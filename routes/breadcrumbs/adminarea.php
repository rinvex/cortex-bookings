<?php

declare(strict_types=1);

use Cortex\Bookings\Models\Event;
use Cortex\Bookings\Models\Service;
use Diglactic\Breadcrumbs\Generator;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Cortex\Bookings\Models\EventTicket;
use Cortex\Bookings\Models\EventBooking;
use Cortex\Bookings\Models\ServiceBooking;

Breadcrumbs::for('adminarea.services.index', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/bookings::common.services'), route('adminarea.services.index'));
});

Breadcrumbs::for('adminarea.services.import', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.services.index');
    $breadcrumbs->push(trans('cortex/bookings::common.import'), route('adminarea.services.import'));
});

Breadcrumbs::for('adminarea.services.import.logs', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.services.index');
    $breadcrumbs->push(trans('cortex/bookings::common.import'), route('adminarea.services.import'));
    $breadcrumbs->push(trans('cortex/bookings::common.logs'), route('adminarea.services.import.logs'));
});

Breadcrumbs::for('adminarea.services.create', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.services.index');
    $breadcrumbs->push(trans('cortex/bookings::common.create_service'), route('adminarea.services.create'));
});

Breadcrumbs::for('adminarea.services.edit', function (Generator $breadcrumbs, Service $service) {
    $breadcrumbs->parent('adminarea.services.index');
    $breadcrumbs->push(strip_tags($service->name), route('adminarea.services.edit', ['service' => $service]));
});

Breadcrumbs::for('adminarea.services.logs', function (Generator $breadcrumbs, Service $service) {
    $breadcrumbs->parent('adminarea.services.index');
    $breadcrumbs->push(strip_tags($service->name), route('adminarea.services.edit', ['service' => $service]));
    $breadcrumbs->push(trans('cortex/bookings::common.logs'), route('adminarea.services.logs', ['service' => $service]));
});

Breadcrumbs::for('adminarea.services.media.index', function (Generator $breadcrumbs, Service $service) {
    $breadcrumbs->parent('adminarea.services.index');
    $breadcrumbs->push(strip_tags($service->name), route('adminarea.services.edit', ['service' => $service]));
    $breadcrumbs->push(trans('cortex/bookings::common.media'), route('adminarea.services.media.index', ['service' => $service]));
});

Breadcrumbs::for('adminarea.services.bookings.index', function (Generator $breadcrumbs, Service $service) {
    $breadcrumbs->parent('adminarea.services.edit', $service);
    $breadcrumbs->push(trans('cortex/bookings::common.bookings'), route('adminarea.services.bookings.index', ['service' => $service]));
});

Breadcrumbs::for('adminarea.services.bookings.import', function (Generator $breadcrumbs, Service $service) {
    $breadcrumbs->parent('adminarea.services.bookings.index');
    $breadcrumbs->push(trans('cortex/bookings::common.import'), route('adminarea.services.bookings.import'));
});

Breadcrumbs::for('adminarea.services.bookings.import.logs', function (Generator $breadcrumbs, Service $service) {
    $breadcrumbs->parent('adminarea.services.bookings.index');
    $breadcrumbs->push(trans('cortex/bookings::common.import'), route('adminarea.services.bookings.import'));
    $breadcrumbs->push(trans('cortex/bookings::common.logs'), route('adminarea.services.bookings.import.logs'));
});

Breadcrumbs::for('adminarea.services.bookings.create', function (Generator $breadcrumbs, Service $service) {
    $breadcrumbs->parent('adminarea.services.edit', $service);
    $breadcrumbs->push(trans('cortex/bookings::common.create_service_booking'), route('adminarea.services.bookings.create', ['service' => $service]));
});

Breadcrumbs::for('adminarea.services.bookings.edit', function (Generator $breadcrumbs, Service $service, ServiceBooking $serviceBooking) {
    $breadcrumbs->parent('adminarea.services.edit');
    $breadcrumbs->push(strip_tags($service->name), route('adminarea.services.bookings.edit', ['service' => $service]));
});

Breadcrumbs::for('adminarea.events.index', function (Generator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.config('app.name'), route('adminarea.home'));
    $breadcrumbs->push(trans('cortex/bookings::common.events'), route('adminarea.events.index'));
});

Breadcrumbs::for('adminarea.events.import', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.events.index');
    $breadcrumbs->push(trans('cortex/bookings::common.import'), route('adminarea.events.import'));
});

Breadcrumbs::for('adminarea.events.import.logs', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.events.index');
    $breadcrumbs->push(trans('cortex/bookings::common.import'), route('adminarea.events.import'));
    $breadcrumbs->push(trans('cortex/bookings::common.logs'), route('adminarea.events.import.logs'));
});

Breadcrumbs::for('adminarea.events.create', function (Generator $breadcrumbs) {
    $breadcrumbs->parent('adminarea.events.index');
    $breadcrumbs->push(trans('cortex/bookings::common.create_event'), route('adminarea.events.create'));
});

Breadcrumbs::for('adminarea.events.edit', function (Generator $breadcrumbs, Event $event) {
    $breadcrumbs->parent('adminarea.events.index');
    $breadcrumbs->push(strip_tags($event->name), route('adminarea.events.edit', ['event' => $event]));
});

Breadcrumbs::for('adminarea.events.logs', function (Generator $breadcrumbs, Event $event) {
    $breadcrumbs->parent('adminarea.events.index');
    $breadcrumbs->push(strip_tags($event->name), route('adminarea.events.edit', ['event' => $event]));
    $breadcrumbs->push(trans('cortex/bookings::common.logs'), route('adminarea.events.logs', ['event' => $event]));
});

Breadcrumbs::for('adminarea.events.media.index', function (Generator $breadcrumbs, Event $event) {
    $breadcrumbs->parent('adminarea.events.index');
    $breadcrumbs->push(strip_tags($event->name), route('adminarea.events.edit', ['event' => $event]));
    $breadcrumbs->push(trans('cortex/bookings::common.media'), route('adminarea.events.media.index', ['event' => $event]));
});

Breadcrumbs::for('adminarea.events.tickets.index', function (Generator $breadcrumbs, Event $event) {
    $breadcrumbs->parent('adminarea.events.edit', $event);
    $breadcrumbs->push(trans('cortex/bookings::common.tickets'), route('adminarea.events.tickets.index', ['event' => $event]));
});

Breadcrumbs::for('adminarea.events.tickets.create', function (Generator $breadcrumbs, Event $event) {
    $breadcrumbs->parent('adminarea.events.tickets.index', $event);
    $breadcrumbs->push(trans('cortex/bookings::common.create_ticket'), route('adminarea.events.tickets.create', ['event' => $event]));
});

Breadcrumbs::for('adminarea.events.tickets.edit', function (Generator $breadcrumbs, Event $event, EventTicket $eventTicket) {
    $breadcrumbs->parent('adminarea.events.tickets.index', $event);
    $breadcrumbs->push(strip_tags($eventTicket->name), route('adminarea.events.tickets.edit', ['event' => $event, 'ticket' => $eventTicket]));
});

Breadcrumbs::for('adminarea.events.bookings.index', function (Generator $breadcrumbs, Event $event) {
    $breadcrumbs->parent('adminarea.events.edit', $event);
    $breadcrumbs->push(trans('cortex/bookings::common.bookings'), route('adminarea.events.bookings.index', ['event' => $event]));
});

Breadcrumbs::for('adminarea.events.bookings.create', function (Generator $breadcrumbs, Event $event) {
    $breadcrumbs->parent('adminarea.events.bookings.index', $event);
    $breadcrumbs->push(trans('cortex/bookings::common.create_booking'), route('adminarea.events.bookings.create', ['event' => $event]));
});

Breadcrumbs::for('adminarea.events.bookings.edit', function (Generator $breadcrumbs, Event $event, EventBooking $eventBooking) {
    $breadcrumbs->parent('adminarea.events.bookings.index', $event);
    $breadcrumbs->push(strip_tags($eventBooking->name), route('adminarea.events.bookings.edit', ['event' => $event, 'booking' => $eventBooking]));
});

Breadcrumbs::for('adminarea.events.bookings.import', function (Generator $breadcrumbs, Event $event) {
    $breadcrumbs->parent('adminarea.events.bookings.index', $event);
    $breadcrumbs->push(trans('cortex/bookings::common.import'), route('adminarea.events.bookings.import', ['event' => $event]));
});
