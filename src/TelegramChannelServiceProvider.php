<?php

namespace Seatplus\TelegramChannel;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\SocialiteManager;
use Seatplus\TelegramChannel\Jobs\DispatchTelegramNotifications;
use Seatplus\TelegramChannel\Services\Telegram;
use SocialiteProviders\Manager\SocialiteWasCalled;
use SocialiteProviders\Telegram\Provider;
use SocialiteProviders\Telegram\TelegramExtendSocialite;

class TelegramChannelServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function boot()
    {
        // Publish the JS & CSS,
        $this->addPublications();

        // Add routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/telegram.php');

        //Add Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations/');

        // Add event listeners
        $this->addEventListeners();

        // Add rate limited
        RateLimiter::for('telegram', fn () => Limit::perMinute(30));

        // Add notification schedule
        $this->addTelegramSchedule();

        // Add translations
        //$this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'web');

        $this->app->singleton(Telegram::class, function ($app) {
            return new Telegram(
                config('services.telegram-bot-api.token'),
                app(HttpClient::class),
                config('services.telegram-bot-api.base_uri')
            );
        });
    }

    public function register()
    {
        $this->mergeConfigurations();

        // Register the Socialite Factory.
        // From: Laravel\Socialite\SocialiteServiceProvider
        if (! $this->app->bound('Laravel\Socialite\Contracts\Factory')) {
            $this->app->singleton('Laravel\Socialite\Contracts\Factory', function ($app) {
                return new SocialiteManager($app);
            });
        }

        // Slap in the Telegram Socialite Provider
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');

        $socialite->extend(
            'telegram',
            function ($app) use ($socialite) {
                $config = $app['config']['services.telegram'];

                return $socialite->buildProvider(Provider::class, $config);
            }
        );
    }

    private function mergeConfigurations()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/notification.channel.php',
            'notification.channels'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../config/services.php',
            'services'
        );
    }

    private function addPublications()
    {
        /*
         * to publish assets one can run:
         * php artisan vendor:publish --tag=web --force
         * or use Laravel Mix to copy the folder to public repo of core.
         */
        $this->publishes([
            __DIR__ . '/../resources/js' => resource_path('js'),
        ], 'web');
    }

    private function addEventListeners()
    {
        $this->app->events->listen(SocialiteWasCalled::class, TelegramExtendSocialite::class);
    }

    private function addTelegramSchedule()
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->job(DispatchTelegramNotifications::class, 'medium')->everyFiveMinutes();
        });
    }
}
