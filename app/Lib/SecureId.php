<?php

/**
 * Opaque, non-sequential resource IDs for URLs (letters + digits).
 * Deterministic: same resource+id always yields the same token.
 */
class SecureId
{
	/**
	 * @param string $resource e.g. reservation, volreservation
	 * @param int $id
	 * @return string Hex token (~48 chars)
	 */
	public static function encode($resource, $id)
	{
		$id = (int)$id;
		$resource = strtolower(preg_replace('/[^a-z0-9_]/', '', (string)$resource));
		$key = self::key($resource);
		$iv = self::iv($resource);

		$plain = pack('N', $id);
		$cipher = openssl_encrypt($plain, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
		if ($cipher === false) {
			throw new RuntimeException('SecureId encrypt failed.');
		}

		$hex = bin2hex($cipher);
		$mac = substr(hash_hmac('sha256', $resource . '|' . $hex, self::secret()), 0, 16);
		return $hex . $mac;
	}

	/**
	 * @param string $resource
	 * @param string $token
	 * @return int|null
	 */
	public static function decode($resource, $token)
	{
		$resource = strtolower(preg_replace('/[^a-z0-9_]/', '', (string)$resource));
		$token = strtolower(preg_replace('/[^a-f0-9]/', '', (string)$token));

		// Reject plain numeric IDs
		if ($token === '' || ctype_digit($token)) {
			return null;
		}
		if (strlen($token) < 20) {
			return null;
		}

		$mac = substr($token, -16);
		$hex = substr($token, 0, -16);
		$expected = substr(hash_hmac('sha256', $resource . '|' . $hex, self::secret()), 0, 16);
		if (!hash_equals($expected, $mac)) {
			return null;
		}

		$cipher = @hex2bin($hex);
		if ($cipher === false) {
			return null;
		}

		$plain = openssl_decrypt($cipher, 'AES-128-CBC', self::key($resource), OPENSSL_RAW_DATA, self::iv($resource));
		if ($plain === false || strlen($plain) < 4) {
			return null;
		}

		$unpacked = unpack('Nid', substr($plain, 0, 4));
		if (empty($unpacked['id'])) {
			return null;
		}

		return (int)$unpacked['id'];
	}

	protected static function secret()
	{
		$salt = Configure::read('Security.salt');
		if (!$salt || $salt === 'koko') {
			throw new RuntimeException('SECURITY_SALT is not configured securely.');
		}
		return $salt;
	}

	protected static function key($resource)
	{
		return substr(hash('sha256', self::secret() . '|sid-key|' . $resource, true), 0, 16);
	}

	protected static function iv($resource)
	{
		return substr(hash('sha256', self::secret() . '|sid-iv|' . $resource, true), 0, 16);
	}
}
