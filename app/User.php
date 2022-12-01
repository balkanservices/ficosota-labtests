<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Role;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class User extends Authenticatable implements AuditableContract
{
    use Auditable;

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function roles() {
        return $this->belongsToMany(Role::class);
    }

    /**
     * @param string|array $roles
     */
    public function authorizeRoles($roles) {
        if (is_array($roles)) {
            return $this->hasAnyRole($roles) ||
                    abort(401, 'This action is unauthorized.');
        }

        return $this->hasRole($roles) ||
                abort(401, 'This action is unauthorized.');
    }

    /**
     * Check multiple roles
     * @param array $roles
     */
    public function hasAnyRole($roles) {
        return null !== $this->roles()->whereIn('name', $roles)->first();
    }

    /**
     * Check one role
     * @param string $role
     */
    public function hasRole($role) {
        return null !== $this->roles()->where('name', $role)->first();
    }

    public function getRolesDescriptionsStr() {
        $descriptions = [];
        foreach($this->roles as $role) {
            $descriptions[] = $role->description;
        }

        return implode(', ', $descriptions);
    }

    public static function getRdSpecialists() {
        return User::where('email', 'LIKE', '%ficosota.com')->orderBy('name', 'ASC')->get();
//        $rdSpecialistRole = Role::where('name', '=', 'rd_specialist')->first();
//        return $rdSpecialistRole->users()->get();
    }

    public static function getRdSpecialistsArr() {
        $rdSpecialistsArr = [];
        $rdSpecialists = self::getRdSpecialists();
        foreach($rdSpecialists as $rdSpecialist) {
            $rdSpecialistsArr[$rdSpecialist->id] = $rdSpecialist->name;
        }
        return $rdSpecialistsArr;
    }
}
