var AppCalendar = function() {

    var h = {};
    var scheduledDates = [];
    var hasDates;

    return {
        setScheduledDates: function(dates) {
            scheduledDates = dates;
        },

        setHasDates: function(flagHasDates) {
            hasDates = flagHasDates;
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

            var initDrag = function(el) {
                // create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
                // it doesn't need to have a start or end
                var eventObject = {
                    title: $.trim(el.text()) // use the element's text as the event title
                };
                // store the Event Object in the DOM element so we can get to it later
                el.data('eventObject', eventObject);
                // make the event draggable using jQuery UI
                el.draggable({
                    zIndex: 999,
                    revert: true, // will cause the event to go back to its
                    revertDuration: 0 //  original position after the drag
                });
            };

            function updateEvents()
            {
                events = $('#calendar').fullCalendar('clientEvents');
                startDates = [];
                endDates = [];

                $(events).each(function(){
                    startDates.push(this.start.format('YYYY-MM-DD HH:mm:ss'));

                    //fix the issue described here: https://github.com/fullcalendar/fullcalendar/issues/2764
                    if(!this.allDay && this.end == null) {
                        eventEndDate = new Date(this.start.format('YYYY-MM-DD HH:mm:ss'));
                        eventEndDate.setHours(eventEndDate.getHours() + 3);
                        endDates.push(eventEndDate.toISOString().replace('T', ' ').slice(0, 19));
                    } else if (!this.allDay && this.end != null) {
                        endDates.push(this.end.format('YYYY-MM-DD HH:mm:ss'));
                    } else {
                        endDates.push(this.start.format('YYYY-MM-DD') + ' 23:59:59');
                    }
                });

                $.ajax({
                    url:"/calendar/update-date",
                    type:"POST",
                    data: {
                        observationId: $( "#calendar" ).data('observation-id'),
                        startDates: JSON.stringify(startDates),
                        endDates: JSON.stringify(endDates)
                    },
                    success:function(response) {

                    },
                    error: function(xhr, status, error) {
                        console.log(xhr.responseText);
                        console.log(error);

                    }
                });

                //alert(event.title + " was dropped on " + event.start.format());

                /*
                if (!confirm("Are you sure about this change?")) {
                    revertFunc();
                }*/
            }

            var addEvent = function(title) {
                title = title.length === 0 ? "Untitled Event" : title;
                var html = $('<div class="external-event label label-default">' + title + '</div>');
                jQuery('#event_box').append(html);
                initDrag(html);
            };

            $('#external-events div.external-event').each(function() {
                initDrag($(this));
            });

            $('#event_add').unbind('click').click(function() {
                var title = $('#event_title').val();
                addEvent(title);
            });

            //predefined events
            $('#event_box').html("");
            addEvent("My Event 1");
            addEvent("My Event 2");
            addEvent("My Event 3");
            addEvent("My Event 4");
            addEvent("My Event 5");
            addEvent("My Event 6");

            $('#calendar').fullCalendar('destroy'); // destroy the calendar
            $('#calendar').fullCalendar({ //re-initialize the calendar
                header: h,
                defaultView: 'month', // change default view with available options from http://arshaw.com/fullcalendar/docs/views/Available_Views/ 
                slotMinutes: 30,
                editable: true,
                droppable: true, // this allows things to be dropped onto the calendar !!!
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
                eventDrop: function(event, delta, revertFunc) {
                    updateEvents();
                },
                eventOverlap: false,
                eventResize: function(event, delta, revertFunc) {
                    updateEvents();
                },
                events: scheduledDates,

                viewRender: function( view, element ) {

                    /*
                    y = '2018';
                    m = '09';
                    d = '01';

                    events = [{
                        title: 'All Day Event',
                        start: new Date(y, m, 1),
                        backgroundColor: App.getBrandColor('yellow')
                    }, {
                        title: 'Long Event',
                        start: new Date(y, m, d - 5),
                        end: new Date(y, m, d - 2),
                        backgroundColor: App.getBrandColor('green')
                    }, {
                        title: 'Repeating Event',
                        start: new Date(y, m, d - 3, 16, 0),
                        allDay: false,
                        backgroundColor: App.getBrandColor('red')
                    }, {
                        title: 'Repeating Event',
                        start: new Date(y, m, d + 4, 16, 0),
                        allDay: false,
                        backgroundColor: App.getBrandColor('green')
                    }, {
                        title: 'Meeting',
                        start: new Date(y, m, d, 10, 30),
                        allDay: false
                    }, {
                        title: 'Lunch',
                        start: new Date(y, m, d, 12, 0),
                        end: new Date(y, m, d, 14, 0),
                        backgroundColor: App.getBrandColor('grey'),
                        allDay: false
                    }, {
                        title: 'Birthday Party',
                        start: new Date(y, m, d + 1, 19, 0),
                        end: new Date(y, m, d + 1, 22, 30),
                        backgroundColor: App.getBrandColor('purple'),
                        allDay: false
                    }, {
                        title: 'Click for Google',
                        start: new Date(y, m, 28),
                        end: new Date(y, m, 29),
                        backgroundColor: App.getBrandColor('yellow'),
                        url: 'http://google.com/'
                    }];*/

                    console.log(hasDates);

                    if(hasDates != 1) {
                        $('#calendar').fullCalendar('removeEvents');

                        events = [];

                        firstDate = $('#calendar').fullCalendar('getView').start.format('YYYY-MM-DD');
                        untilDate = new Date($('#calendar').fullCalendar('getView').end.format('YYYY-MM-DD'));

                        for (d = new Date(firstDate); d < untilDate; d.setDate(d.getDate() + 1)) {
                            events.push({
                                title: $('#calendar').data('observation-name'),
                                start: new Date(d),
                                backgroundColor: App.getBrandColor('red'),
                                allDay: true
                            })
                        }

                        $('#calendar').fullCalendar('addEventSource', events);
                        $('#calendar').fullCalendar('refetchEvents');
                    }
                }
            });

        }

    };

}();

jQuery(document).ready(function() {    
   AppCalendar.init(); 
});