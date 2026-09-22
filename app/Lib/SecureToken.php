<?php

/**
 * HMAC-signed, time-limited tokens for public email links.
 */
class SecureToken
{
	/**
	 * @param mixed $payload Scalar or array payload (e.g. record id)
	 * @param int $ttlSeconds Default 7 days
	 * @return string
	 */
	public static function create($payload, $ttlSeconds = 604800)
	{
		$body = array(
			'p' => $payload,
			'e' => time() + (int)$ttlSeconds,
			'n' => bin2hex(random_bytes(8)),
		);
		$encoded = self::base64UrlEncode(json_encode($body));
		$signature = hash_hmac('sha256', $encoded, self::secret());
		// Use '--' (not '.') so CakePHP does not treat the token as a file extension.
		return $encoded . '--' . $signature;
	}

	/**
	 * @param string $token
	 * @return mixed|null Payload on success, null if invalid/expired
	 */
	public static function parse($token)
	{
		if (!is_string($token) || strpos($token, '--') === false) {
			return null;
		}

		list($encoded, $signature) = explode('--', $token, 2);
		if ($encoded === '' || $signature === '') {
			return null;
		}

		$expected = hash_hmac('sha256', $encoded, self::secret());
		if (!hash_equals($expected, $signature)) {
			return null;
		}

		$json = self::base64UrlDecode($encoded);
		$data = json_decode($json, true);
		if (!is_array($data) || !isset($data['p'], $data['e'])) {
			return null;
		}

		if ((int)$data['e'] < time()) {
			return null;
		}

		return $data['p'];
	}

	protected static function secret()
	{
		$salt = Configure::read('Security.salt');
		if (!$salt || $salt === 'koko') {
			throw new RuntimeException('SECURITY_SALT is not configured securely.');
		}
		return $salt;
	}

	protected static function base64UrlEncode($data)
	{
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}

	protected static function base64UrlDecode($data)
	{
		$remainder = strlen($data) % 4;
		if ($remainder) {
			$data .= str_repeat('=', 4 - $remainder);
		}
		return base64_decode(strtr($data, '-_', '+/'));
	}
}
