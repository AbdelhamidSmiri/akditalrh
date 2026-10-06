<?php

App::uses('CakeEmail', 'Network/Email');

/**
 * Application mailer: neutral X-Mailer header instead of the framework default.
 */
class AppEmail extends CakeEmail
{
	public const EMAIL_CLIENT = 'MGR Mailer';
}
