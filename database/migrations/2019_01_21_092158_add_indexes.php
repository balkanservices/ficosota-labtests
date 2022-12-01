<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('analysis_attributes', function (Blueprint $table) {
            $table->index(['analysis_id', 'attribute']);
        });
        
        Schema::table('samples', function (Blueprint $table) {
            $table->index(['samples_package_id']);
        });
        
        Schema::table('samples_packages', function (Blueprint $table) {
            $table->index(['samples_list_id']);
        });
        
        Schema::table('samples_package_diaper_weights', function (Blueprint $table) {
            $table->index(['samples_package_id']);
        });
        
        Schema::table('analysis', function (Blueprint $table) {
            $table->index(['sample_id', 'enabled']);
            $table->index(['analysis_definition_id']);
        });
        
        Schema::table('analysis_definitions', function (Blueprint $table) {
            $table->index(['slug']);
        });
        
        Schema::table('analysis_limits', function (Blueprint $table) {
            $table->index(['type', 'analysis_slug']);
        });
        
        Schema::table('qa_journal_ingredients', function (Blueprint $table) {
            $table->index(['qa_journal_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('analysis_attributes', function (Blueprint $table) {
            $table->dropIndex(['analysis_id', 'attribute']);
        });
        
        Schema::table('samples', function (Blueprint $table) {
            $table->dropIndex(['samples_package_id']);
        });
        
        Schema::table('samples_packages', function (Blueprint $table) {
            $table->dropIndex(['samples_list_id']);
        });
        
        Schema::table('samples_package_diaper_weights', function (Blueprint $table) {
            $table->dropIndex(['samples_package_id']);
        });
        
        Schema::table('analysis', function (Blueprint $table) {
            $table->dropIndex(['sample_id', 'enabled']);
            $table->dropIndex(['analysis_definition_id']);
        });
        
        Schema::table('analysis_definitions', function (Blueprint $table) {
            $table->dropIndex(['slug']);
        });
        
        Schema::table('analysis_limits', function (Blueprint $table) {
            $table->dropIndex(['type', 'analysis_slug']);
        });
        
        Schema::table('qa_journal_ingredients', function (Blueprint $table) {
            $table->dropIndex(['qa_journal_id']);
        });
    }
}
