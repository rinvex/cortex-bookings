<?php

declare(strict_types=1);

namespace Cortex\Bookings\Providers;

use Illuminate\Routing\Router;
use Cortex\Bookings\Models\Rate;
use Cortex\Bookings\Models\Room;
use Cortex\Bookings\Models\Addon;
use Cortex\Bookings\Models\Event;
use Cortex\Bookings\Models\Booking;
use Illuminate\Support\ServiceProvider;
use Cortex\Bookings\Models\Availability;
use Cortex\Bookings\Console\Commands\SeedCommand;
use Cortex\Bookings\Console\Commands\InstallCommand;
use Cortex\Bookings\Console\Commands\MigrateCommand;
use Cortex\Bookings\Console\Commands\PublishCommand;
use Illuminate\Database\Eloquent\Relations\Relation;
use Cortex\Bookings\Console\Commands\RollbackCommand;

class BookingsServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        SeedCommand::class => 'command.cortex.bookings.seed',
        InstallCommand::class => 'command.cortex.bookings.install',
        MigrateCommand::class => 'command.cortex.bookings.migrate',
        PublishCommand::class => 'command.cortex.bookings.publish',
        RollbackCommand::class => 'command.cortex.bookings.rollback',
    ];

    /**
     * Register any application services.
     *
     * This service provider is a great spot to register your various container
     * bindings with the application. As you can see, we are registering our
     * "Registrar" implementation here. You can add your own bindings too!
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'cortex.bookings');

        // Bind eloquent models to IoC container
        $this->app['config']['rinvex.bookings.models.addon'] === Addon::class
        || $this->app->alias('rinvex.bookings.addon', Addon::class);

        $this->app['config']['rinvex.bookings.models.availability'] === Availability::class
        || $this->app->alias('rinvex.bookings.availability', Availability::class);

        $this->app['config']['rinvex.bookings.models.booking'] === Booking::class
        || $this->app->alias('rinvex.bookings.booking', Booking::class);

        $this->app['config']['rinvex.bookings.models.rate'] === Rate::class
        || $this->app->alias('rinvex.bookings.rate', Rate::class);

        // Register console commands
        ! $this->app->runningInConsole() || $this->registerCommands();

        // Bind eloquent models to IoC container
        $this->app->singleton('cortex.bookings.room', $roomModel = $this->app['config']['cortex.bookings.models.room']);
        $roomModel === Room::class || $this->app->alias('cortex.bookings.room', Room::class);

        $this->app->singleton('cortex.bookings.event', $eventModel = $this->app['config']['cortex.bookings.models.event']);
        $eventModel === Event::class || $this->app->alias('cortex.bookings.event', Event::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router): void
    {
        // Bind route models and constrains
        $router->pattern('booking', '[a-zA-Z0-9]+');
        $router->pattern('room', '[a-zA-Z0-9]+');
        $router->pattern('event', '[a-zA-Z0-9]+');
        $router->model('room', config('cortex.bookings.models.room'));
        $router->model('event', config('cortex.bookings.models.event'));
        $router->model('booking', config('rinvex.bookings.models.booking'));

        // Map relations
        Relation::morphMap([
            'room' => config('cortex.bookings.models.room'),
            'event' => config('cortex.bookings.models.event'),
            'booking' => config('rinvex.bookings.models.booking'),
        ]);

        // Load resources
        require __DIR__.'/../../routes/breadcrumbs/adminarea.php';
        $this->loadRoutesFrom(__DIR__.'/../../routes/web/adminarea.php');
        $this->loadRoutesFrom(__DIR__.'/../../routes/web/managerarea.php');
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'cortex/bookings');
        $this->loadTranslationsFrom(__DIR__.'/../../resources/lang', 'cortex/bookings');
        ! $this->app->runningInConsole() || $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->app->runningInConsole() || $this->app->afterResolving('blade.compiler', function () {
            require __DIR__.'/../../routes/menus/managerarea.php';
            require __DIR__.'/../../routes/menus/adminarea.php';
        });

        // Publish resources
        ! $this->app->runningInConsole() || $this->publishResources();
    }

    /**
     * Publish resources.
     *
     * @return void
     */
    protected function publishResources(): void
    {
        $this->publishes([realpath(__DIR__.'/../../database/migrations') => database_path('migrations')], 'cortex-bookings-migrations');
        $this->publishes([realpath(__DIR__.'/../../config/config.php') => config_path('cortex.bookings.php')], 'cortex-bookings-config');
        $this->publishes([realpath(__DIR__.'/../../resources/lang') => resource_path('lang/vendor/cortex/bookings')], 'cortex-bookings-lang');
        $this->publishes([realpath(__DIR__.'/../../resources/views') => resource_path('views/vendor/cortex/bookings')], 'cortex-bookings-views');
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        // Register artisan commands
        foreach ($this->commands as $key => $value) {
            $this->app->singleton($value, $key);
        }

        $this->commands(array_values($this->commands));
    }
}
