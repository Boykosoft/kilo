<?php

namespace App\Providers;

use App\Http\Controllers\PaymentProviders\AppleController;
use App\Services\Apple\AppleWebhookExecutor;
use App\Services\PaymentProviders\WebhookExecutorInterface;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class AppleWebhookExecutorServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(AppleController::class)
            ->needs(WebhookExecutorInterface::class)
            ->give(AppleWebhookExecutor::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [WebhookExecutorInterface::class];
    }
}
