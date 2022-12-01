<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\LanguageLineHelper;

class RecipesElasticsCountAndElongation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recipe_ingredient_options', function($table) {
            $table->string('elastics_count')->nullable();
            $table->string('elongation')->nullable();
        });

        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "ingredient.elastics_count", ["bg" => "Elastics count"]);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "ingredient.elongation", ["bg" => "Elongation, %"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recipe_ingredient_options', function($table) {
            $table->dropColumn('elastics_count');
            $table->dropColumn('elongation');
        });
    }
}
