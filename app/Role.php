<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class Role extends Model implements AuditableContract
{
    use Auditable;

    public function users() {
        return $this->belongsToMany(User::class);
    }

    public static function getRolesArr($excludeRoles = []) {
        $rolesArr = [];

        $roles = self::all();

        foreach($roles as $role) {
            if(in_array($role->name, $excludeRoles)) {
                continue;
            }
            $rolesArr[$role->name] = $role->description;
        }

        return $rolesArr;
    }
}
