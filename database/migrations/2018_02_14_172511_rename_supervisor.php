<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RenameSupervisor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('roles')
            ->where('description', 'Supevisor')
            ->update(['description' => 'R&D Labs Supervisor']);

        DB::table('roles')
            ->where('description', 'R&D specialist')
            ->update(['description' => 'R&D Specialist']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('roles')
            ->where('description', 'R&D Labs Supervisor')
            ->update(['description' => 'Supevisor']);

        DB::table('roles')
            ->where('description', 'R&D Specialist')
            ->update(['description' => 'R&D specialist']);
    }
}
