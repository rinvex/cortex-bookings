<?php

declare(strict_types=1);

namespace Cortex\Bookings\Http\Controllers\Adminarea;

use Illuminate\Http\Request;
use Cortex\Bookings\Contracts\RoomContract;
use Cortex\Foundation\DataTables\LogsDataTable;
use Cortex\Bookings\DataTables\Adminarea\RoomsDataTable;
use Cortex\Bookings\Http\Requests\Adminarea\RoomFormRequest;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class RoomsController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'rooms';

    /**
     * Display a listing of the resource.
     *
     * @param \Cortex\Bookings\DataTables\Adminarea\RoomsDataTable $roomsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(RoomsDataTable $roomsDataTable)
    {
        return $roomsDataTable->with([
            'id' => 'cortex-rooms',
            'phrase' => trans('cortex/bookings::common.rooms'),
        ])->render('cortex/foundation::adminarea.pages.datatable');
    }

    /**
     * Display a listing of the resource logs.
     *
     * @param \Cortex\Bookings\Contracts\RoomContract     $room
     * @param \Cortex\Foundation\DataTables\LogsDataTable $logsDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function logs(RoomContract $room, LogsDataTable $logsDataTable)
    {
        return $logsDataTable->with([
            'tab' => 'logs',
            'type' => 'rooms',
            'resource' => $room,
            'id' => 'cortex-rooms-logs',
            'phrase' => trans('cortex/bookings::common.rooms'),
        ])->render('cortex/foundation::adminarea.pages.datatable-tab');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Cortex\Bookings\Http\Requests\Adminarea\RoomFormRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RoomFormRequest $request)
    {
        return $this->process($request, app('cortex.bookings.room'));
    }

    /**
     * Update the given resource in storage.
     *
     * @param \Cortex\Bookings\Http\Requests\Adminarea\RoomFormRequest $request
     * @param \Cortex\Bookings\Contracts\RoomContract                  $room
     *
     * @return \Illuminate\Http\Response
     */
    public function update(RoomFormRequest $request, RoomContract $room)
    {
        return $this->process($request, $room);
    }

    /**
     * Delete the given resource from storage.
     *
     * @param \Cortex\Bookings\Contracts\RoomContract $room
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(RoomContract $room)
    {
        $room->delete();

        return intend([
            'url' => route('adminarea.rooms.index'),
            'with' => ['warning' => trans('cortex/bookings::messages.room.deleted', ['slug' => $room->slug])],
        ]);
    }

    /**
     * Show the form for create/update of the given resource.
     *
     * @param \Cortex\Bookings\Contracts\RoomContract $room
     *
     * @return \Illuminate\Http\Response
     */
    public function form(RoomContract $room)
    {
        return view('cortex/bookings::adminarea.forms.room', compact('room'));
    }

    /**
     * Process the form for store/update of the given resource.
     *
     * @param \Illuminate\Http\Request                $request
     * @param \Cortex\Bookings\Contracts\RoomContract $room
     *
     * @return \Illuminate\Http\Response
     */
    protected function process(Request $request, RoomContract $room)
    {
        // Prepare required input fields
        $data = $request->all();

        // Save room
        $room->fill($data)->save();

        return intend([
            'url' => route('adminarea.rooms.index'),
            'with' => ['success' => trans('cortex/bookings::messages.room.saved', ['slug' => $room->slug])],
        ]);
    }
}
