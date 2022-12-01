<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\LanguageLineHelper;

class RenameQaJournalsToUsedMaterials extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        LanguageLineHelper::updateOrCreateLanguageLine("qa_journals", "title", ["bg" => "Вложени материали"]);
        LanguageLineHelper::updateOrCreateLanguageLine("qa_journals", "qa_journals", ["bg" => "Вложени материали"]);
        LanguageLineHelper::updateOrCreateLanguageLine("home", "qa_journals", ["bg" => "Вложени материали"]);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "btn.new_qa_journal", ["bg" => "Нови вложени материали"]);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "btn.save_and_create_qa_journal", ["bg" => "Запазване и създаване на вложени материали"]);
        LanguageLineHelper::updateOrCreateLanguageLine("samples_list", "header.qa_journal", ["bg" => "Вложени материали"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        LanguageLineHelper::updateOrCreateLanguageLine("qa_journals", "title", ["bg" => "ОТК журнал"]);
        LanguageLineHelper::updateOrCreateLanguageLine("qa_journals", "qa_journals", ["bg" => "ОТК журнал"]);
        LanguageLineHelper::updateOrCreateLanguageLine("home", "qa_journals", ["bg" => "ОТК журнал"]);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "btn.new_qa_journal", ["bg" => "Нов ОТК журнал"]);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "btn.save_and_create_qa_journal", ["bg" => "Запазване и създаване на ОТК журнал"]);
        LanguageLineHelper::updateOrCreateLanguageLine("samples_list", "header.qa_journal", ["bg" => "ОТК журнал"]);
    }
}
