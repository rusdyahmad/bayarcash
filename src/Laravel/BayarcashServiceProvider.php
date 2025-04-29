<?php

declare(strict_types=1);

namespace Bayarcash\Laravel;

use Bayarcash\Bayarcash;
use Illuminate\Support\ServiceProvider;

class BayarcashServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../../config/bayarcash.php' => $this->app->basePath('config/bayarcash.php'),
            ], 'bayarcash-config');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/bayarcash.php', 'bayarcash'
        );

        $this->app->singleton('bayarcash', function ($app) {
            $config = $app['config']['bayarcash'];
            
            return new Bayarcash(
                pat: $config['pat'] ?? $app['config']->get('bayarcash.pat'),
                apiSecretKey: $config['api_secret_key'] ?? $app['config']->get('bayarcash.api_secret_key'),
                portalKey: $config['portal_key'] ?? $app['config']->get('bayarcash.portal_key'),
                options: [
                    'sandbox' => $config['sandbox'] ?? $app['config']->get('bayarcash.sandbox', false),
                    'api_version' => $config['api_version'] ?? $app['config']->get('bayarcash.api_version', 'v3'),
                    'debug' => $config['debug'] ?? $app['config']->get('bayarcash.debug', false),
                    'default_channel' => $config['default_channel'] ?? $app['config']->get('bayarcash.default_channel'),
                    'return_url' => $config['return_url'] ?? $app['config']->get('bayarcash.return_url'),
                    'callback_url' => $config['callback_url'] ?? $app['config']->get('bayarcash.callback_url'),
                ],
                logger: $app['log']
            );
        });
    }
}
