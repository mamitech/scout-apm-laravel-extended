<?php

namespace Mamitech\ScoutApmLaravelExtended;

use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Mamitech\ScoutApmLaravelExtended\Middleware\AddCustomContext;
use Mamitech\ScoutApmLaravelExtended\Middleware\SampleRequest;

class ScoutApmLaravelExtendedServiceProvider extends ServiceProvider
{
    public function boot(Container $application): void
    {
        $kernel = $application->make(Kernel::class);
        $kernel->prependMiddleware(AddCustomContext::class);
        $kernel->prependMiddleware(SampleRequest::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/scout-apm-laravel-extended.php', 'scout-apm-laravel-extended'
        );
    }
}
