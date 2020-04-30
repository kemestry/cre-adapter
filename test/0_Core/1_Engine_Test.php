<?php
/**
 * Load Each Engine
 */

namespace Test;

class Engine_Test extends \PHPUnit\Framework\TestCase
{
	function test_engine_ping()
	{
		$cfg_base = [
			'company' => '123456789',
			'username' => 'fdsafdsaf',
			'password' => 'fdsafdsafsda',
			'license' => '123123123',
			'license-key' => 'fdsafdsafsda',
			'program-key' => 'fdsajklrewcsd',
		];

		$cre_list = \OpenTHC\CRE\Adapter\Base::getEngineList();

		foreach ($cre_list as $cfg) {

			$cfg = array_merge($cfg_base, $cfg);

			$n = $cfg['class'];
			// $this->assertNotEmpty($n);

			if (!empty($n)) {
				echo "Class: $n\n";
				$cre = new $n($cfg);
			}

			// CRE::factory($cfg);
		}

	}

}
