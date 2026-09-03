<?php

namespace App\Providers;

use App\Models\CaseModel;
use App\Observers\CaseObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        CaseModel::observe(CaseObserver::class);
    }
}
