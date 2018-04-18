self.addEventListener("message", function(e) {
    var startDate = new Date();
    var observationLengthInMinutes = e.data.observationLengthInMinutes;
    var partialLengthInSeconds = e.data.partialLengthInSeconds || null;

    var countDownDate = new Date(startDate.getTime() + observationLengthInMinutes * 60000).getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {
        countdown(countDownDate, startDate, observationLengthInMinutes, partialLengthInSeconds);
    }, 100);
}, false);

function countdown(countDownDate, startDate, observationLengthInMinutes, partialLengthInSeconds)
{
    // Get todays date and time
    var now = new Date().getTime();

    // Find the distance between now an the count down date
    var distance = countDownDate - now;
    var observationLengthInMilliseconds = observationLengthInMinutes * 60 * 1000;
    var partialLengthInMilliseconds = partialLengthInSeconds * 1000;
    var hasInterval = (partialLengthInMilliseconds) ? true : false;

    // Time calculations for days, hours, minutes and seconds
    var minutes = formatTimestampDifferenceToTimerSeconds(distance);
    var seconds = formatTimestampDifferenceToTimerMinutes(distance);

    if(partialLengthInMilliseconds) {
        var intervalNumber = getIntervalNumber(startDate.getTime(), countDownDate, partialLengthInMilliseconds, now);
        var distanceInterval = distance - (observationLengthInMilliseconds - intervalNumber * partialLengthInMilliseconds) + 1;
        var minutesInterval = formatTimestampDifferenceToTimerSeconds(distanceInterval);
        var secondsInterval = formatTimestampDifferenceToTimerMinutes(distanceInterval);
    }

    //console.log(distance, observationLengthInMinutes * 60 * 1000, intervalNumber);

    postMessage({
        'timer': minutes + ':' + seconds,
        'hasInterval': hasInterval,
        'intervalNumber': intervalNumber,
        'intervalTimer': minutesInterval + ':' + secondsInterval
    });

    if(minutes == '00' && seconds == '00') {
        self.close();
    }
}

function formatTimestampDifferenceToTimerMinutes(timestampDifference)
{
    minutes = Math.floor((timestampDifference % (1000 * 60)) / 1000);

    // if number of minutes less than 10, add a leading "0"
    minutes = minutes.toString();
    if (minutes.length == 1){
        minutes = "0" + minutes;
    }

    return minutes;
}

function formatTimestampDifferenceToTimerSeconds(timestampDifference)
{
    seconds = Math.floor((timestampDifference % (1000 * 60 * 60)) / (1000 * 60));

    // if number of seconds less than 10, add a leading "0"
    seconds = seconds.toString();
    if (seconds.length == 1){
        seconds = "0" + seconds;
    }

    return seconds;
}

function getIntervalNumber(startDate, endDate, partialLengthInMilliseconds, now)
{
    thresholds = [];

    for(i=startDate; i<=endDate; i+=partialLengthInMilliseconds) {
        thresholds.push(i);
    }

    intervalNumber = null;

    thresholds.forEach(function(value, key) {
       if(value <= now) {
           intervalNumber = key + 1;
       }
    });

    return intervalNumber;
}