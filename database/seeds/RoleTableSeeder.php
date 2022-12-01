<?php

use Illuminate\Database\Seeder;
use App\Role;

class RoleTableSeeder extends Seeder {

    public function run() {
        $role_employee = new Role();
        $role_employee->name = 'user';
        $role_employee->description = 'Normal User';
        $role_employee->save();

        $role_manager = new Role();
        $role_manager->name = 'admin';
        $role_manager->description = 'Administrator';
        $role_manager->save();
    }

}
