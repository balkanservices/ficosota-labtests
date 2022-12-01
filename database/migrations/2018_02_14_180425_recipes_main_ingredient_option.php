<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\LanguageLineHelper;

class RecipesMainIngredientOption extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recipe_ingredient_options', function($table) {
            $table->boolean('main_material')->default(false);
        });

        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "ingredient.main_material", ["bg" => "Основен материал"]);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "btn.mark_as_main_material", ["bg" => "Маркирай като основна"]);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "ingredient.marked_as_main_material", ["bg" => "Маркиран като основен"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recipe_ingredient_options', function($table) {
            $table->dropColumn('main_material');
        });
    }
}
