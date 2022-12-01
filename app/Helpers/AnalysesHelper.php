<?php

namespace App\Helpers;

class AnalysesHelper
{
    public static function getAnalyses() {
		return [
			[
				'id'	=>	'wetting_value',
			],
			[
				'id'	=>	'wetting_value_under_pressure',
			],
			[
				'id'	=>	'sap',
			],
		];
	}
}
