<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrateCrcAapValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $crcArr = [
            'sap_weight', 'package_weight', 'weight_after_draining',
            'weight_after_centrifuge', 'absorption_capacity', 'centrifuge_retention_capacity',
        ];

        foreach($crcArr as $crcElement) {
            DB::table('analysis_attributes')
                ->where('attribute', 'data__' . $crcElement)
                ->update(['attribute' => 'data_1__' . $crcElement]);
        }

        $aapArr = [
            'sap_weight', 'cylinder_weight', 'weight_mass',
            'dry_cell_weight', 'weight_after_absorption', 'absorption_against_pressure',
        ];

        foreach($aapArr as $aapElement) {
            DB::table('analysis_attributes')
                ->where('attribute', 'data__' . $aapElement)
                ->update(['attribute' => 'data_1__' . $aapElement]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
