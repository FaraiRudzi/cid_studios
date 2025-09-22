<?php

namespace App\Providers;

// Add these lines at the top
use App\Models\CaseModel;
use App\Policies\CasePolicy;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
     /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // THE FIX IS HERE: Add this line to the array.
        CaseModel::class => CasePolicy::class,
    ];
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
         $this->registerPolicies();
    }
}
