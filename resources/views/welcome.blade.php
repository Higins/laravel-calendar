<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

</head>

<body class="container">
    <div id='calendar'></div>

    <div class="modal fade" id="createEventModal" tabindex="-1" role="dialog" aria-labelledby="createEventModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createEventModalLabel">Create Event</h5>
                </div>
                <div class="modal-body">
                    <div id="msg"></div>
                    <form>
                        <div class="form-group">
                            <label for="eventName">Name of client</label>
                            <input type="text" class="form-control" id="name_of_client" placeholder="Name of client">
                        </div>
                        <div class="form-group">
                            <label for="eventName">Start date (required)</label>
                            <input type="datetime-local" class="form-control" id="start_date" placeholder="Start date"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="eventName">End date</label>
                            <input type="datetime-local" class="form-control" id="end_date" placeholder="End date">
                        </div>
                        <div class="form-group">
                            <label for="eventName">Recurrence</label>
                            <select class="form-select" id="recurrence">
                                @foreach ($eventTypes as $eventType)
                                    <option value="{{ $eventType->value }}">{{ $eventType->value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveEvent">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.30.1/moment.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
</script>
<!-- calendar js -->
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            editable: true,
            selectable: true,
            selectHelper: true,
            select: function(start, end, allDay) {
                $('#createEventModal').modal('show');
                $('#start_date').val(moment(start).format('YYYY-MM-DD HH:mm:ss'));
                $('#end_date').val(moment(end).format('YYYY-MM-DD HH:mm:ss'));
            },
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek,timeGridDay'
            },
            events: '{{route('events')}}',
            eventDidMount: function(info) {
                var eventStart = moment(info.event.start);
                var weekNumber = eventStart.week();

                if (info.event.groupId == 'every week even' && weekNumber % 2 !=
                    0) {
                    $(info.el).hide();
                } else if (info.event.groupId == 'every week odd' && weekNumber %
                    2 == 0) {
                    $(info.el).hide();
                } else if (info.event.groupId == 'every week') {

                } else if (info.event.groupId == 'every day') {

                } else if (info.event.groupId == 'none') {

                }
            }

        }, );
        calendar.render();

        $('#saveEvent').click(function() {
            $.ajax({
                url: '{{ route('create-event') }}',
                type: 'POST',
                data: {
                    start_date: $('#start_date').val(),
                    end_date: $('#end_date').val(),
                    recurrence: $('#recurrence').val(),
                    name_of_client: $('#name_of_client').val(),
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log(response);
                    if (response.success == 'Event created!') {
                        calendar.refetchEvents();
                        setTimeout(() => {
                            $('#createEventModal').modal('hide');
                        }, 10000);

                        $('#msg').html('<div class="alert alert-success" role="alert">' +
                            response.success + '</div>');
                    } else {
                        $('#msg').html('<div class="alert alert-danger" role="alert">' +
                            response.error + '</div>');
                    }


                }
            });
        });

    });
</script>

</html>
