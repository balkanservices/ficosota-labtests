<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDeleteFunctionality extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function($table) {
            $table->tinyInteger('deleted')->default(0);
        });
        Schema::table('recipes', function($table) {
            $table->tinyInteger('deleted')->default(0);
        });
        Schema::table('qa_journals', function($table) {
            $table->tinyInteger('deleted')->default(0);
        });
        Schema::table('samples_lists', function($table) {
            $table->tinyInteger('deleted')->default(0);
        });
        Schema::table('samples', function($table) {
            $table->tinyInteger('deleted')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function($table) {
            $table->dropColumn('deleted');
        });
        Schema::table('recipes', function($table) {
            $table->dropColumn('deleted');
        });
        Schema::table('qa_journals', function($table) {
            $table->dropColumn('deleted');
        });
        Schema::table('samples_lists', function($table) {
            $table->dropColumn('deleted');
        });
        Schema::table('samples', function($table) {
            $table->dropColumn('deleted');
        });
    }
}