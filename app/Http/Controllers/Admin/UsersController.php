<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Role;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		$users = User::orderBy('id', 'desc')->paginate(self::PAGE_SIZE);

        return view('users/index', [
			'users' => $users,
		]);
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function edit($locale, $id)
    {
		$user = User::findOrFail($id);

        $excludeRoles = ['admin'];
        if(Auth::user()->hasRole('admin')) {
            $excludeRoles = [];
        }

        return view('users/edit', [
			'user' => $user,
            'roles' => Role::getRolesArr($excludeRoles),
		]);
    }


    public function process(UserRequest $request, $locale, $id)
    {
        $user = User::findOrFail($id);

        $role = Role::where('name', '=', $request->role)->first();

        if(!empty($role)) {
            if(Auth::user()->hasRole('admin') || !$user->hasRole('admin')) {
                $user->roles()->detach();
                $user->roles()->attach($role);
            }
        }

        return redirect()->route('users.index');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function audit($locale, $id)
    {
		$user = User::findOrFail($id);

        $auditLog = $user->audits()->with('user')->orderBy('id', 'DESC')->get();

        return view('users/audit', [
			'user' => $user,
            'auditLog' => $auditLog,
		]);
    }
}
