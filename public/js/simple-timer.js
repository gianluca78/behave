var now = new Date();
var observationLengthInMinutes = null;

self.addEventListener("message", function(e) {
    observationLengthInMinutes = e.data;

    var countDownDate = new Date(now.getTime() + observationLengthInMinutes * 60000).getTime();

    // Update the count down every 1 second
    var x = setInterval(function() {
        countdown(countDownDate);
    }, 100);

}, false);

function countdown(countDownDate)
{
    // Get todays date and time
    var now = new Date().getTime();

    // Find the distance between now an the count down date
    var distance = countDownDate - now;

    // Time calculations for days, hours, minutes and seconds
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    // if number of minutes less than 10, add a leading "0"
    minutes = minutes.toString();
    if (minutes.length == 1){
        minutes = "0" + minutes;
    }
    // if number of seconds less than 10, add a leading "0"
    seconds = seconds.toString();
    if (seconds.length == 1){
        seconds = "0" + seconds;
    }

    postMessage(minutes + ":" + seconds);

    if(minutes == '00' && seconds == '00') {
        self.close();
    }
}