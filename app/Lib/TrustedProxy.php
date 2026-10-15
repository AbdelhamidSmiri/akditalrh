<?php

/**
 * Normalizes $_SERVER for requests relayed by a reverse proxy (TLS termination).
 *
 * Forwarded headers are honoured ONLY when REMOTE_ADDR is listed in TRUSTED_PROXIES,
 * otherwise any client could spoof X-Forwarded-For to dodge the login throttle.
 */
class TrustedProxy
{
	/** @var bool */
	protected static $viaProxy = false;

	public static function apply()
	{
		// The public host comes from APP_BASE_URL / Host, never from a client-supplied forwarding header.
		unset($_SERVER['HTTP_X_FORWARDED_HOST'], $_SERVER['HTTP_X_FORWARDED_SERVER'], $_SERVER['HTTP_X_ORIGINAL_HOST']);

		$remote = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';
		if ($remote === '' || !self::isTrusted($remote)) {
			return;
		}
		self::$viaProxy = true;
		$_SERVER['MGR_PROXY_ADDR'] = $remote;

		if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
			$proto = strtolower(trim(explode(',', $_SERVER['HTTP_X_FORWARDED_PROTO'])[0]));
			if ($proto === 'https') {
				$_SERVER['HTTPS'] = 'on';
				$_SERVER['SERVER_PORT'] = 443;
			}
		}

		$client = self::clientFromHeaders();
		if ($client !== null) {
			$_SERVER['REMOTE_ADDR'] = $client;
		}
	}

	/**
	 * @return bool True when the current request was relayed by a trusted proxy.
	 */
	public static function viaProxy()
	{
		return self::$viaProxy;
	}

	/**
	 * Right-most X-Forwarded-For entry that is not itself a trusted proxy.
	 *
	 * @return string|null
	 */
	protected static function clientFromHeaders()
	{
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$hops = array_reverse(array_map('trim', explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])));
			foreach ($hops as $hop) {
				if (filter_var($hop, FILTER_VALIDATE_IP) && !self::isTrusted($hop)) {
					return $hop;
				}
			}
		}
		if (!empty($_SERVER['HTTP_X_REAL_IP'])) {
			$real = trim($_SERVER['HTTP_X_REAL_IP']);
			if (filter_var($real, FILTER_VALIDATE_IP)) {
				return $real;
			}
		}
		return null;
	}

	/**
	 * @param string $ip
	 * @return bool
	 */
	public static function isTrusted($ip)
	{
		foreach (self::trustedList() as $entry) {
			if (self::matches($ip, $entry)) {
				return true;
			}
		}
		return false;
	}

	/**
	 * @return array IPs or CIDR ranges from TRUSTED_PROXIES (comma-separated).
	 */
	protected static function trustedList()
	{
		$raw = class_exists('Env') ? (string)Env::get('TRUSTED_PROXIES', '') : '';
		$list = array();
		foreach (explode(',', $raw) as $entry) {
			$entry = trim($entry);
			if ($entry !== '') {
				$list[] = $entry;
			}
		}
		return $list;
	}

	/**
	 * @param string $ip
	 * @param string $entry Single IP or CIDR (IPv4 / IPv6)
	 * @return bool
	 */
	protected static function matches($ip, $entry)
	{
		if (strpos($entry, '/') === false) {
			return $ip === $entry;
		}
		list($subnet, $bits) = explode('/', $entry, 2);
		$ipBin = @inet_pton($ip);
		$subnetBin = @inet_pton($subnet);
		if ($ipBin === false || $subnetBin === false || strlen($ipBin) !== strlen($subnetBin)) {
			return false;
		}
		$bits = (int)$bits;
		$maxBits = strlen($ipBin) * 8;
		if ($bits < 0 || $bits > $maxBits) {
			return false;
		}
		$fullBytes = intdiv($bits, 8);
		if (substr($ipBin, 0, $fullBytes) !== substr($subnetBin, 0, $fullBytes)) {
			return false;
		}
		$remainder = $bits % 8;
		if ($remainder === 0) {
			return true;
		}
		$mask = (0xFF << (8 - $remainder)) & 0xFF;
		return (ord($ipBin[$fullBytes]) & $mask) === (ord($subnetBin[$fullBytes]) & $mask);
	}
}
