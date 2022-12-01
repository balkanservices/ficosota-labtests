<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Product extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('brand');
            $table->string('sub_brand')->nullable();
            $table->string('conception')->nullable();
            $table->string('size')->nullable();
            $table->string('kg_range')->nullable();
            $table->string('region')->nullable();
            $table->string('market')->nullable();
            $table->string('year')->nullable();
            $table->string('quarter')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->string('producer')->nullable();
            $table->string('date_of_production')->nullable();
            $table->string('batch')->nullable();
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
        Schema::dropIfExists('products');
    }
}

