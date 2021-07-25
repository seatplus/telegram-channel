<?php

namespace Seatplus\TelegramChannel\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Inertia\Inertia;
use Orchestra\Testbench\TestCase as Orchestra;
use Seatplus\Auth\AuthenticationServiceProvider;
use Seatplus\Auth\Models\Permissions\Permission;
use Seatplus\Auth\Models\User;
use Seatplus\Eveapi\EveapiServiceProvider;
use Seatplus\Notifications\NotificationsServiceProvider;
use Seatplus\TelegramChannel\TelegramChannelServiceProvider;
use Seatplus\TelegramChannel\Tests\Stubs\Kernel;
use Seatplus\Web\WebServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Seatplus\\TelegramChannel\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        //Setup Inertia Root View
        Inertia::setRootView('web::app');

        //Do not use the queue
        Queue::fake();

        // setup database
        $this->setupDatabase($this->app);

        /** @noinspection PhpFieldAssignmentTypeMismatchInspection */
        $this->test_user = Event::fakeFor(function () {
            return User::factory()->create();
        });

        $this->test_character = $this->test_user->characters->first();

        $this->app->instance('path.public', __DIR__ .'/../vendor/seatplus/web/src/public');


        Permission::findOrCreate('superuser');
    }

    protected function getPackageProviders($app)
    {
        return [
            TelegramChannelServiceProvider::class,
            NotificationsServiceProvider::class,
            AuthenticationServiceProvider::class,
            WebServiceProvider::class,
            EveapiServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        // Use memory SQLite, cleans it self up
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        config(['app.debug' => true]);

        //$app['router']->aliasMiddleware('auth', Authenticate::class);

        // Use test User model for users provider
        $app['config']->set('auth.providers.users.model', User::class);

        $app['config']->set('cache.prefix', 'seatplus_tests---');

        //Setup Inertia for package development
        /*config()->set('inertia.testing.page_paths', array_merge(
            config()->get('inertia.testing.page_paths', []),
            [
                realpath(__DIR__ . '/../src/resources/js/Pages'),
                realpath(__DIR__ . '/../src/resources/js/Shared')
            ],
        ));*/
    }

    /**
     * Resolve application HTTP Kernel implementation.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function resolveApplicationHttpKernel($app)
    {
        $app->singleton('Illuminate\Contracts\Http\Kernel', Kernel::class);
    }

    /**
     * @param \Illuminate\Foundation\Application  $app
     */
    private function setupDatabase($app)
    {
        // Path to our migrations to load
        //$this->loadMigrationsFrom(__DIR__ . '/database/migrations');
        $this->artisan('migrate', ['--database' => 'testbench']);
    }
}
