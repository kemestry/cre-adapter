<?php
/**
 * Test Helper for LeafData
 */

namespace Test;

class OpenTHC_LeafData_Test extends OpenTHC_Base_TestCase
{
	/**
	* Intends to become an assert wrapper for a bunch of common response checks
	* @param $res, Response Object
	* @return void
	*/
	function assertValidResponse($res, $code=200, $dump=null)
	{
		$this->assertNotEmpty($res);

		// Dump on Errors
		$hrc = $res->getStatusCode();
		switch ($hrc) {
		case 422:
		case 500:
			if (empty($dump)) {
				$dump = sprintf('%d Response Code', $hrc);
			}
			break;
		}

		$this->raw = $res->getBody()->getContents();

		if (!empty($dump)) {
			echo "\n<<<$dump<<<\n{$this->raw}\n###\n";
		}

		$ret = \json_decode($this->raw, true);

		//$this->assertEquals('HTTPS', $res->getProtocol());
		$this->assertEquals($code, $res->getStatusCode());
		// $this->assertEquals('application/json', $res->getHeaderLine('content-type')); // RFCs
		$this->assertEquals('text/json; charset=UTF-8', $res->getHeaderLine('content-type')); // LeafData
		$this->assertIsArray($ret);
		$this->assertCount(2, $ret);

		$this->assertIsArray($ret['data']);
		$this->assertIsArray($ret['meta']);

		return $ret;

	}

	function find_random_batch_of_type($t)
	{
		$res = $this->ghc->get('batches?f_type=' .$t);
		$res = $this->assertValidResponse($res);
		$this->assertIsArray($res['data']);
		$this->assertGreaterThan(2, $res['data']);

		$rnd_list = [];
		foreach ($res['data'] as $b) {
			if ('open' == $b['status']) {
				$rnd_list[] = $b;
			}
		}

		$i = array_rand($rnd_list);
		$B = $rnd_list[$i];

		return $B;

	}

	/**
	 *
	 * @param [type] $f [description]
	 * @return [type] [description]
	 */
	function find_random_lot($f=null)
	{
		// @todo Handle Multiple Pages?
		$res = $this->get('inventories');
		$this->assertCount(9, $res);
		$this->assertIsArray($res['data']);
		https://pipe.openthc.com/stem/leafdata/wa/test
		$rnd_list = [];
		foreach ($res['data'] as $x) {
			$rnd_list[] = $x;
		}

		$i = array_rand($rnd_list);
		$r = $rnd_list[$i];

		return $r;

	}


	function find_random_plant($f=null)
	{
		$res = $this->get('plants?f_stage=growing');
		$this->assertCount(9, $res);
		$this->assertIsArray($res['data']);

		// echo "\nWe Found: " . count($res['data']) . " Plants\n";
		// var_dump($res['next_page_url']);

		$rnd_list = [];
		foreach ($res['data'] as $x) {
			if ('growing' == $x['stage']) {
				$rnd_list[] = $x;
			}
		}

		$i = array_rand($rnd_list);
		$r = $rnd_list[$i];

		return $r;

	}

	function find_random_strain()
	{
		$res = $this->get('strains');
		$this->assertCount(9, $res);
		$this->assertIsArray($res['data']);

		$rnd_list = [];
		foreach ($res['data'] as $x) {
			$rnd_list[] = $x;
		}

		$i = array_rand($rnd_list);
		$r = $rnd_list[$i];

		return $r;

	}

	/**
		@param $b The Base URL
	*/
	protected function _api($opt=null)
	{
		// create our http client (Guzzle)
		$cfg = array(
			'debug' => $_ENV['debug-http'],
			'base_uri' => $_ENV['leafdata-url'],
			'allow_redirects' => false,
			'cookies' => true,
			'headers' => [
				'x-mjf-mme-code' => null,
				'x-mjf-key' => null,
			],
			'http_errors' => false,
			'request.options' => array(
				'exceptions' => false,
			),
		);

		if (!empty($opt['license'])) {
			$cfg['headers']['x-mjf-mme-code'] = $opt['license'];
			$cfg['headers']['x-mjf-key'] = $opt['license-secret'];
		}

		$c = new \GuzzleHttp\Client($cfg);

		return $c;
	}

}
