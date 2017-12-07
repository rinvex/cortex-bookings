$(function() {
    $calendar = $('div[data-calendar]');

    $calendar.fullCalendarCortal({
        editable: true,
        selectable: true,
        selectHelper: false,
        unselectAuto: false,
        disableResizing: false,
        cortalPopupBreakPoint: 768,
        views: 'month, week, agendaDay, listYear',
        defaultView: 'agendaWeek',
        dateFormat: 'YYYY-MM-DD',
        timeFormat: 'hh:mm a',
        firstDay: 0,
        navLinks: true,
        eventLimit: true,
        eventLimitClick: 'popover',
        header: {
            center: 'title',
            left: 'prev,next today',
            right: 'month,agendaWeek,agendaDay,listYear',
        },
        events: {
            url: routes.route('adminarea.bookings.list'),
            data: {
                _token: window.Laravel.csrfToken,
            },
            type: 'POST',
        },
        customers: $.ajax({
            url: routes.route('adminarea.bookings.customers'),
            data: {
                _token: window.Laravel.csrfToken,
            },
            type: 'POST',
        }),
        rooms: $.ajax({
            url: routes.route('adminarea.bookings.rooms'),
            data: {
                _token: window.Laravel.csrfToken,
            },
            type: 'POST',
        }),

        // Create the event using AJAX
        eventCreate: function($calendar, calendarEvent) {
            var dateFormat = $calendar.fullCalendar('option', 'dateFormat');
            var timeFormat = $calendar.fullCalendar('option', 'timeFormat');

            $.ajax({
                type: 'POST',
                url: routes.route('adminarea.bookings.store'),
                data: {
                    starts_at: calendarEvent.allDay
                        ? calendarEvent.start.format(dateFormat)
                        : calendarEvent.start.format(dateFormat + ' ' + timeFormat),
                    ends_at: calendarEvent.allDay
                        ? calendarEvent.end.format(dateFormat)
                        : calendarEvent.end.format(dateFormat + ' ' + timeFormat),
                    customer_id: calendarEvent.customerId,
                    room_id: calendarEvent.roomId,
                    _token: window.Laravel.csrfToken,
                },
            }).done(function(response, status, request) {
                // Rebind newly created event: remove old event object
                $calendar.fullCalendar('removeEvents', calendarEvent.id);

                // Update event object
                calendarEvent.id = response;
                calendarEvent.editable = true;

                // Rebind newly created event: render new event object
                $calendar.fullCalendar('renderEvent', calendarEvent);
            });
        },

        // Update the event using AJAX
        eventUpdate: function($calendar, calendarEvent) {
            var dateFormat = $calendar.fullCalendar('option', 'dateFormat');
            var timeFormat = $calendar.fullCalendar('option', 'timeFormat');

            $.ajax({
                type: 'PUT',
                url: routes.route('adminarea.bookings.update', { booking: calendarEvent.id }),
                data: {
                    starts_at: calendarEvent.allDay
                        ? calendarEvent.start.format(dateFormat)
                        : calendarEvent.start.format(dateFormat + ' ' + timeFormat),
                    ends_at: calendarEvent.allDay
                        ? calendarEvent.end.format(dateFormat)
                        : calendarEvent.end.format(dateFormat + ' ' + timeFormat),
                    customer_id: calendarEvent.customerId,
                    room_id: calendarEvent.roomId,
                    _token: window.Laravel.csrfToken,
                },
            }).done(function(response, status, request) {
                // Rebind newly created event: remove old event object
                $calendar.fullCalendar('removeEvents', calendarEvent.id);

                // Rebind newly created event: render new event object
                $calendar.fullCalendar('renderEvent', calendarEvent);
            });
        },

        // Delete the event using AJAX
        eventDelete: function($calendar, calendarEvent) {
            var dateFormat = $calendar.fullCalendar('option', 'dateFormat');
            var timeFormat = $calendar.fullCalendar('option', 'timeFormat');

            $.ajax({
                type: 'DELETE',
                url: routes.route('adminarea.bookings.delete', { booking: calendarEvent.id }),
                data: {
                    _token: window.Laravel.csrfToken,
                },
            }).done(function(response, status, request) {
                // Remove deleted event object
                $calendar.fullCalendar('removeEvents', calendarEvent.id);
            });
        },

        loading: function(bool) {},
        eventDrop: function(event, dayDelta, minuteDelta, allDay, revertFunc) {},
        eventRender: function(event, element) {},
        viewDisplay: function(view) {},
        dayClick: function(date, allDay, domEvent, view) {},
    });
});
