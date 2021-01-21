<?php
/**
 * Notes about the Auth module
 * The "service-key" cooresponds to a code that is a company object identifier
 * The "license-key" cooresponds to a code that is a license object identifier
 *
 * Licenses can belong to a company in a 1:M way
 * Companies can have different permissions to act on a license's object
 *
 */

namespace Test\B_Auth;

class A_Alpha_Test extends \Test\Components\OpenTHC_Test_Case
{

	public function test_factory()
	{
		$cfg = [
			'code' => $_ENV['metrc-cfg-code'],
			'service-key' => $_ENV['metrc-cfg-service-key'],
			'license-key' => $_ENV['metrc-license-g'],
		];
		$cre = \OpenTHC\CRE::factory($cfg);
		$p1 = $cre->ping();
		$this->assertIsArray($p1);
		$this->assertValidResponse($p1);
	}

	public function test_auth()
	{
		$c = new \OpenTHC\CRE\Metrc([
			'code' => $_ENV['metrc-cfg-code'],
			'service-key' => $_ENV['metrc-cfg-service-key'],
			'license-key' => $_ENV['metrc-license-g'],
		]);
		$p1 = $c->ping();
		$this->assertIsArray($p1);
		$this->assertValidResponse($p1);
	}

	public function test_open_fail()
	{
		$c = new \OpenTHC\CRE\Metrc([
			'code' => 'garbage-data',
			'service-key' => 'garbage-data',
			'license-key' => 'garbage-data',
		]);
		$p1 = $c->ping();

		$res = $this->assertValidResponse($p1, 403);
		var_dump($p1);
	}

	function test_open_pass()
	{
		// TEST COMPANY A
		// $res = $this->_post('/auth/open', [
		$c = new \OpenTHC\CRE\Adapter\Metrc([
			'code' => $_ENV['metrc-cfg-code'],
			'service-key' => $_ENV['metrc-cfg-service-key'],
			'license-key' => $_ENV['metrc-license-g'],
		]);

		$p1 = $c->ping();
		$this->assertValidResponse($p1, 200);

		$this->assertIsArray($p1);
		$this->assertCount(3, $p1);
		$this->assertMatchesRegularExpression('/\w{26,256}/', $res['data']);
	}

	function test_open_fail_company_license()
	{
		$c = new \OpenTHC\CRE\Adapter\Metrc([
			'code' => $_ENV['metrc-cfg-code'],
			'service-key' => $_ENV['metrc-cfg-service-key'],
			'license-key' => 'garbage-data',
		]);
		$p1 = $c->ping();

		$res = $this->assertValidResponse($p1, 500);
		var_dump($p1);
	}

}
