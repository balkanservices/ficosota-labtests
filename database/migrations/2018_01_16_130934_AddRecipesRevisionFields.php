<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\LanguageLineHelper;

class AddRecipesRevisionFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recipes', function($table) {
            $table->integer('revision_number')->nullable();
            $table->date('revision_date')->nullable();
            $table->date('valid_from')->nullable();
            $table->date('valid_to')->nullable();
        });
        
        
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "revision_number", ["bg" => "Ревизия"]);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "revision_date", ["bg" => "Дата на ревизията"]);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "valid_from", ["bg" => "Валидна от"]);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "valid_to", ["bg" => "Валидна до"]);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "btn.save_and_create_new_revision", ["bg" => "Запазване и създаване на нова ревизия"]);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "status.could_not_create_new_revision", ["bg" => "Грешка при създаване на нова ревизия!"]);
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "header.revision", ["bg" => "Ревизия"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recipes', function($table) {
            $table->dropColumn('revision_number');
            $table->dropColumn('revision_date');
            $table->dropColumn('valid_from');
            $table->dropColumn('valid_to');
        });
    }
}
