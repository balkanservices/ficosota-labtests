<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\LanguageLineHelper;
use Illuminate\Support\Facades\DB;
use App\RecipeIngredientOption;

class NewRecipesModifications extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", 'btn.active_recipes', ["bg" => 'Активни']);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", 'btn.not_active_recipes', ["bg" => 'Неактивни']);

        Schema::table('recipe_ingredient_options', function($table) {
            $table->string('type')->nullable();
            $table->string('priority')->nullable();
        });

        LanguageLineHelper::updateOrCreateLanguageLine("recipes", 'ingredient_options.type.main', ["bg" => 'Основен']);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", 'ingredient_options.type.alternative', ["bg" => 'Алтернативен']);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", 'ingredient_options.type.reserve', ["bg" => 'Резервен']);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", 'ingredient_options.type.header', ["bg" => 'Тип']);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", 'ingredient_options.priority', ["bg" => 'Приоритет']);

        RecipeIngredientOption::where('main_material', 1)
          ->update(['type' => RecipeIngredientOption::TYPE_MAIN]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recipe_ingredient_options', function($table) {
            $table->dropColumn('type');
            $table->dropColumn('priority');
        });
    }
}
