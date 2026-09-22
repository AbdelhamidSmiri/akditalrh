<?php

App::uses('AppHelper', 'View/Helper');
App::uses('SecureId', 'Lib');

/**
 * Opaque IDs in links: $this->SecureId->encode('reservation', $id)
 */
class SecureIdHelper extends AppHelper
{
	public function encode($resource, $id)
	{
		return SecureId::encode($resource, $id);
	}
}
