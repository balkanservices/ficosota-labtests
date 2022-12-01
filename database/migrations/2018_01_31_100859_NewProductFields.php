<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\LanguageLineHelper;

class NewProductFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function($table) {
            $table->integer('excel_sample_number')->nullable();
            $table->string('excel_sheet')->nullable();
            $table->string('date_bought')->nullable();
            $table->date('date_supplied_to_rd_center')->nullable();
            $table->integer('diaper_count_in_package')->nullable();
            $table->text('pictograms')->nullable();
            $table->string('product')->nullable();
            $table->string('breathable_sheet')->nullable();
            $table->string('status')->nullable();
            $table->text('comment')->nullable();
            $table->string('sub_conception')->nullable();
            $table->string('conception2')->nullable();
            $table->string('date_finished_sample_analyses')->nullable();
            $table->string('samples_for_urgent_analyses')->nullable();
            $table->string('samples_priorities')->nullable();
            $table->string('time_of_production')->nullable();
            $table->string('lotion_smell')->nullable();
            $table->string('lotion_package_ingredients')->nullable();
        });
        
        
        LanguageLineHelper::updateOrCreateLanguageLine("products", "excel_sample_number", ["bg" => "№ на мострата (Excel)"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "excel_sheet", ["bg" => "Excel sheet"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "date_bought", ["bg" => "Дата на закупуване"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "date_supplied_to_rd_center", ["bg" => "Дата на предоставяне в R&D Centre"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "diaper_count_in_package", ["bg" => "Брой пелени в пакет"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "pictograms", ["bg" => "Пиктограми"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "product", ["bg" => "Продукт"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "breathable_sheet", ["bg" => "Breathable sheet (TS/BS)"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "status", ["bg" => "Статус"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "comment", ["bg" => "Коментар"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "sub_conception", ["bg" => "Подконцепция"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "conception2", ["bg" => "Концепция2"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "date_finished_sample_analyses", ["bg" => "Дата на завършване на мострените анализи"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "samples_for_urgent_analyses", ["bg" => "Мостри за спешни анализи"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "samples_priorities", ["bg" => "Приоритет на предоставените мостри"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "time_of_production", ["bg" => "Час на производство"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "lotion_smell", ["bg" => "Лосион в пелената-мирис"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "lotion_package_ingredients", ["bg" => "Състав на лосиона от опаковката"]);
        
        LanguageLineHelper::updateOrCreateLanguageLine("products", "imported", ["bg" => "Нови запазени продукти:"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "btn.file_upload", ["bg" => "Качване на XLS 'Дневник мостри'"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "file_upload", ["bg" => "Качване на XLS 'Дневник мостри'"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "select_file_to_upload", ["bg" => "Изберете файл за качване"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "btn.upload", ["bg" => "Качване"]);
        
        LanguageLineHelper::updateOrCreateLanguageLine("products", "header.name", ["bg" => "Име"]);
        LanguageLineHelper::updateOrCreateLanguageLine("products", "header.excel_sheet", ["bg" => "Excel sheet"]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function($table) {
            $table->dropColumn('excel_sample_number');
            $table->dropColumn('excel_sheet');
            $table->dropColumn('date_bought');
            $table->dropColumn('date_supplied_to_rd_center');
            $table->dropColumn('diaper_count_in_package');
            $table->dropColumn('pictograms');
            $table->dropColumn('product');
            $table->dropColumn('breathable_sheet');
            $table->dropColumn('status');
            $table->dropColumn('comment');
            $table->dropColumn('sub_conception');
            $table->dropColumn('conception2');
            $table->dropColumn('date_finished_sample_analyses');
            $table->dropColumn('samples_for_urgent_analyses');
            $table->dropColumn('samples_priorities');
            $table->dropColumn('time_of_production');
            $table->dropColumn('lotion_smell');
            $table->dropColumn('lotion_package_ingredients');
        });
    }
}
