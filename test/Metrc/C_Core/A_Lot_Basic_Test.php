<?php
/**
 * Test Lot
 */

namespace Test\Metrc\C_Core;

class A_Lot_Basic_Test extends \Test\OpenTHC_Metrc_Test
{

	/**
	 * Lot Create / "Package Create"
	 */
	public function testLotPackageCreate()
	{
		$package = [
			[
				'Tag' => 'ABCDEF012345670000016511',
				'Room' => null,
				'Item' => 'Buds',
				'Quantity' => 246+666,
				'UnitOfMeasure' => 'Grams',
				// 'PatientLicenseNumber' => 'X00001',
				// 'Note' => 'This is a note.',
				// 'IsProductionBatch' => false,
				// 'ProductionBatchNumber' => null,
				// 'ProductRequiresRemediation' => false,
				'ActualDate' => date('Y-m-d'),
				'Ingredients' => [
					[
						'Package' => '1A4FFFB303D7E32000000084',
						'Quantity' => 246.0,
						'UnitOfMeasure' => 'Grams'
					],
					[
						'Package' => '1A4FFFB303D7E32000000087',
						'Quantity' => 666.0,
						'UnitOfMeasure' => 'Grams'
					]
				]
			],
		];
		$L = $this->cre->lot()->create($package);
		// print_r($res);

		$this->assertEqual($package[0]['Tag'], $L['meta']['Label']);
		$this->assertEqual($package[0]['Quantity'], $L['meta']['Quantity']);
		$this->assertEqual($package[0]['UnitOfMeasure'], $L['meta']['UnitOfMeasureName']);

	}

	/**
	 * Lot Modify / Package Change
	 */
	public function testLotPackageChange()
	{
		$lot_guid = 'ABCDEF012345670000016511';

		$res = $this->cre->lot()->update($lot_guid, [[
			'Label' => $lot_guid,
			'Item' => 'Product 78b705e8 - Bravo',
		]]);
		// print_r($res);
		$this->assertEqual($lot_guid, $res[0]['Label']);
	}

	/**
	 * Lot Adjust / Package Adjust
	 */
	public function testLotPackageAdjust()
	{
		$lot_guid = '1A4FFFB303D7E32000000089';
		$qty = -666;
		$L0 = $this->cre->lot($lot_guid);

		$res = $this->cre->lot()->adjust([[
			'Label' => $lot_guid,
			'Quantity' => $qty0,
			'UnitOfMeasure' => 'Grams',
			'AdjustmentReason' => 'Proficiency Testing',
			'AdjustmentDate' => date('Y-m-d'),
			'ReasonNote' => 'Adjust a package using: POST /packages/v1/adjust'
		]]);
		// print_r($res);
		$adjust_list = $this->cre->adjustList();
		$adjustment = $adjust_list[0]; // Find the record we made somehow
		$L1 = $this->cre->lot($lot_guid);

		$this->assertEqual($res[0]['Id'], $adjustment['Id']);
		$this->assertEqual($lot_guid, $adjustment['Label']);
		$this->assertEqual($L0['qty'] + $qty, $L1['qty']);
	}

	/**
	 * Lot Finish / Package Finish
	 */
	public function testLotFinish()
	{
		$lot_guid = '1A4FFFB303D7E32000000093';

		// Lot Finish
		$res = $this->cre->lot()->modify($lot_guid, [[
			'Label' => $lot_guid,
			'ActualDate' => date('Y-m-d'),
		]]);
		print_r($res);

		$L1 = $this->cre->lot($lot_guid);
		$this->assertEqual(date('Y-m-d', $L1['meta']['FinishedDate']));
		$this->assertEqual($lot_guid, $res[0]['Tag']);
	}

	/**
	 * Lot Finish Undo
	 */
	public function testLotFinishUndo()
	{
		$lot_guid = '1A4FFFB303D7E32000000093';

		$res = $this->cre->lot()->modify($lot_guid, [[
			'Label' => $lot_guid
		]]);
		// print_r($res);
		$L1 = $this->cre->lot($lot_guid);

		$this->assertEmpty($L1['meta']['FinishedDate']);
	}
}
