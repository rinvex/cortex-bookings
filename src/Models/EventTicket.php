<?php

declare(strict_types=1);

namespace Cortex\Bookings\Models;

use Cortex\Foundation\Traits\Auditable;
use Rinvex\Support\Traits\HashidsTrait;
use Cortex\Foundation\Events\CrudPerformed;
use Spatie\Activitylog\Traits\LogsActivity;
use Rinvex\Bookings\Models\TicketableTicket;
use Cortex\Foundation\Traits\FiresCustomModelEvent;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventTicket extends TicketableTicket
{
    use Auditable;
    use HashidsTrait;
    use LogsActivity;
    use FiresCustomModelEvent;

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => CrudPerformed::class,
        'deleted' => CrudPerformed::class,
        'restored' => CrudPerformed::class,
        'updated' => CrudPerformed::class,
    ];

    /**
     * Indicates whether to log only dirty attributes or all.
     *
     * @var bool
     */
    protected static $logOnlyDirty = true;

    /**
     * The attributes that are logged on change.
     *
     * @var array
     */
    protected static $logFillable = true;

    /**
     * The attributes that are ignored on change.
     *
     * @var array
     */
    protected static $ignoreChangedAttributes = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * The event ticket may have many bookings.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(config('cortex.bookings.models.event_booking'), 'ticket_id', 'id');
    }
}
