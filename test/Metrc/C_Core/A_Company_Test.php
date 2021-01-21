<?php
/**
 *
 */

namespace Test\Metrc\C_Core;

class A_Company_Test extends \Test\Components\OpenTHC_Test_Case
{
	protected function setUp() : void
	{
		$cfg = [
			'code' => $_ENV['metrc-cfg-code'],
			'service-key' => $_ENV['metrc-cfg-service-key'],
			'license-key' => $_ENV['metrc-license-g'],
		];
		$this->cre = \OpenTHC\CRE::factory($cfg);
	}

	protected function test_employees()
	{
		// GET /employees/v1/
		$res = $this->cre->company()->getEmployees();
		$this->assertValidResponse($res, 200);
		$this->assertArray($res);
	}

	protected function test_employees_license_filter()
	{
		$q = $_ENV['metrc-license-g'];
		// GET /employees/v1/
		$res = $this->cre->company()->getEmployees($q);
		$this->assertValidResponse($res, 200);
		$this->assertArray($res);
	}
}
