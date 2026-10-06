<?php

App::uses('Controller', 'Controller');
App::uses('MigratingPasswordHasher', 'Controller/Component/Auth');
App::uses('AuditLog', 'Lib');
App::uses('Env', 'Lib');

class AppController extends Controller
{
	public $components = array(
		'Session',
		'Flash',
		'Security' => array(
			'csrfCheck' => true,
			'csrfUseOnce' => false,
			'csrfExpires' => '+2 hours',
			'validatePost' => false,
			'blackHoleCallback' => 'securityBlackhole',
		),
		'Auth' => array(
			'loginRedirect' => array('controller' => 'users', 'action' => 'view'),
			'logoutRedirect' => array('controller' => 'users', 'action' => 'login'),
			'authError' => 'Veuillez vous connecter pour accéder à cette page.',
			'authorize' => array('Controller'),
			'authenticate' => array(
				'Form' => array(
					'passwordHasher' => 'Migrating',
				),
			),
		),
	);

	public $helpers = array(
		'Session',
		'Flash',
		'Html',
		'Form',
		'File',
		'SecureId',
	);

	public function beforeFilter()
	{
		$this->Auth->allow('login');
		$this->_enforceHttps();
		$this->_sendSecurityHeaders();
		$this->_enforceMfaGate();
	}

	public function beforeRender()
	{
		$this->_sendSecurityHeaders();
	}

	/**
	 * Deny by default — each controller must explicitly authorize actions.
	 */
	public function isAuthorized($user)
	{
		return false;
	}

	public function securityBlackhole($type)
	{
		AuditLog::write('security_blackhole', array('type' => $type));
		if ($type === 'csrf') {
			throw new BadRequestException('Jeton de sécurité invalide ou expiré. Rechargez la page et réessayez.');
		}
		throw new BadRequestException('Requête refusée par le contrôle de sécurité.');
	}

	protected function currentRole()
	{
		return AuthComponent::user('Role.role');
	}

	protected function isAdmin()
	{
		return $this->currentRole() === 'Admin';
	}

	protected function isAgence()
	{
		return $this->currentRole() === 'Agence';
	}

	/**
	 * Roles that must enroll MFA (comma-separated in MFA_REQUIRED_ROLES, e.g. "Admin" or "Admin,Agence").
	 *
	 * @return array
	 */
	protected function mfaRequiredRoles()
	{
		$raw = (string)Env::get('MFA_REQUIRED_ROLES', 'Admin');
		$roles = array();
		foreach (explode(',', $raw) as $role) {
			$role = trim($role);
			if ($role !== '') {
				$roles[] = $role;
			}
		}
		return $roles;
	}

	/**
	 * Whether the current user's role must have MFA enabled.
	 *
	 * @return bool
	 */
	protected function isMfaRequiredForCurrentUser()
	{
		$role = $this->currentRole();
		if ($role === null || $role === '') {
			return false;
		}
		return in_array($role, $this->mfaRequiredRoles(), true);
	}

	/**
	 * After password login: force MFA enrollment for required roles,
	 * then block the app until MFA challenge succeeds when MFA is enabled.
	 */
	protected function _enforceMfaGate()
	{
		if (!$this->Auth->user()) {
			return;
		}

		// Public media must stay reachable while a session is mid-MFA / enrollment
		if (strtolower($this->name) === 'media' && $this->action === 'serve') {
			return;
		}
		if (strtolower($this->name) === 'files' && in_array($this->action, array('serve', 'download'), true)) {
			return;
		}

		$controller = strtolower($this->name);
		$action = $this->action;

		// Required roles without MFA yet → force enrollment
		if ($this->isMfaRequiredForCurrentUser() && !$this->Auth->user('totp_enabled')) {
			$enrollAllowed = array('mfa_setup', 'mfa_enable', 'logout');
			if ($controller === 'users' && in_array($action, $enrollAllowed, true)) {
				return;
			}
			AuditLog::write('mfa_enrollment_enforced');
			$this->Session->setFlash(
				'La double authentification (MFA) est obligatoire pour votre compte. Veuillez l’activer.',
				'Flash/error',
				array(),
				'error'
			);
			$this->redirect(array('controller' => 'users', 'action' => 'mfa_setup'));
			return;
		}

		if (!$this->Auth->user('totp_enabled')) {
			return;
		}
		if ($this->Session->read('Auth.mfa_verified')) {
			return;
		}

		$allowed = array('mfa_verify', 'logout');
		if ($controller === 'users' && in_array($action, $allowed, true)) {
			return;
		}

		$this->redirect(array('controller' => 'users', 'action' => 'mfa_verify'));
	}

	/**
	 * Decode an opaque URL token into a numeric id (rejects plain integers).
	 *
	 * @param string $resource
	 * @param string $token
	 * @return int
	 */
	protected function decodeOpaqueId($resource, $token)
	{
		App::uses('SecureId', 'Lib');
		$id = SecureId::decode($resource, $token);
		if ($id === null) {
			AuditLog::write('opaque_id_rejected', array('resource' => $resource));
			throw new NotFoundException('Ressource introuvable.');
		}
		return $id;
	}

	/**
	 * Ensure the current agent owns the record (Admin/Agence bypass).
	 */
	protected function ensureOwnerOrPrivileged($model, $id, $userField = 'user_id', $label = 'ressource')
	{
		$role = $this->currentRole();
		if ($role === 'Admin' || $role === 'Agence') {
			return;
		}

		$alias = $model->alias;
		$record = $model->find('first', array(
			'conditions' => array($alias . '.' . $model->primaryKey => $id),
			'fields' => array($alias . '.' . $model->primaryKey, $alias . '.' . $userField),
			'recursive' => -1,
		));

		if (empty($record) || (int)$record[$alias][$userField] !== (int)AuthComponent::user('id')) {
			AuditLog::write('ownership_denied', array(
				'model' => $alias,
				'record_id' => $id,
			));
			throw new ForbiddenException('Accès non autorisé à cette ' . $label . '.');
		}
	}

	protected function _enforceHttps()
	{
		$force = Env::get('FORCE_HTTPS', false);
		if (!$force) {
			return;
		}
		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
			return;
		}
		if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
			return;
		}
		$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'localhost';
		$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
		$this->redirect('https://' . $host . $uri, 301);
	}

	protected function _sendSecurityHeaders()
	{
		if (!isset($this->response) || !is_object($this->response)) {
			return;
		}

		$this->response->header('X-Frame-Options', 'SAMEORIGIN');
		$this->response->header('X-Content-Type-Options', 'nosniff');
		$this->response->header('Referrer-Policy', 'strict-origin-when-cross-origin');
		$this->response->header('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
		$this->response->header(
			'Content-Security-Policy',
			"default-src 'self'; " .
			"script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://code.jquery.com; " .
			"style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.googleapis.com; " .
			"font-src 'self' data: https://cdn.jsdelivr.net https://cdnjs.cloudflare.com https://fonts.gstatic.com; " .
			"img-src 'self' data: blob: https:; " .
			"connect-src 'self'; " .
			"frame-ancestors 'self'; " .
			"base-uri 'self'; " .
			"form-action 'self'"
		);

		$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
			|| (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');
		if ($https || Env::get('FORCE_HTTPS', false)) {
			$this->response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
		}
	}
}
