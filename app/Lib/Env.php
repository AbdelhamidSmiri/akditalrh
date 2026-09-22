<?php

/**
 * Minimal .env loader (no Composer dependency).
 */
class Env
{
	public static function load($path)
	{
		if (!is_readable($path)) {
			return;
		}

		$lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		if ($lines === false) {
			return;
		}

		foreach ($lines as $line) {
			$line = trim($line);
			if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
				continue;
			}

			list($name, $value) = explode('=', $line, 2);
			$name = trim($name);
			$value = trim($value);
			$value = trim($value, "\"'");

			if ($name === '' || getenv($name) !== false) {
				continue;
			}

			putenv($name . '=' . $value);
			$_ENV[$name] = $value;
			$_SERVER[$name] = $value;
		}
	}

	public static function get($key, $default = null)
	{
		$value = getenv($key);
		if ($value === false) {
			return $default;
		}

		switch (strtolower($value)) {
			case 'true':
			case '(true)':
				return true;
			case 'false':
			case '(false)':
				return false;
			case 'empty':
			case '(empty)':
				return '';
			case 'null':
			case '(null)':
				return null;
		}

		return $value;
	}
}
