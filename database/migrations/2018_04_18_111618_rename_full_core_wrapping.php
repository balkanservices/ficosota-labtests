<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class RenameFullCoreWrapping extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('recipe_ingredients')
            ->where('name', 'Или цял Core Wrapping')
            ->update(['name' => 'Full Core Wrapping']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('recipe_ingredients')
            ->where('name', 'Или цял Core Wrapping')
            ->update(['name' => 'Full Core Wrapping']);
    }
}
