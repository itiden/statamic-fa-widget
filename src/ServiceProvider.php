<?php

namespace Itiden\FA;

use App\Http\Controllers\FAController;
use Illuminate\Support\Facades\Route;
use Statamic\Statamic;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        Statamic::pushCpRoutes(function () {
            Route::get('/fa', [FAController::class, 'index'])->name('fa');
        });
    }

    protected $vite = [
        'input' => [
            'resources/js/cp.js',
        ],
        'publicDirectory' => 'resources/dist',
    ];
}
