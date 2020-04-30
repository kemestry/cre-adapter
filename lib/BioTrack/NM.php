<?php
/**
 * BioTrackTHC Interface - New Mexico
 */

namespace OpenTHC\CRE\Adapter\Biotrack;

class NM extends \OpenTHC\CRE\Adapter\BioTrack
{
	protected $_api_base = 'https://pipe.openthc.com/stem/biotrack/nm';
	// protected $_api_base = 'https://mcp-tracking.nmhealth.org/serverjson.asp';
	// protected $_api_host = 'mcp-tracking.nmhealth.org';

	/**
	 * listSyncObjects but strips out the two objects not used in New Mexito
	 * @return array of syncObjects
	 */
	function listSyncObjects()
	{
		$ret = parent::listSyncObjects();
		unset($ret['third_party_transporter']);
		unset($ret['id_preassign']);
		return $ret;
	}

}
