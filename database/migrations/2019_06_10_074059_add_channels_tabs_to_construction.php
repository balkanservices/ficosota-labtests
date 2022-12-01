<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\AnalysisDefinitionSeederHelper;

class AddChannelsTabsToConstruction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('construction', function (Blueprint $table) {
            AnalysisDefinitionSeederHelper::seedOrUpdate('construction');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('construction', function (Blueprint $table) {
            //
        });
    }
}
