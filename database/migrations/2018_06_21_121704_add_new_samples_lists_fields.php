<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNewSamplesListsFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('samples_lists', function($table) {
            $table->string('elastic_elements')->nullable();
            $table->date('analysis_start_date')->nullable();
            $table->date('analysis_planned_end_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('samples_lists', function($table) {
            $table->dropColumn('elastic_elements');
            $table->dropColumn('analysis_start_date');
            $table->dropColumn('analysis_planned_end_date');
        });
    }
}
