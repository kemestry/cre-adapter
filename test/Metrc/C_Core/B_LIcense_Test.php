<?php
/**
 *
 */

namespace Test\Metrc\C_Core;

class B_License_Test extends \Test\Components\OpenTHC_Test_Case
{
	protected $_tmp_file = '/tmp/unit-test-license.json';
	protected $cre;

	protected function setUp() : void
	{
		$cfg = [
			'code' => $_ENV['metrc-cfg-code'],
			'service-key' => $_ENV['metrc-cfg-service-key'],
			'license-key' => $_ENV['metrc-license-g'],
		];
		$this->cre = \OpenTHC\CRE::factory($cfg);
	}

	protected function test_search()
	{
		$res = $this->cre->search();
		$this->assertValidRespone($res);
	}

	public function test_single() 
	{
		$q = $_ENV['metrc-license-g'];
		$res = $this->cre->search($q);
		$this->assertValidRespone($res);
		$this->assertCount(1, $res);
		$this->assertEqual($q, $res['facility_id']);
	}

	public function test_update() {}
	public function test_delete() {}

}
