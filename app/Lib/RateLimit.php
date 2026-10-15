<?php

App::uses('Cache', 'Cache');

/**
 * Fixed-window request counters (Cache 'default', File engine: keep windows <= 1 hour).
 */
class RateLimit
{
	/**
	 * Count one request and tell whether the caller is now over the limit.
	 *
	 * @param string $bucket Feature name, e.g. "forgot_ip"
	 * @param string $id Client IP, account name...
	 * @param int $max Requests allowed per window
	 * @param int $windowSeconds
	 * @return bool True when this request exceeds the limit
	 */
	public static function hit($bucket, $id, $max, $windowSeconds)
	{
		$key = self::key($bucket, $id);
		$data = self::current($key, $windowSeconds);
		$data['count']++;
		Cache::write($key, $data, 'default');
		return $data['count'] > $max;
	}

	/**
	 * @return bool True when the limit is already reached (does not count a request).
	 */
	public static function exceeded($bucket, $id, $max, $windowSeconds)
	{
		$data = self::current(self::key($bucket, $id), $windowSeconds);
		return $data['count'] >= $max;
	}

	/**
	 * Distinct values seen for an id within the window (e.g. usernames tried from one IP).
	 *
	 * @return int Number of distinct values, including this one
	 */
	public static function addDistinct($bucket, $id, $value, $windowSeconds)
	{
		$key = self::key($bucket, $id);
		$data = self::current($key, $windowSeconds);
		$data['values'][md5((string)$value)] = true;
		Cache::write($key, $data, 'default');
		return count($data['values']);
	}

	public static function reset($bucket, $id)
	{
		Cache::delete(self::key($bucket, $id), 'default');
	}

	protected static function current($key, $windowSeconds)
	{
		$data = Cache::read($key, 'default');
		if (!is_array($data) || empty($data['start']) || (time() - $data['start']) > $windowSeconds) {
			$data = array('start' => time(), 'count' => 0, 'values' => array());
		}
		return $data;
	}

	protected static function key($bucket, $id)
	{
		return 'rate_' . preg_replace('/[^a-z0-9_]/i', '', $bucket) . '_' . md5(strtolower(trim((string)$id)));
	}
}
