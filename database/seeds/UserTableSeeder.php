<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = Role::where('name', 'admin')->first();
		$role_user  = Role::where('name', 'user')->first();

        $admin = new User();
		$admin->name = 'Dobromir Mateev';
		$admin->email = 'dobromir.mateev@balkanservices.com';
		$admin->password = bcrypt('imerlEWR34d');
		$admin->save();
		$admin->roles()->attach($role_admin);

        $usersData = [
            'Женя Стоянова'         =>  'jenya.stoyanova@ficosota.com',
            'Диляна Рачева'         =>  'dilyana.racheva@ficosota.com',
            'Елена Тодорова'        =>  'elena.todorova@ficosota.com',
            'Емона Александрова'    =>  'emona.alexandrova@ficosota.com',
            'Ана Цветкова'          =>  'ana.tsvetkova@ficosota.com',
            'Иванита Иванова'       =>  'ivanita.ivanova@ficosota.com',
            'Константин Атанасов'   =>  'konstantin.atanasov@ficosota.com',

            'Симона Георгиева'       =>  'simona.georgieva@ficosota.com',
            'Михаела Славова'       =>  'mihaela.slavova@ficosota.com',
        ];

        foreach($usersData as $name => $email) {
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->password = bcrypt('Password123');
            $user->save();
            $user->roles()->attach($role_user);
        }
    }
}
