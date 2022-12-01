<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Role;

class AddMoreRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $newRoles = [
            'rd_lab_assistant' => 'R&D Lab Assistant',
            'head_rd_qc' => 'Head of R&D and QC',
            'head_global_bu_hdt' => 'Head of Global Business Unit HDT'
        ];
        
        foreach($newRoles as $newRole => $newRoleDescription) {
            $role = new Role();
            $role->name = $newRole;
            $role->description = $newRoleDescription;
            $role->save();
        }
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $roleUser = Role::where('name', '=', 'user')->first();
        
        $newRoles = [
            'rd_lab_assistant' => 'R&D Lab Assistant',
            'head_rd_qc' => 'Head of R&D and QC',
            'head_global_bu_hdt' => 'Head of Global Business Unit HDT'
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
