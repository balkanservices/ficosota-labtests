<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\LanguageLineHelper;

class AddDirectProductLinkToSamplesList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('samples_lists', function($table) {
            $table->integer('product_id')->nullable();
        });

        LanguageLineHelper::updateOrCreateLanguageLine("samples_list", 'or', ["bg" => 'или']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('samples_lists', function($table) {
            $table->dropColumn('product_id');
        });
    }
}
