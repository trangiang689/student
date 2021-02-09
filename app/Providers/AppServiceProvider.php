<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //

        $this->app->bind(
            'App\Repositories\Faculties\FacultyRepositoryInterface',
            'App\Repositories\Faculties\FacultyRepository'
        );

        $this->app->bind(
            'App\Repositories\Classes\ClassRepositoryInterface',
            'App\Repositories\Classes\ClassRepository'
        );

        $this->app->bind(
            'App\Repositories\Subjects\SubjectRepositoryInterface',
            'App\Repositories\Subjects\SubjectRepository'
        );

        $this->app->bind(
            'App\Repositories\Students\StudentRepositoryInterface',
            'App\Repositories\Students\StudentRepository'
        );

        $this->app->bind(
            'App\Repositories\Users\UserRepositoryInterface',
            'App\Repositories\Users\UserRepository'
        );

        $this->app->bind(
            'App\Repositories\Admins\AdminRepositoryInterface',
            'App\Repositories\Admins\AdminRepository'
        );

        $this->app->bind(
            'App\Repositories\Roles\RoleRepositoryInterface',
            'App\Repositories\Roles\RoleRepository'
        );

        $this->app->bind(
            'App\Repositories\Permissions\PermissionRepositoryInterface',
            'App\Repositories\Permissions\PermissionRepository'
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
