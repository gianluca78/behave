if (typeof jQuery == 'undefined') {
    throw new Error('jQuery is not loaded');
}

if (typeof jQuery.ui == 'undefined') {
    throw new Error('jQuery UI is not loaded');
}

(function ( $ ) {
    $.fn.behavioralRecordingTool = function() {
        return this.each(function() {
            function addTimeIntervalForm($collectionHolder) {
                var prototype = $collectionHolder.data('prototype');

                var index = $collectionHolder.find(':input').length / 2;

                var newForm = prototype.replace(/__name__/g, index);

                $collectionHolder.data('index', index + 1);
                $collectionHolder.append('<div style="display: none;">' + newForm + '</div>');
            }

            function addOccurrenceTimestampForm($collectionHolder) {
                var prototype = $collectionHolder.data('prototype');

                var index = $collectionHolder.find(':input').length;

                var newForm = prototype.replace(/__name__/g, index);

                $collectionHolder.data('index', index + 1);
                $collectionHolder.append(newForm);
            }

            function progress(timeleft, timetotal, $element) {
                var progressBarWidth = timeleft * $element.width() / timetotal;
                var timeleft = (timeleft == timetotal) ? 0 : timetotal;

                $element.find('div').animate(
                    { width: progressBarWidth }, timeleft, 'linear'
                ).html('');
            };

            function startTimer(observationLengthInMinutesId, timerId, partialLengthInSecondsId, buttonId, progressBarId)
            {
                w = null;
                data = null;
                observationLengthInMinutes = $( '#' + observationLengthInMinutesId ).val();
                partialLengthInSeconds = $ ( '#' + partialLengthInSecondsId ).val() || null;
                intervalPlayedAudio = [];
                isCounterClicked = false;
                timestampLastPlayedAudio = null;

                // First check whether Web Workers are supported
                if (typeof(Worker)!=="undefined"){
                    // Check whether Web Worker has been created. If not, create a new Web Worker based on the Javascript file simple-timer.js
                    if (w==null){
                        w = new Worker("/js/simple-timer.js");
                        w.postMessage({
                            observationLengthInMinutes: observationLengthInMinutes,
                            partialLengthInSeconds: partialLengthInSeconds
                        });
                    }

                    // Update timer div with output from Web Worker
                    w.onmessage = function (event) {
                        var timestampNow = ~~(Date.now()/1000);
                        var button = $ ( '#' + buttonId);
                        data = event.data;

                        $( '#' + timerId ).text(data.timer);

                        var audioElement = new Audio(audioPath);

                        if(data.hasInterval == true &&
                            data.intervalTimer == '00:00' &&
                            intervalPlayedAudio.includes(data.intervalNumber)==false
                            ) {

                            intervalPlayedAudio.push(data.intervalNumber);
                            timestampLastPlayedAudio = ~~(Date.now()/1000);

                            audioElement.play();
                            $( '#' + timerId ).effect('shake');
                        }

                        if(timestampNow >= timestampLastPlayedAudio && timestampNow <= timestampLastPlayedAudio + 3){
                            method = (!isCounterClicked) ? 'removeClass' : 'addClass';
                            button[method]('red-button');
                        } else {
                            button.addClass('red-button');
                            isCounterClicked = false;
                        }

                        progress(
                            Math.floor(data.distance / 1000),
                            Math.floor(data.observationLengthInMilliseconds / 1000),
                            $('#' + progressBarId)
                        );

                    };
                } else {
                    // Web workers are not supported by your browser
                    $( '#' + timerId).text("Sorry, your browser does not support Web Workers ...");
                }
            }

            $( '.duration-item' ).each(function(i, e) {
                children = $(e).children();

                observationLengthInMinutesId = $(children[0]).children()[0].id;
                timerId = $(children[0]).children()[5].id;
                progressBarId = $(children)[1].id;

                startTimer(observationLengthInMinutesId, timerId, null, null, progressBarId);
            });

            $( '.time-sampling-item' ).each(function(i, e) {
                children = $(e).children()[1];
                observationLengthInMinutesId = $(children).children()[0].id;
                partialLengthInSecondsId = $(children).children()[2].id;
                timerId = $(children).children()[4].id;
                buttonId = $(e).children()[1].lastChild.children[0].id;
                progressBarId = $(e).children()[2].id;

                startTimer(observationLengthInMinutesId, timerId, partialLengthInSecondsId, buttonId, progressBarId);
            });

            $( "a.counter" ).click(function(e){
                e.preventDefault();

                if( !$(this).hasClass('red-button') ) {
                    baseSelectorId = $( this ).attr('data-base-selector-id');
                    dataIntervalDiv = $( "#" + baseSelectorId + '_intervalData' );

                    addTimeIntervalForm(dataIntervalDiv);

                    lastIndex = ($( "#" + baseSelectorId + '_intervalData' ).find(':input').length / 2) - 1;
                    intervalNumber = (data.timer == '00:00') ? data.intervalNumber : data.intervalNumber - 1;

                    $( '#' + baseSelectorId + '_intervalData_' + lastIndex + '_intervalNumber').val(intervalNumber);
                    $( '#' + baseSelectorId + '_intervalData_' + lastIndex + '_isBehaviorOccurred').val(true);

                    $(this).addClass('red-button');
                    isCounterClicked = true;
                }
            });

            $( "a.player" ).click(function(e){
                e.preventDefault();

                baseSelectorId = $( this ).attr('data-base-selector-id');
                timer = $( "#timer-" + baseSelectorId );
                occurrencesTimestampDiv = $( "#" + baseSelectorId + '_occurrenceTimestamps' );

                if(timer.text() != '00:00') {
                    newString = ($( this ).text() == '►') ? '■' : '►';
                    newIcon = (newString == '■') ? '<img src="/icons/spinner.GIF" width="24px" height="24px">' : '<img src="/icons/spinner.png" width="24px" height="24px">';

                    iconSelector = $( this ).parent().parent().parent().children()[3];

                    $( this ).html(newString);
                    $ ( iconSelector ).html(newIcon);

                    addOccurrenceTimestampForm(occurrencesTimestampDiv);

                    lastIndex = $( "#" + baseSelectorId + '_occurrenceTimestamps' ).find(':input').length - 1;
                    timestamp = ~~(Date.now()/1000);

                    $( '#' + baseSelectorId + '_occurrenceTimestamps_' + lastIndex).val(timestamp);
                }
            });
        });

    };

}( jQuery ));
