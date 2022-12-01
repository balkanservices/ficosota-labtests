<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConvertSampleListsDatesToYmdFormat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('UPDATE samples_lists '
                . 'SET '
                . 'rd_delivery_date = STR_TO_DATE(`rd_delivery_date`, \'%d/%m/%Y\'), '
                . 'buying_date = STR_TO_DATE(`buying_date`, \'%d/%m/%Y\'), '
                . 'analysis_end_date = STR_TO_DATE(`analysis_end_date`, \'%d/%m/%Y\'), '
                . 'manifacturing_date = STR_TO_DATE(`manifacturing_date`, \'%d/%m/%Y\')');


        DB::statement('ALTER TABLE `samples_lists` CHANGE COLUMN `rd_delivery_date` `rd_delivery_date` DATE DEFAULT NULL');
        DB::statement('ALTER TABLE `samples_lists` CHANGE COLUMN `buying_date` `buying_date` DATE DEFAULT NULL');
        DB::statement('ALTER TABLE `samples_lists` CHANGE COLUMN `analysis_end_date` `analysis_end_date` DATE DEFAULT NULL');
        DB::statement('ALTER TABLE `samples_lists` CHANGE COLUMN `manifacturing_date` `manifacturing_date` DATE DEFAULT NULL');

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
