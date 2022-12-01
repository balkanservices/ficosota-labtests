<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRecipesEditedFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recipes', function($table) {
            $table->boolean('final_version')->default(false);
            $table->boolean('final_version_edited')->default(false);
            $table->timestamp('final_version_edited_at', 0)->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recipes', function($table) {
            $table->dropColumn('final_version');
            $table->dropColumn('final_version_edited');
            $table->dropColumn('final_version_edited_at');
        });
    }
}
