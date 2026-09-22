<?php

App::uses('CakeLog', 'Log');

/**
 * Lightweight security audit logger (file-based).
 */
class AuditLog
{
	public static function write($event, $context = array())
	{
		$payload = array_merge(array(
			'event' => $event,
			'time' => date('c'),
			'ip' => self::clientIp(),
			'user_id' => AuthComponent::user('id'),
			'username' => AuthComponent::user('username'),
			'role' => AuthComponent::user('Role.role'),
			'url' => isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : null,
		), $context);

		CakeLog::write('audit', json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
	}

	public static function clientIp()
	{
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$parts = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			return trim($parts[0]);
		}
		return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'cli';
	}
}
