<?php

use Illuminate\Database\Seeder;
use App\Helpers\AnalysisDefinitionSeederHelper;

class AnalysisDefinitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $definitions = [
            'wetting_value',
            'wetting_value_under_pressure',
            'creep_resistance_1h_40_degrees',
            'creep_resistance_4h_40_degrees',
            'total_absorption_capacity',
            'superabsorbent_quantity',
            'superabsorbent_quantity_zones',
            'centrifuge_retention_capacity',
            'absorption_against_pressure',
        ];

        foreach($definitions as $definitionFile) {
            AnalysisDefinitionSeederHelper::seedOrUpdate($definitionFile);
		}
    }
}
