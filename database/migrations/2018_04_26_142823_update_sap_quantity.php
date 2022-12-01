<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\AnalysisDefinitionSeederHelper;

class UpdateSapQuantity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        AnalysisDefinitionSeederHelper::seedOrUpdate('superabsorbent_quantity');
        AnalysisDefinitionSeederHelper::seedOrUpdate('absorption_against_pressure');
        AnalysisDefinitionSeederHelper::seedOrUpdate('centrifuge_retention_capacity');
        AnalysisDefinitionSeederHelper::seedOrUpdate('total_absorption_capacity');
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
