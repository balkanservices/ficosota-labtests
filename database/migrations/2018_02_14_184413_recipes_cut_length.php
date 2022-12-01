<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\LanguageLineHelper;

class RecipesCutLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recipe_ingredient_options', function($table) {
            $table->string('cut_length')->nullable();
        });

        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "ingredient.cut_length", ["bg" => "Cut length"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recipe_ingredient_options', function($table) {
            $table->dropColumn('cut_length');
        });
    }
}
