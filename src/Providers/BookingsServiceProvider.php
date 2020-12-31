<?php

declare(strict_types=1);

namespace Cortex\Bookings\Providers;

use Illuminate\Routing\Router;
use Cortex\Bookings\Models\Event;
use Cortex\Bookings\Models\Service;
use Cortex\Bookings\Models\EventTicket;
use Cortex\Bookings\Models\ServiceRate;
use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Cortex\Bookings\Models\EventBooking;
use Cortex\Bookings\Models\ServiceBooking;
use Illuminate\Contracts\Events\Dispatcher;
use Cortex\Bookings\Models\ServiceAvailability;
use Cortex\Bookings\Console\Commands\SeedCommand;
use Cortex\Bookings\Console\Commands\UnloadCommand;
use Cortex\Bookings\Console\Commands\InstallCommand;
use Cortex\Bookings\Console\Commands\MigrateCommand;
use Cortex\Bookings\Console\Commands\PublishCommand;
use Illuminate\Database\Eloquent\Relations\Relation;
use Cortex\Bookings\Console\Commands\ActivateCommand;
use Cortex\Bookings\Console\Commands\AutoloadCommand;
use Cortex\Bookings\Console\Commands\RollbackCommand;
use Cortex\Bookings\Console\Commands\DeactivateCommand;

class BookingsServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        ActivateCommand::class => 'command.cortex.bookings.activate',
        DeactivateCommand::class => 'command.cortex.bookings.deactivate',
        AutoloadCommand::class => 'command.cortex.bookings.autoload',
        UnloadCommand::class => 'command.cortex.bookings.unload',

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
        // Bind eloquent models to IoC container
        $this->app->singleton('cortex.bookings.service', $serviceModel = $this->app['config']['cortex.bookings.models.service']);
        $serviceModel === Service::class || $this->app->alias('cortex.bookings.service', Service::class);

        $this->app->singleton('cortex.bookings.service_availability', $serviceAvailabilityModel = $this->app['config']['cortex.bookings.models.service_availability']);
        $serviceAvailabilityModel === ServiceAvailability::class || $this->app->alias('cortex.bookings.service_availability', ServiceAvailability::class);

        $this->app->singleton('cortex.bookings.service_booking', $serviceBookingModel = $this->app['config']['cortex.bookings.models.service_booking']);
        $serviceBookingModel === ServiceBooking::class || $this->app->alias('cortex.bookings.service_booking', ServiceBooking::class);

        $this->app->singleton('cortex.bookings.service_rate', $serviceRateModel = $this->app['config']['cortex.bookings.models.service_rate']);
        $serviceRateModel === ServiceRate::class || $this->app->alias('cortex.bookings.service_rate', ServiceRate::class);

        $this->app->singleton('cortex.bookings.event', $serviceAvailabilityModel = $this->app['config']['cortex.bookings.models.event']);
        $serviceAvailabilityModel === Event::class || $this->app->alias('cortex.bookings.event', Event::class);

        $this->app->singleton('cortex.bookings.event_ticket', $eventTicketModel = $this->app['config']['cortex.bookings.models.event_ticket']);
        $eventTicketModel === EventTicket::class || $this->app->alias('cortex.bookings.event_ticket', EventTicket::class);

        $this->app->singleton('cortex.bookings.event_booking', $eventBookingModel = $this->app['config']['cortex.bookings.models.event_booking']);
        $eventBookingModel === EventBooking::class || $this->app->alias('cortex.bookings.event_booking', EventBooking::class);

        // Register console commands
        $this->registerCommands($this->commands);

        // Bind eloquent models to IoC container
        $this->app->singleton('cortex.bookings.service', $serviceModel = $this->app['config']['cortex.bookings.models.service']);
        $serviceModel === Service::class || $this->app->alias('cortex.bookings.service', Service::class);

        $this->app->singleton('cortex.bookings.event', $eventModel = $this->app['config']['cortex.bookings.models.event']);
        $eventModel === Event::class || $this->app->alias('cortex.bookings.event', Event::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Router $router, Dispatcher $dispatcher): void
    {
        // Bind route models and constrains
        $router->pattern('service', '[a-zA-Z0-9-_]+');
        $router->pattern('service_rate', '[a-zA-Z0-9-_]+');
        $router->pattern('service_booking', '[a-zA-Z0-9-_]+');
        $router->pattern('service_availability', '[a-zA-Z0-9-_]+');
        $router->pattern('event_booking', '[a-zA-Z0-9-_]+');
        $router->pattern('event_ticket', '[a-zA-Z0-9-_]+');
        $router->pattern('event', '[a-zA-Z0-9-_]+');

        $router->model('service', config('cortex.bookings.models.service'));
        $router->model('service_rate', config('cortex.bookings.models.service_rate'));
        $router->model('service_booking', config('cortex.bookings.models.service_booking'));
        $router->model('service_availability', config('cortex.bookings.models.service_availability'));
        $router->model('event_booking', config('cortex.bookings.models.event_booking'));
        $router->model('event_ticket', config('cortex.bookings.models.event_ticket'));
        $router->model('event', config('cortex.bookings.models.event'));

        // Map relations
        Relation::morphMap([
            'service' => config('cortex.bookings.models.service'),
            'service_rate' => config('cortex.bookings.models.service_rate'),
            'service_booking' => config('cortex.bookings.models.service_booking'),
            'service_availability' => config('cortex.bookings.models.service_availability'),
            'event_booking' => config('cortex.bookings.models.event_booking'),
            'event_ticket' => config('cortex.bookings.models.event_ticket'),
            'event' => config('cortex.bookings.models.event'),
        ]);
    }
}
