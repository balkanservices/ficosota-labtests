<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSamplesPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('samples_packages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('samples_list_id')->nullable();
            $table->string('manifacturing_time')->nullable();
            $table->integer('samples_count')->nullable();
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
        Schema::dropIfExists('samples_packages');
    }
}
