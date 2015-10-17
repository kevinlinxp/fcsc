@extends('layout')
@section('main')
    <div id="tempSecret"></div>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <h1 id="main_title">Hello, {{$student['firstName']}} {{$student['lastName']}}</h1>
        </div>
        <div class="col-md-2"></div>
    </div>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div id="row_toStart" class="row">
                <div class="col-md-6 middle lead">Last played: <span>{{$student['lastPlayed']}}</span></div>
                <div class="col-md-6">
                    <button id="btnStart" class="btn btn-primary">Start</button>
                </div>
            </div>
            <div id="row_gameRound" class="row" style="display:none">
                <div class="col-md-12 middle"><input id="guess" type="text" name="guess"
                                                     placeholder="Input 4 digits only">
                    <button id="btnGuess" class="btn btn-info">Guess!</button>
                </div>
                <div class="col-md-12">
                    <span id="notes" style="color: blue"><b>Note: </b>(1). You need to play 5 rounds to complete one game. (2). In each round, you will have up to 10 guesses. (3). The more guesses you have used in one round, the fewer points you will receive. (4). If more than 30 seconds is spent in one round, your points will be deducted.</span>
                </div>
                <div class="col-md-12 middle">
                    <span id="guessPoints" style="color: red"></span>
                </div>
                <div class="col-md-12 middle">
                    <table class="table">
                        <thead>
                        <tr>
                            <td></td>
                            <td><b>Guess</b></td>
                            <td><b>Result</b></td>
                        </tr>
                        </thead>
                        <tbody id="guessTableBody"><tr><td colspan="3">Round 1 has started!</td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
@stop
@section('footer_script')
    <script>

        var pattern = /^\d{4}$/;

        function ajaxStart() {
            jQuery.ajax('start', {
                method: 'POST',
                //data: {
                // '_token': '{{ csrf_token() }}',
                //    'studentId': '{{$student['id']}}'
                //},
                beforeSend: function (xhr) {
                    var token = '{{ csrf_token() }}';
                    if (token) {
                        return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }
                },
            }).done(function (jsonData, textStatus, jqXHR) {
                console.log(jsonData);
                switch (jsonData['result']) {
                    case 'roundStarted':
                        startRound(jsonData['roundData']);
                        break;
                    default:
                        reportError(jsonData);
                        break;
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
                alert("Sorry, request failed due to: " + errorThrown);
            });
        }

        function ajaxGuess(guessValue) {
            jQuery.ajax('guess', {
                method: 'POST',
                //beforeSend: function (xhr) {
                //var token = '{{ csrf_token() }}';
                //if (token) {
                //    return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                // }
                //},
                data: {
                    'guess': guessValue
                }
            }).done(function (jsonData, textStatus, jqXHR) {
                console.log(jsonData);

                var resultText = jsonData['roundData']['resultText'];
                var guessCount = jsonData['roundData']['guessCount'];
                var totalPoints = jsonData['roundData']['totalPoints'];
                var roundPoints = jsonData['roundData']['roundPoints'];
                var roundCount = jsonData['roundData']['roundCount'];
                var correctness = jsonData['roundData']['correctness'];

                jQuery('#guessPoints').text('Your total points received: '+totalPoints);

                jQuery('<tr><td>' + guessCount + '</td><td>' + jQuery('#guess').val() + '</td><td>' + resultText + '</td></tr>').prependTo('#guessTableBody');
                jQuery('#guess').val('');

                if(correctness){
                    if(roundCount<5){
                        jQuery('<tr><td colspan="3">Congratulations! You received ' + roundPoints  + ' points in this round!</td></tr>').prependTo('#guessTableBody');
                        jQuery('<tr><td colspan="3">Round ' + (roundCount+1) + ' has started!</td></tr>').prependTo('#guessTableBody');
                    }
                    else{
                        jQuery('<tr><td colspan="3">Congratulations! You received ' + roundPoints  + ' points in this final round!</td></tr>').prependTo('#guessTableBody');
                        jQuery('<tr><td colspan="3">You have finished the game! See you!</td></tr>').prependTo('#guessTableBody');
                        jQuery('#btnGuess').prop('disabled',true);
                    }
                }
                else if(guessCount % 10 == 0){
                    if(roundCount < 5) {
                        jQuery('<tr><td colspan="3">You reach guess number limit in this round!</td></tr>').prependTo('#guessTableBody');
                        jQuery('<tr><td colspan="3">Round ' + (roundCount+1) + ' has started!</td></tr>').prependTo('#guessTableBody');
                    }
                    else {
                        jQuery('<tr><td colspan="3">You reach guess number limit in this final round!</td></tr>').prependTo('#guessTableBody');
                        jQuery('<tr><td colspan="3">You have finished the game! See you!</td></tr>').prependTo('#guessTableBody');
                        jQuery('#btnGuess').prop('disabled',true);
                    }
                }

            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
                alert("Sorry, request failed due to: " + errorThrown);
            });
            ;
        }

        jQuery(document).ready(function () {
            jQuery('#btnStart').click(function () {
                // var confirmed = confirm('Once start, you will have to wait for another 6 hours for starting the next game. Confirm to start?');
                // if (confirmed) {
                //     ajaxStart();
                // }
				ajaxStart();
            });

            jQuery('#btnGuess').click(function () {
                var guessValue = jQuery('#guess').val();
                console.log(guessValue);
                if (!pattern.test(guessValue)) {
                    alert('Please input 4 digits only.');
                    return;
                }
                else if(hasDuplicates(guessValue)){
                    alert('Please input 4 different digits.');
                    return;
                }
                ajaxGuess(guessValue);
            });

        })

        function hasDuplicates(array){
            var valueSoFar = Object.create(null);

            for (var i = 0; i < array.length; i++){
                var value = array[i];
                if(value in valueSoFar) {
                    return true;
                }

                valueSoFar[value] = true;
            }
        }

        function startRound(roundData) {
            var roundCount = roundData['roundCount'];
            jQuery('#row_toStart').fadeOut({
                duration: 100,
                complete: function () {
                    jQuery('#row_gameRound').fadeIn({
                        duration: 100
                    });
                }
            });
        }

        function reportError(errorData) {
            alert(errorData['result'] + ' ' + errorData['reason']);
        }

        //function showAlert(title, message) {
        //    var x = jQuery("<div class='alert'>Alert</div>");
        //    $("#alertContainer").prepend(x);
        //    x.slideDown(250).delay(3000).slideUp(250);
        //}

    </script>
@stop
