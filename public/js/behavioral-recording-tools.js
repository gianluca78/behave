if (typeof jQuery == 'undefined') {
    throw new Error('jQuery is not loaded');
}

if (typeof jQuery.ui == 'undefined') {
    throw new Error('jQuery UI is not loaded');
}

(function ( $ ) {
    $.fn.behavioralRecordingTool = function() {
        activeIntervalNumber = [];
        isCounterClicked = [];
        intervalPlayedAudio = [];
        numberOfSecondsToClickToCounterButton = 5;
        timestampLastPlayedAudio = [];

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
                    { width: progressBarWidth }, 1, 'linear'
                ).html('');
            };

            function startTimer(observationLengthInMinutesId, timerId, partialLengthInSecondsId, buttonId, progressBarId, feedbackForIntervalRecordingId)
            {
                var w = null;
                var data = null;
                var observationLengthInMinutes = $( '#' + observationLengthInMinutesId ).val();
                var partialLengthInSeconds = $ ( '#' + partialLengthInSecondsId ).val() || null;
                var numberOfIntervals = Math.round(observationLengthInMinutes * 60 / partialLengthInSeconds);

                // First check whether Web Workers are supported
                if (typeof(Worker)!=="undefined"){
                    // Check whether Web Worker has been created. If not, create a new Web Worker based on the Javascript file simple-timer.js
                    if (w==null){
                        w = new Worker("/js/simple-timer.js");
                        w.postMessage({
                            observationLengthInMinutes: observationLengthInMinutes,
                            partialLengthInSeconds: partialLengthInSeconds,
                            timerId: timerId
                        });
                    }

                    // Update timer div with output from Web Worker
                    w.onmessage = function (event) {
                        var timestampNow = ~~(Date.now()/1000);
                        var button = $ ( '#' + buttonId);
                        var data = event.data;

                        $( '#' + data.timerId ).text(data.timer);

                        var audioElement = new Audio(audioPath);

                        if(!(timerId in intervalPlayedAudio)) {
                            intervalPlayedAudio[timerId] = [];
                        }

                        if(!(timerId in activeIntervalNumber)) {
                            activeIntervalNumber[timerId] = 0;
                        }

                        if(data.hasInterval == true &&
                            data.intervalTimer == '00:00' &&
                            $.inArray(data.intervalNumber, intervalPlayedAudio[timerId]) == -1
                            ) {

                            intervalPlayedAudio[timerId].push(data.intervalNumber);
                            timestampLastPlayedAudio[timerId] = ~~(Date.now()/1000);

                            if($('#' + feedbackForIntervalRecordingId).val() == 'visual-feedback') {
                                $( '#' + progressBarId ).effect('pulsate', { 'duration': 800 });
                            } else {
                                audioElement.pause();
                                audioElement.play();
                            }

                            activeIntervalNumber[timerId]++;
                        }

                        if(data.hasInterval == true) {
                            if(timestampNow >= timestampLastPlayedAudio[timerId]
                                && timestampNow <= timestampLastPlayedAudio[timerId] + numberOfSecondsToClickToCounterButton
                                ){
                                method = (!isCounterClicked[buttonId]) ? 'removeClass' : 'addClass';

                                button[method]('red-button');
                            } else {
                                    if(data.timer == '00:00') {
                                        button.removeClass('red-button');
                                    } else {
                                        button.addClass('red-button');
                                        isCounterClicked[buttonId] = false;
                                    }
                            }
                        } else {
                            button.removeClass('red-button');
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

            $( '.duration-item a.player').on('click', function(e) {
                e.preventDefault();

                if($(this).html() == 'START') {
                    durationItem = $(this).parent().parent().parent().parent().parent();

                    observationLengthInMinutesId = $(durationItem).find('input[id*="observationLengthInMinutes"]').attr('id');
                    timerId = $(durationItem).find('div[id*="timer"]').attr('id');
                    progressBarId = $(durationItem).find('div[id*="progressBar"]').attr('id');

                    startTimer(observationLengthInMinutesId, timerId, null, null, progressBarId, null);
                }
            });

            $( '.frequency-item a.frequency-counter').on('click', function(e) {
                if($(this).html() == 'START') {
                    $(this).html('+');

                    frequencyItem = $(this).parent().parent().parent().parent();

                    observationLengthInMinutesId = $(frequencyItem).find('input[id*="observationLengthInMinutes"]').attr('id');
                    timerId = $(frequencyItem).find('div[id*="timer"]').attr('id');
                    buttonId = $(frequencyItem).find('a[id*="button"]').attr('id');
                    progressBarId = $(frequencyItem).find('div[id*="progressBar"]').attr('id');

                    startTimer(observationLengthInMinutesId, timerId, null, buttonId, progressBarId, null);

                }

            });

            $( '.time-sampling-item a.counter').on('click', function(){

                if($(this).html() == 'START') {
                    timeSamplingItem = $(this).parent().parent().parent().parent();

                    observationLengthInMinutesId = $(timeSamplingItem).find('input[id*="observationLengthInMinutes"]').attr('id');
                    partialLengthInSecondsId = $(timeSamplingItem).find('input[id*="intervalLengthInSeconds"]').attr('id');
                    feedbackForIntervalRecordingId = $(timeSamplingItem).find('input[id*="feedbackForIntervalRecording"]').attr('id');
                    timerId = $(timeSamplingItem).find('div[id*="timer"]').attr('id');
                    buttonId = $(timeSamplingItem).find('a[id*="button"]').attr('id');
                    progressBarId = $(timeSamplingItem).find('div[id*="progressBar"]').attr('id');

                    startTimer(observationLengthInMinutesId, timerId, partialLengthInSecondsId, buttonId, progressBarId, feedbackForIntervalRecordingId);
                }
            });

            $( "a.counter" ).click(function(e){
                e.preventDefault();

                if($(this).html() == 'START') {
                    $(this).html('+');
                } else {
                    if( !$(this).hasClass('red-button') ) {
                        baseSelectorId = $( this ).attr('data-base-selector-id');
                        timerSelectorId = $(this).attr("id").replace('button', 'timer');

                        dataIntervalDiv = $( "#" + baseSelectorId + '_intervalData' );

                        addTimeIntervalForm(dataIntervalDiv);

                        lastIndex = ($( "#" + baseSelectorId + '_intervalData' ).find(':input').length / 2) - 1;

                        $( '#' + baseSelectorId + '_intervalData_' + lastIndex + '_intervalNumber').val(activeIntervalNumber[timerSelectorId]);
                        $( '#' + baseSelectorId + '_intervalData_' + lastIndex + '_isBehaviorOccurred').val(true);

                        counterValue = $( '#' + baseSelectorId + '_counter').val() == 0 ?
                            parseInt($( '#' + baseSelectorId + '_counter').val()) + 2 :
                            parseInt($( '#' + baseSelectorId + '_counter').val()) + 1;

                        $( '#' + baseSelectorId + '_counter').val(counterValue);

                        $(this).addClass('red-button');

                        isCounterClicked[$(this).attr("id")] = true;
                    }
                }
            });

            $( "a.frequency-counter" ).click(function(e){
                e.preventDefault();

                baseSelectorId = $( this ).attr('data-base-selector-id');
                counterValue = parseInt($( '#' + baseSelectorId + '_counter').val()) + 1;
                occurrencesTimestampDiv = $( "#" + baseSelectorId + '_occurrenceTimestamps' );

                addOccurrenceTimestampForm(occurrencesTimestampDiv);

                lastIndex = $( "#" + baseSelectorId + '_occurrenceTimestamps' ).find(':input').length - 1;
                timestamp = ~~(Date.now()/1000);

                $( '#' + baseSelectorId + '_occurrenceTimestamps_' + lastIndex).val(timestamp);
                $( '#' + baseSelectorId + '_counter').val(counterValue);
                $( '#counter-' + baseSelectorId).text(counterValue);

            });

            $( "a.player" ).click(function(e){
                e.preventDefault();

                if($( this ).html() == 'START') {
                    $( this ).html('►');
                } else {
                    baseSelectorId = $( this ).attr('data-base-selector-id');
                    timer = $( "#timer-" + baseSelectorId );
                    occurrencesTimestampDiv = $( "#" + baseSelectorId + '_occurrenceTimestamps' );

                    if(timer.text() != '00:00') {
                        newString = ($( this ).text() == '►') ? '■' : '►';
                        newIcon = (newString == '■') ? '<img src="/icons/spinner.GIF" width="24px" height="24px">' : '<img src="/icons/spinner.png" width="24px" height="24px">';

                        iconSelector = $( '.spinner' );

                        $( this ).html(newString);
                        $ ( iconSelector ).html(newIcon);

                        addOccurrenceTimestampForm(occurrencesTimestampDiv);

                        lastIndex = $( "#" + baseSelectorId + '_occurrenceTimestamps' ).find(':input').length - 1;
                        timestamp = ~~(Date.now()/1000);

                        $( '#' + baseSelectorId + '_occurrenceTimestamps_' + lastIndex).val(timestamp);
                    }
                }
            });
        });

    };

}( jQuery ));