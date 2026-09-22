<?php
/**
 * Email configuration — values from .env when present.
 *
 * Local: MAIL_TRANSPORT=Debug (default)
 * Prod:  MAIL_TRANSPORT=Smtp + SMTP_* credentials
 */
class EmailConfig {

	public $default;
	public $smtp;
	public $fast;

	public function __construct()
	{
		App::uses('Env', 'Lib');

		$transport = Env::get('MAIL_TRANSPORT', 'Debug');
		if (!is_string($transport) || $transport === '') {
			$transport = 'Debug';
		}
		// Normalize common aliases
		$transportNorm = strtolower($transport);
		if ($transportNorm === 'smtp') {
			$transport = 'Smtp';
		} elseif ($transportNorm === 'mail') {
			$transport = 'Mail';
		} elseif ($transportNorm === 'debug') {
			$transport = 'Debug';
		}

		$fromEmail = Env::get('MAIL_FROM', 'noreply@akdital.local');
		$fromName = Env::get('MAIL_FROM_NAME', 'Akdital MGR');
		$from = array($fromEmail => $fromName);

		$host = Env::get('SMTP_HOST', 'localhost');
		$port = (int)Env::get('SMTP_PORT', 25);
		$username = Env::get('SMTP_USER', '');
		$password = Env::get('SMTP_PASS', '');
		$tlsRaw = Env::get('SMTP_TLS', false);
		if (is_bool($tlsRaw)) {
			$tls = $tlsRaw;
		} else {
			$tls = in_array(strtolower((string)$tlsRaw), array('1', 'true', 'yes', 'on'), true);
		}

		$this->default = array(
			'transport' => $transport,
			'from' => $from,
			'host' => $host,
			'port' => $port,
			'timeout' => 30,
			'username' => $username !== '' ? $username : null,
			'password' => $password !== '' ? $password : null,
			'client' => null,
			'log' => ($transport === 'Debug'),
			'tls' => $tls,
		);

		$this->smtp = array(
			'transport' => 'Smtp',
			'from' => $from,
			'host' => $host,
			'port' => $port,
			'timeout' => 30,
			'username' => $username !== '' ? $username : null,
			'password' => $password !== '' ? $password : null,
			'client' => null,
			'log' => false,
			'tls' => $tls,
		);

		$this->fast = array(
			'from' => $fromEmail,
			'sender' => null,
			'to' => null,
			'cc' => null,
			'bcc' => null,
			'replyTo' => null,
			'readReceipt' => null,
			'returnPath' => null,
			'messageId' => true,
			'subject' => null,
			'message' => null,
			'headers' => null,
			'viewRender' => null,
			'template' => false,
			'layout' => false,
			'viewVars' => null,
			'attachments' => null,
			'emailFormat' => null,
			'transport' => 'Debug',
			'log' => true,
		);
	}
}
