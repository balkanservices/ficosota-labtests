<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\AnalysisDefinitionSeederHelper;

class CrcAppAdditionalLines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        AnalysisDefinitionSeederHelper::seedOrUpdate('centrifuge_retention_capacity');
        AnalysisDefinitionSeederHelper::seedOrUpdate('absorption_against_pressure');
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
