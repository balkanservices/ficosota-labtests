<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QaJournal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qa_journals', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('recipe_id');
			$table->string('batch_number')->nullable();
            $table->timestamps();
        });
		
		Schema::create('qa_journal_ingredients', function (Blueprint $table) {
            $table->increments('id');
			$table->integer('qa_journal_id');
			$table->integer('ingredient_id');
			$table->integer('option_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::dropIfExists('qa_journal_ingredients');
        Schema::dropIfExists('qa_journals');
    }
}
