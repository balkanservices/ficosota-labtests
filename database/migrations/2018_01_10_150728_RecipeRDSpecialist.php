<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\LanguageLineHelper;

class RecipeRDSpecialist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recipes', function($table) {
            $table->integer('rd_specialist_id')->nullable();
        });
        
        LanguageLineHelper::updateOrCreateLanguageLine("recipes", "rd_specialist", ["bg" => "R&D Specialist"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recipes', function($table) {
            $table->dropColumn('rd_specialist_id');
        });
    }
}
