<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\LanguageLineHelper;

class MakeAnalysesPerPackage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('samples_packages', function($table) {
            $table->text('comment');
        });
        
        LanguageLineHelper::updateOrCreateLanguageLine("samples_list", "package_analyses", ["bg" => "Анализи"]);
        LanguageLineHelper::updateOrCreateLanguageLine("samples_list", "action", ["bg" => "Действие"]);
        LanguageLineHelper::updateOrCreateLanguageLine("samples_list", "header.package", ["bg" => "Пакет"]);
        LanguageLineHelper::updateOrCreateLanguageLine("samples_list", "header.packages", ["bg" => "Пакети"]);
        LanguageLineHelper::updateOrCreateLanguageLine("samples_list", "back_to_package", ["bg" => "Обратно към пакет"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('samples_packages', function($table) {
            $table->dropColumn('comment');
        });
    }
}
