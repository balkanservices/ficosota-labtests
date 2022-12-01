<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeOldMigrationName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('migrations')
            ->where('migration', '2018_07_19_080310_add_new_user')
            ->update(['migration' => '2018_07_19_080310_add_new_user2']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('migrations')
            ->where('migration', '2018_07_19_080310_add_new_user2')
            ->update(['migration' => '2018_07_19_080310_add_new_user']);
    }
}
