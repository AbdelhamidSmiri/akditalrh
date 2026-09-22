<?php

App::uses('Cache', 'Cache');
App::uses('AuditLog', 'Lib');

/**
 * Simple login brute-force protection (Cache-backed).
 */
class LoginThrottle
{
	const MAX_ATTEMPTS = 5;
	const WINDOW_SECONDS = 900; // 15 minutes
	const LOCK_SECONDS = 900;

	/** Higher ceiling for a single IP trying many different accounts. */
	const MAX_IP_ATTEMPTS = 15;

	public static function isBlocked($username)
	{
		return self::isCounterLocked(self::key($username)) || self::isCounterLocked(self::ipKey());
	}

	public static function remainingLockSeconds($username)
	{
		return max(
			self::counterLockSeconds(self::key($username)),
			self::counterLockSeconds(self::ipKey())
		);
	}

	public static function hitFailure($username)
	{
		$data = self::increment(self::key($username), self::MAX_ATTEMPTS);
		$ipData = self::increment(self::ipKey(), self::MAX_IP_ATTEMPTS);

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
		Cache::delete(self::ipKey(), 'default');
	}

	protected static function increment($key, $maxAttempts)
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
			$data['locked_until'] = time() + self::LOCK_SECONDS;
		}

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
