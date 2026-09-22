<?php

App::uses('AbstractPasswordHasher', 'Controller/Component/Auth');
App::uses('Security', 'Utility');

/**
 * Prefer bcrypt; still accept legacy CakePHP SHA1(salt + password) hashes.
 */
class MigratingPasswordHasher extends AbstractPasswordHasher
{
	public function hash($password)
	{
		return password_hash($password, PASSWORD_BCRYPT);
	}

	public function check($password, $hashedPassword)
	{
		if ($hashedPassword === null || $hashedPassword === '') {
			return false;
		}

		if (preg_match('/^\$2[ayb]\$/', $hashedPassword)) {
			return password_verify($password, $hashedPassword);
		}

		$legacySalt = Configure::read('Security.legacySalt');
		if (!$legacySalt) {
			$legacySalt = 'koko';
		}

		$legacyHash = Security::hash($password, 'sha1', $legacySalt);
		return hash_equals($legacyHash, $hashedPassword);
	}

	public static function needsRehash($hashedPassword)
	{
		return !preg_match('/^\$2[ayb]\$/', (string)$hashedPassword);
	}
}
