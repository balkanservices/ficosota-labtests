<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSamplesListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('samples_lists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('qa_journal_id')->nullable();
            $table->string('rd_delivery_date')->nullable();
            $table->string('buying_date')->nullable();
            $table->string('analysis_end_date')->nullable();
            $table->string('urgent_analysis_samples')->nullable();
            $table->string('priority')->nullable();
            $table->string('manifacturing_date')->nullable();
            $table->string('batch')->nullable();
            $table->string('manifacturing_time')->nullable();
            $table->string('region')->nullable();
            $table->string('market')->nullable();
            $table->string('size')->nullable();
            $table->string('weight_range')->nullable();
            $table->string('concept')->nullable();
            $table->string('subconcept')->nullable();
            $table->string('concept2')->nullable();
            $table->string('samples_count')->nullable();
            $table->string('country_of_origin')->nullable();
            $table->string('manifacturer')->nullable();
            $table->string('product')->nullable();
            $table->string('pictograms')->nullable();
            $table->string('breathable_sheet')->nullable();
            $table->string('lotion_smell')->nullable();
            $table->string('lotion_composition')->nullable();
            $table->string('status')->nullable();
            $table->string('comment')->nullable();
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
        Schema::dropIfExists('samples_lists');
    }
}
