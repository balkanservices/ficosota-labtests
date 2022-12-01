<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\AnalysisDefinitionSeederHelper;

class AddAnalysesNewFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        AnalysisDefinitionSeederHelper::seedOrUpdate('absorption_against_pressure');
        AnalysisDefinitionSeederHelper::seedOrUpdate('superabsorbent_quantity');
        AnalysisDefinitionSeederHelper::seedOrUpdate('absorbtion_before_leakage');
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
