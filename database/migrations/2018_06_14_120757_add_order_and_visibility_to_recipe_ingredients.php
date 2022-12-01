<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrderAndVisibilityToRecipeIngredients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recipe_ingredients', function($table) {
            $table->boolean('is_visible')->default(false);
            $table->integer('order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recipe_ingredients', function($table) {
            $table->dropColumn('is_visible');
            $table->dropColumn('order');
        });
    }
}
