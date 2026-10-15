<?php

App::uses('Cache', 'Cache');
App::uses('AuditLog', 'Lib');
App::uses('RateLimit', 'Lib');
App::uses('TrustedProxy', 'Lib');

/**
 * Login brute-force protection (Cache-backed).
 *
 * - per (IP + account): 5 failures / 15 min -> 15 min lock (only that IP is locked out of the
 *   account, so a third party cannot lock a colleague out from another workstation);
 * - per IP: 10 failures / 15 min -> 15 min lock;
 * - password spraying: one IP failing on 5 distinct accounts / 15 min -> 30 min lock.
 */
class LoginThrottle
{
	const MAX_ATTEMPTS = 5;
	const WINDOW_SECONDS = 900; // 15 minutes
	const LOCK_SECONDS = 900;

	const MAX_IP_ATTEMPTS = 10;

	const SPRAY_DISTINCT_ACCOUNTS = 5;
	const SPRAY_LOCK_SECONDS = 1800;

	public static function isBlocked($username)
	{
		if (self::isCounterLocked(self::key($username))) {
			return true;
		}
		return self::ipLimitsApply() && self::isCounterLocked(self::ipKey());
	}

	public static function remainingLockSeconds($username)
	{
		$seconds = self::counterLockSeconds(self::key($username));
		if (self::ipLimitsApply()) {
			$seconds = max($seconds, self::counterLockSeconds(self::ipKey()));
		}
		return $seconds;
	}

	public static function hitFailure($username)
	{
		$data = self::increment(self::key($username), self::MAX_ATTEMPTS, self::LOCK_SECONDS);
		$ipData = array();
		$distinct = 0;

		if (self::ipLimitsApply()) {
			$ipData = self::increment(self::ipKey(), self::MAX_IP_ATTEMPTS, self::LOCK_SECONDS);
			$distinct = RateLimit::addDistinct(
				'login_spray',
				AuditLog::clientIp(),
				strtolower(trim((string)$username)),
				self::WINDOW_SECONDS
			);
			if ($distinct >= self::SPRAY_DISTINCT_ACCOUNTS) {
				$ipData = self::lock(self::ipKey(), self::SPRAY_LOCK_SECONDS);
				AuditLog::write('login_spray_suspected', array(
					'username' => $username,
					'distinct_accounts' => $distinct,
				));
				return $data;
			}
		}

		if (!empty($data['locked_until'])) {
			AuditLog::write('login_locked', array(
				'username' => $username,
				'attempts' => $data['attempts'],
			));
		} elseif (!empty($ipData['locked_until'])) {
			AuditLog::write('login_locked_ip', array(
				'username' => $username,
				'attempts' => $ipData['attempts'],
			));
		} else {
			AuditLog::write('login_failed', array(
				'username' => $username,
				'attempts' => $data['attempts'],
			));
		}

		return $data;
	}

	public static function clear($username)
	{
		Cache::delete(self::key($username), 'default');
		if (self::ipLimitsApply()) {
			Cache::delete(self::ipKey(), 'default');
		}
	}

	/**
	 * When the proxy does not forward the client address, every user shares the proxy IP:
	 * IP-wide locks would then lock the whole company out at once.
	 *
	 * @return bool
	 */
	protected static function ipLimitsApply()
	{
		$ip = AuditLog::clientIp();
		if ($ip === 'cli' || TrustedProxy::isTrusted($ip)) {
			return false;
		}
		return true;
	}

	protected static function increment($key, $maxAttempts, $lockSeconds)
	{
		$data = Cache::read($key, 'default');
		if (!is_array($data) || empty($data['window_start']) || (time() - $data['window_start']) > self::WINDOW_SECONDS) {
			$data = array(
				'window_start' => time(),
				'attempts' => 0,
				'locked_until' => 0,
			);
		}

		$data['attempts']++;
		if ($data['attempts'] >= $maxAttempts) {
			$data['locked_until'] = time() + $lockSeconds;
		}

		Cache::write($key, $data, 'default');
		return $data;
	}

	protected static function lock($key, $seconds)
	{
		$data = Cache::read($key, 'default');
		if (!is_array($data)) {
			$data = array('window_start' => time(), 'attempts' => 0);
		}
		$data['locked_until'] = max(isset($data['locked_until']) ? (int)$data['locked_until'] : 0, time() + $seconds);
		Cache::write($key, $data, 'default');
		return $data;
	}

	protected static function isCounterLocked($key)
	{
		$data = Cache::read($key, 'default');
		return is_array($data) && !empty($data['locked_until']) && $data['locked_until'] > time();
	}

	protected static function counterLockSeconds($key)
	{
		$data = Cache::read($key, 'default');
		if (!is_array($data) || empty($data['locked_until'])) {
			return 0;
		}
		return max(0, (int)$data['locked_until'] - time());
	}

	protected static function key($username)
	{
		$ip = AuditLog::clientIp();
		$normalized = strtolower(trim((string)$username));
		return 'login_throttle_' . md5($ip . '|' . $normalized);
	}

	protected static function ipKey()
	{
		return 'login_throttle_ip_' . md5(AuditLog::clientIp());
	}
}
