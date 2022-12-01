<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Role;
use App\User;

class AddNewUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role_admin = Role::where('name', 'admin')->first();

        $usersData = [
            'Силвия Кацарова'       =>  'silvia.katsarova@ficosota.com',
        ];

        foreach($usersData as $name => $email) {
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->password = bcrypt('Password123');
            $user->save();
            $user->roles()->attach($role_admin);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
