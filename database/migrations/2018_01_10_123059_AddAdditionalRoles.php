<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\LanguageLineHelper;
use App\Role;
use App\User;

class AddAdditionalRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        LanguageLineHelper::updateOrCreateLanguageLine("home", "users", ["bg" => "Потребители"]);
        
        $newRoles = [
            'rd_specialist' => 'R&D specialist',
            'supervisor' => 'Supevisor'
        ];
        
        foreach($newRoles as $newRole => $newRoleDescription) {
            $role = new Role();
            $role->name = $newRole;
            $role->description = $newRoleDescription;
            $role->save();
        }
        
        $roleUser = Role::where('name', '=', 'user')->first();
        $roleUser->description = 'User';
        $roleUser->save();
        
        $roleSupervisor = Role::where('name', '=', 'supervisor')->first();
        
        $userSupervisor = User::where('email', '=', 'jenya.stoyanova@ficosota.com')->first();
        $userSupervisor->roles()->detach();
        $userSupervisor->roles()->attach($roleSupervisor);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $roleUser = Role::where('name', '=', 'user')->first();
        $roleUser->description = 'Normal User';
        $roleUser->save();
        
        $newRoles = [
            'rd_specialist' => 'R&D specialist',
            'supervisor' => 'Supevisor'
        ];
        
        foreach($newRoles as $newRole => $newRoleDescription) {
            $role = Role::where('name', '=', $newRole)->first();
            
            if($role) {
                $users = $role->users()->get();
                foreach($users as $user) {
                    $user->roles()->detach();
                    $user->roles()->attach($roleUser);
                }
            
                $role->delete();
            }
        }
    }
}
