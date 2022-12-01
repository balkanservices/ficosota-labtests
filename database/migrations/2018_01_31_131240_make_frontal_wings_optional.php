<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\LanguageLineHelper;

class MakeFrontalWingsOptional extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qa_journal_ingredients', function($table) {
            $table->integer('option_id')->nullable()->change();
        });
        
        LanguageLineHelper::updateOrCreateLanguageLine("qa_journals", "all_options_except_frontal_wings_need_to_be_selected", ["bg" => "Всички опции освен \"Frontal wings\" трябва да са избрани!"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qa_journal_ingredients', function($table) {
            $table->integer('option_id')->change();
        });
    }
}
