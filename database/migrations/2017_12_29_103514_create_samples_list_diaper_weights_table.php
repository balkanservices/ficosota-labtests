<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSamplesListDiaperWeightsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('samples_list_diaper_weights', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('samples_list_id');
            $table->string('average');
            $table->string('min');
            $table->string('max');
            $table->string('standard_deviation');
            $table->string('delta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('samples_list_diaper_weights');
    }
}
