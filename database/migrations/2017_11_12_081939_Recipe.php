<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Recipe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->nullable();
            $table->string('name');
			$table->mediumText('comment')->nullable();
            $table->timestamps();
        });
		
		Schema::create('recipe_ingredients', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('recipe_id');
            $table->string('name');
            $table->timestamps();
        });
		
		Schema::create('recipe_ingredient_options', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('recipe_ingredient_id');
            $table->string('name')->nullable();
			$table->string('width')->nullable();
			$table->string('supplier')->nullable();
			$table->string('metric_unit')->nullable();
			$table->string('consumption_per_piece')->nullable();
			$table->mediumText('comment')->nullable();
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
		Schema::dropIfExists('recipe_ingredient_options');
		Schema::dropIfExists('recipe_ingredients');
        Schema::dropIfExists('recipes');
    }
}

