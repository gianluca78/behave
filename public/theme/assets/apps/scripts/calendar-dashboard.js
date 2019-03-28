var AppCalendar = function() {

    var h = {};
    var scheduledDates = [];
    var observationsWithoutDates = [];

    return {
        setScheduledDates: function(dates) {
            scheduledDates = dates;
        },

        setObservationsWithoutDates: function(observations) {
            observationsWithoutDates = observations;
        },

        //main function to initiate the module
        init: function() {
            this.initCalendar();
        },

        initCalendar: function() {
            if (!jQuery().fullCalendar) {
                return;
            }

            if (App.isRTL()) {
                if ($('#calendar').parents(".portlet").width() <= 720) {
                    $('#calendar').addClass("mobile");
                    h = {
                        right: 'title, prev, next',
                        center: '',
                        left: 'agendaDay, agendaWeek, month, today'
                    };
                } else {
                    $('#calendar').removeClass("mobile");
                    h = {
                        right: 'title',
                        center: '',
                        left: 'agendaDay, agendaWeek, month, today, prev,next'
                    };
                }
            } else {
                if ($('#calendar').parents(".portlet").width() <= 720) {
                    $('#calendar').addClass("mobile");
                    h = {
                        left: 'title, prev, next',
                        center: '',
                        right: 'today,month,agendaWeek,agendaDay'
                    };
                } else {
                    $('#calendar').removeClass("mobile");
                    h = {
                        left: 'title',
                        center: '',
                        right: 'prev,next,today,month,agendaWeek,agendaDay'
                    };
                }
            }

            $('#calendar').fullCalendar('destroy'); // destroy the calendar
            $('#calendar').fullCalendar({ //re-initialize the calendar
                header: h,
                defaultView: 'agendaDay', // change default view with available options from http://arshaw.com/fullcalendar/docs/views/Available_Views/
                slotMinutes: 30,
                editable: false,
                droppable: false, // this allows things to be dropped onto the calendar !!!
                drop: function(date, allDay) { // this function is called when something is dropped

                    // retrieve the dropped element's stored Event Object
                    var originalEventObject = $(this).data('eventObject');
                    // we need to copy it, so that multiple events don't have a reference to the same object
                    var copiedEventObject = $.extend({}, originalEventObject);

                    // assign it the date that was reported
                    copiedEventObject.start = date;
                    copiedEventObject.allDay = allDay;
                    copiedEventObject.className = $(this).attr("data-class");

                    // render the event on the calendar
                    // the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
                    $('#calendar').fullCalendar('renderEvent', copiedEventObject, true);

                    // is the "remove after drop" checkbox checked?
                    if ($('#drop-remove').is(':checked')) {
                        // if so, remove the element from the "Draggable Events" list
                        $(this).remove();
                    }
                },
                eventClick: function(calEvent, jsEvent, view) {
                    window.location.assign('/measure/' + calEvent.observationId + '/' + calEvent.observationToken);
                },
                events: scheduledDates,
                viewRender: function( view, element ) {
                    $('#calendar').fullCalendar('removeEvents');

                    var uniqueArray = function(arrArg) {
                        return arrArg.filter(function(elem, pos,arr) {
                            return arr.indexOf(elem) == pos;
                        });
                    };

                    events = uniqueArray(scheduledDates);

                    for (i = 0; i < observationsWithoutDates.length; i++) {
                        firstDate = $('#calendar').fullCalendar('getView').start.format('YYYY-MM-DD');
                        untilDate = new Date($('#calendar').fullCalendar('getView').end.format('YYYY-MM-DD'));

                        for (d = new Date(firstDate); d < untilDate; d.setDate(d.getDate() + 1)) {

                            newEvent = {
                                title: observationsWithoutDates[i].title,
                                start: new Date(d),
                                backgroundColor: observationsWithoutDates[i].backgroundColor,
                                allDay: true,
                                observationId: observationsWithoutDates[i].observationId,
                                observationToken: observationsWithoutDates[i].observationToken
                            };

                            events.push(
                                newEvent
                            );

                        }
                    }

                    $('#calendar').fullCalendar('addEventSource', events);
                    $('#calendar').fullCalendar('refetchEvents');
                }
            });

        }

    };

}();

jQuery(document).ready(function() {    
   AppCalendar.init(); 
});