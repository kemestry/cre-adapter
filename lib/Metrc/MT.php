<?php
/**
 * Franwell / Metrc Interface for Montana
 */

namespace OpenTHC\CRE\Adapter\Metrc;

class MT extends \OpenTHC\CRE\Adapter\Metrc
{
	protected $_api_base = 'https://api-mt.metrc.com';

	function setTestMode()
	{
		$this->_api_base = 'https://sandbox-api-co.metrc.com';
		$this->_api_host = null;

		$this->_api_base = 'https://pipe.openthc.com/stem/metrc/sandbox-api-co.metrc.com';

	}
}
