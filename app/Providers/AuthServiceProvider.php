<?php

namespace App\Providers;

use App\Models\Diagnosis;
use App\Models\LabOrder;
use App\Models\Patient;
use App\Models\Visit;
use App\Policies\DiagnosisPolicy;
use App\Policies\LabOrderPolicy;
use App\Policies\PatientPolicy;
use App\Policies\VisitPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Visit::class => VisitPolicy::class,
        Diagnosis::class => DiagnosisPolicy::class,
        LabOrder::class => LabOrderPolicy::class,
        Patient::class => PatientPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}