<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\LanguageLineHelper;

class AuditLogAdditional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        LanguageLineHelper::updateOrCreateLanguageLine("products", "audit.title", ["bg" => "История на промените"]);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "audit.title", ["bg" => "История на промените"]);
        LanguageLineHelper::updateOrCreateLanguageLine("qa_journals", "audit.title", ["bg" => "История на промените"]);
        LanguageLineHelper::updateOrCreateLanguageLine("samples_list", "audit.title", ["bg" => "История на промените"]);

        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "audit.general", ["bg" => "Основни"]);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "ingredient_option.recipe_ingredient_id", ["bg" => "Номер на рецептура"]);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "ingredient_option.id", ["bg" => "Номер на опция"]);

        LanguageLineHelper::updateOrCreateLanguageLine("samples_list", "back_to_package", ["bg" => "Обратно към пакета"]);
        LanguageLineHelper::updateOrCreateLanguageLine("samples_list", "back_to_analysis", ["bg" => "Обратно към анализа"]);
        LanguageLineHelper::updateOrCreateLanguageLine("samples_list", "diaper", ["bg" => "Пелена"]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
