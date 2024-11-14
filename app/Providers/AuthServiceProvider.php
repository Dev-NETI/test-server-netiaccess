<?php

namespace App\Providers;

use App\Policies\AdminComponentPolicy;
use App\Policies\AuthorizeEnrollmentPolicy;
use App\Policies\BillingModulePolicy;
use App\Policies\CoursePolicy;
use App\Policies\EnrollmentPolicy;
use Illuminate\Auth\Access\Gate as AccessGate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    protected $providers = [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\Models\tbltraineeaccount::class,
        ],
    ];



    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('authorizeAdminComponents', [AdminComponentPolicy::class, 'authorizeComponent']);
        Gate::define('authorizeHandout', [AdminComponentPolicy::class, 'authorizeHandout']);
        Gate::define('authorizeDelete', [AdminComponentPolicy::class, 'authorizeDelete']);

        // Gate::define('authorizeBillingModule', [AdminComponentPolicy::class, 'restrictBillingModule']);
        Gate::define('authorizeRegistrarEnrollment', [EnrollmentPolicy::class, 'RegistrarEnrollment']);
        Gate::define('authorizeCompanyEnrollment', [EnrollmentPolicy::class, 'CompanyEnrollment']);

        // BILLING GATES
        Gate::define('authorizeBillingAccess', [BillingModulePolicy::class, 'authorizeBillingAccess']);
        Gate::define('authorizeSendBackBilling', [BillingModulePolicy::class, 'authorizeSendBackBilling']);
        Gate::define('EditVesselAuthorization', [BillingModulePolicy::class, 'EditVesselAuthorization']);
        // PDE 
        Gate::define('authorizeRequestPdeComponent', [AdminComponentPolicy::class, 'authorizeRequestPdeComponent']);
        //EDIT COURSE
        Gate::define('AuthorizeEditCourse', [CoursePolicy::class, 'AuthorizeEditCourse']);
    }
}
