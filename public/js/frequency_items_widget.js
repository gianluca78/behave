$( document ).ready(function() {
    $( '.frequency-item' ).each(function(i, e) {
        children = $(e).children();

        observationLengthInMinutesId = $(children[0]).children()[0].id;
        timerId = $(children[0]).children()[5].id;

        startTimer(observationLengthInMinutesId, timerId);
    });
});

$( "a.counter" ).click(function(e){
    e.preventDefault();

    baseSelectorId = $( this ).attr('data-base-selector-id');
    counter = parseInt($( "#" + baseSelectorId + "_counter" ).val()) + 1;
    timer = $( "#timer-" + baseSelectorId );
    occurrencesTimestampDiv = $( "#" + baseSelectorId + '_occurrenceTimestamps' );

    if(timer.text() != '00:00') {
        $( "#" + baseSelectorId + "_counter" ).val(counter);
        $( "#counter-" + baseSelectorId + " span" ).text(counter);

        addOccurrenceTimestampForm(occurrencesTimestampDiv);

        lastIndex = $( "#" + baseSelectorId + '_occurrenceTimestamps' ).find(':input').length - 1;
        timestamp = ~~(Date.now()/1000);
        $( '#' + baseSelectorId + '_occurrenceTimestamps_' + lastIndex).val(timestamp);
    }

});

function addOccurrenceTimestampForm($collectionHolder) {

    var prototype = $collectionHolder.data('prototype');

    var index = $collectionHolder.find(':input').length;

    var newForm = prototype.replace(/__name__/g, index);

    $collectionHolder.data('index', index + 1);
    $collectionHolder.append(newForm);
}

function startTimer(observationLengthInMinutesId, timerId)
{
    w = null;

    // First check whether Web Workers are supported
    if (typeof(Worker)!=="undefined"){
        // Check whether Web Worker has been created. If not, create a new Web Worker based on the Javascript file simple-timer.js
        if (w==null){
            w = new Worker("/js/simple-timer.js");
            w.postMessage($( '#' + observationLengthInMinutesId ).val());
        }

        // Update timer div with output from Web Worker
        w.onmessage = function (event) {
            $( '#' + timerId ).text(event.data);
        };
    } else {
        // Web workers are not supported by your browser
        $( '#' + timerId).text("Sorry, your browser does not support Web Workers ...");
    }
}