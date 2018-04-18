if (typeof jQuery == 'undefined') {
    throw new Error('jQuery is not loaded');
}

(function ( $ ) {
    $.fn.behavioralRecordingTool = function() {
        return this.each(function() {
            function addOccurrenceTimestampForm($collectionHolder) {
                var prototype = $collectionHolder.data('prototype');

                var index = $collectionHolder.find(':input').length;

                var newForm = prototype.replace(/__name__/g, index);

                $collectionHolder.data('index', index + 1);
                $collectionHolder.append(newForm);
            }

            function startTimer(typology, observationLengthInMinutesId, timerId, partialLengthInSeconds)
            {
                w = null;
                partialLengthInSeconds = partialLengthInSeconds || null;
                intervalPlayedAudio = [];

                // First check whether Web Workers are supported
                if (typeof(Worker)!=="undefined"){
                    // Check whether Web Worker has been created. If not, create a new Web Worker based on the Javascript file simple-timer.js
                    if (w==null){
                        w = new Worker("/js/simple-timer.js");
                        w.postMessage({
                            observationLengthInMinutes: $( '#' + observationLengthInMinutesId ).val(),
                            partialLengthInSeconds: partialLengthInSeconds
                        });
                    }

                    // Update timer div with output from Web Worker
                    w.onmessage = function (event) {
                        $( '#' + timerId ).text(event.data.timer);

                        var audioElement = new Audio(audioPath);

                        if(event.data.hasInterval == true &&
                            event.data.intervalTimer == '00:00' &&
                            intervalPlayedAudio.includes(event.data.intervalNumber)==false
                            ) {
                            intervalPlayedAudio.push(event.data.intervalNumber);

                            audioElement.play();
                        }
                    };
                } else {
                    // Web workers are not supported by your browser
                    $( '#' + timerId).text("Sorry, your browser does not support Web Workers ...");
                }
            }

            $( '.duration-item' ).each(function(i, e) {
                children = $(e).children();

                observationLengthInMinutesId = $(children[0]).children()[0].id;
                partialLengthInSeconds = $(children[0]).children()[2].id;
                timerId = $(children[0]).children()[5].id;

                //console.log($('#' + partialLengthInSeconds).val());

                startTimer('continuous', observationLengthInMinutesId, timerId, null);
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
