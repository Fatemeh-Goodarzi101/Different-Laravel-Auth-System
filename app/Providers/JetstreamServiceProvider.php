<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Jetstream::ignoreRoutes();
        $this->registerJetStreamRoutes();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePermissions();

        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::permissions([
            'create',
            'read',
            'update',
            'delete',
        ]);
    }

    private function registerJetStreamRoutes()
    {
        Route::group([
            'namespace' => 'Laravel\Jetstream\Http\Controllers',
            'domain' => config('jetstream.domain', null),
            'prefix' => config('jetstream.prefix', config('jetstream.path')),
        ], function () {
            $this->loadRoutesFrom(base_path('routes/jetstream.php'));
        });
    }
}
