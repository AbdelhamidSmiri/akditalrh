<?php

/**
 * Minimal RFC 6238 TOTP (Google Authenticator compatible).
 */
class Totp
{
	const PERIOD = 30;
	const DIGITS = 6;

	public static function generateSecret($length = 16)
	{
		$bytes = random_bytes($length);
		return self::base32Encode($bytes);
	}

	public static function getCode($secret, $timeSlice = null)
	{
		if ($timeSlice === null) {
			$timeSlice = (int)floor(time() / self::PERIOD);
		}
		$secretKey = self::base32Decode($secret);
		$time = pack('N*', 0, $timeSlice);
		$hash = hash_hmac('sha1', $time, $secretKey, true);
		$offset = ord(substr($hash, -1)) & 0x0F;
		$truncated = (
			((ord($hash[$offset]) & 0x7F) << 24) |
			((ord($hash[$offset + 1]) & 0xFF) << 16) |
			((ord($hash[$offset + 2]) & 0xFF) << 8) |
			(ord($hash[$offset + 3]) & 0xFF)
		);
		$code = $truncated % pow(10, self::DIGITS);
		return str_pad((string)$code, self::DIGITS, '0', STR_PAD_LEFT);
	}

	public static function verify($secret, $code, $window = 1)
	{
		$code = preg_replace('/\s+/', '', (string)$code);
		if (!preg_match('/^\d{6}$/', $code) || !$secret) {
			return false;
		}
		$timeSlice = (int)floor(time() / self::PERIOD);
		for ($i = -$window; $i <= $window; $i++) {
			if (hash_equals(self::getCode($secret, $timeSlice + $i), $code)) {
				return true;
			}
		}
		return false;
	}

	public static function provisioningUri($secret, $accountName, $issuer = 'Akdital MGR')
	{
		$label = rawurlencode($issuer . ':' . $accountName);
		$query = http_build_query(array(
			'secret' => $secret,
			'issuer' => $issuer,
			'algorithm' => 'SHA1',
			'digits' => self::DIGITS,
			'period' => self::PERIOD,
		), '', '&', PHP_QUERY_RFC3986);
		return 'otpauth://totp/' . $label . '?' . $query;
	}

	protected static function base32Encode($data)
	{
		$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
		$binary = '';
		foreach (str_split($data) as $char) {
			$binary .= str_pad(decbin(ord($char)), 8, '0', STR_PAD_LEFT);
		}
		$fiveBit = str_split($binary, 5);
		$base32 = '';
		foreach ($fiveBit as $chunk) {
			if (strlen($chunk) < 5) {
				$chunk = str_pad($chunk, 5, '0', STR_PAD_RIGHT);
			}
			$base32 .= $alphabet[bindec($chunk)];
		}
		return $base32;
	}

	protected static function base32Decode($secret)
	{
		$alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
		$secret = strtoupper(preg_replace('/[^A-Z2-7]/', '', (string)$secret));
		$binary = '';
		foreach (str_split($secret) as $char) {
			$pos = strpos($alphabet, $char);
			if ($pos === false) {
				continue;
			}
			$binary .= str_pad(decbin($pos), 5, '0', STR_PAD_LEFT);
		}
		$bytes = '';
		foreach (str_split($binary, 8) as $chunk) {
			if (strlen($chunk) === 8) {
				$bytes .= chr(bindec($chunk));
			}
		}
		return $bytes;
	}
}
