<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\LanguageLineHelper;

class NewQaJournalOprionsFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('qa_journal_ingredients', function($table) {
            $table->string('option_batch_number')->nullable();
            $table->string('option_fs_batch_number')->nullable();
        });

        LanguageLineHelper::updateOrCreateLanguageLine("qa_journals", 'option_batch_number', ["bg" => 'П №']);
        LanguageLineHelper::updateOrCreateLanguageLine("qa_journals", 'option_fs_batch_number', ["bg" => 'Lot №']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('qa_journal_ingredients', function($table) {
            $table->dropColumn('option_batch_number');
            $table->dropColumn('option_fs_batch_number');
        });
    }
}
