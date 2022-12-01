<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('access-backend', function ($user) {
			return $user->authorizeRoles(['admin', 'rd_specialist', 'head_global_bu_hdt', 'head_rd_qc']);
		});

        Gate::define('edit-products', function ($user) {
			return $user->authorizeRoles(['admin', 'rd_specialist', 'head_global_bu_hdt', 'head_rd_qc']);
		});

        Gate::define('edit-recipes', function ($user) {
			return $user->authorizeRoles(['admin', 'rd_specialist', 'head_global_bu_hdt', 'head_rd_qc']);
		});

        Gate::define('edit-qa_journals', function ($user) {
			return $user->authorizeRoles(['admin', 'rd_specialist', 'head_global_bu_hdt', 'head_rd_qc', 'supervisor', 'rd_lab_assistant']);
		});

        Gate::define('edit-samples_lists', function ($user) {
			return $user->authorizeRoles(['admin', 'rd_specialist', 'head_global_bu_hdt', 'head_rd_qc', 'supervisor', 'rd_lab_assistant']);
		});
    }
}
