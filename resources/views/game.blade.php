@extends('layout')
@section('main')
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
                <div class="col-md-12">
                    <div class="middle" style="font-size:18px">Last played:
                        <span>{{$student['lastPlayed'] == '1970-01-01 00:00:00' ? 'N/A' : $student['lastPlayed']}}</span>
                    </div>
                    <div>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border middle" style="color:blue; width:60px; margin-bottom: 0px;">
                                Note
                            </legend>
                            <ul>
                                <li>You need to play 5 rounds to complete one game.</li>
                                <li>In each round, you will have up to 10 guesses.</li>
                                <li>The more guesses you have used in one round, the fewer points you will receive.</li>
                                <li>If more than {{\App\Game::getDeductRoundPointInterval()}} seconds are spent in one round, your points will be deducted.</li>
                            </ul>
                        </fieldset>
                    </div>
                    <div class="middle">
                        <button id="btnStart" class="btn btn-primary">Start</button>
                    </div>
                </div>
            </div>
            <div id="row_gameRound" class="row" style="display:none">
                <div class="col-md-12 middle"><input id="guess" type="text" name="guess"
                                                     placeholder="Input 4 digits only">
                    <button id="btnGuess" class="btn btn-info">Guess!</button>
                </div>
                <div class="col-md-12 middle">
                    <span id="guessPoints" style="color: blue"></span>
                </div>
                <div class="col-md-12 middle">
                    <table class="table">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Guess</th>
                            <th>Result</th>
                        </tr>
                        </thead>
                        <tbody id="guessTableBody">
                        <tr>
                            <td colspan="3" style="color:blue">Round 1 has started!</td>
                        </tr>
                        </tbody>
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
        var round_limit = 5;
        var guess_limit = 10;

        function ajaxStart() {
            jQuery.ajax('start', {
                method: 'POST',
                //data: {
                //    'studentId': '{{$student['id']}}'
                //},
                //beforeSend: function (xhr) {
                //    var token = '{{ csrf_token() }}';
                //    if (token) {
                //        return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                //    }
                //},
            }).done(function (jsonData, textStatus, jqXHR) {
                switch (jsonData['result']) {
                    case 'roundStarted':
                        startRound(jsonData['roundData']);
                        break;
                    default:
                        alert('unrecognized success result:' + jsonData['result']);
                        break;
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                reportError(jqXHR);
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
                var resultText = jsonData['roundData']['resultText'];
                var guessCount = jsonData['roundData']['guessCount'];
                var totalPoints = jsonData['roundData']['totalPoints'];
                var roundPoints = jsonData['roundData']['roundPoints'];
                var roundCount = jsonData['roundData']['roundCount'];
                var correctness = jsonData['roundData']['correctness'];

                jQuery('#guessPoints').text('Your points received: ' + totalPoints);

                jQuery('<tr><td>' + guessCount + '</td><td>' + jQuery('#guess').val() + '</td><td>' + resultText + '</td></tr>').prependTo('#guessTableBody');
                jQuery('#guess').val('');

                if (correctness) {
                    if (roundCount < round_limit) {
                        jQuery('<tr><td colspan="3" style="color:green">You received ' + roundPoints + ' points in this round!</td></tr>').prependTo('#guessTableBody');
                        jQuery('<tr><td colspan="3" style="color:blue">Round ' + (roundCount + 1) + ' has started!</td></tr>').prependTo('#guessTableBody');
                    } else {
                        jQuery('<tr><td colspan="3" style="color:green">You received ' + roundPoints + ' points in this final round!</td></tr>').prependTo('#guessTableBody');
                        endGame();
                    }
                }
                else if (guessCount % guess_limit == 0) {
                    if (roundCount < round_limit) {
                        jQuery('<tr><td colspan="3" style="color:orange">You have reached guess number limit in this round!</td></tr>').prependTo('#guessTableBody');
                        jQuery('<tr><td colspan="3" style="color:blue">Round ' + (roundCount + 1) + ' has started!</td></tr>').prependTo('#guessTableBody');
                    }
                    else {
                        jQuery('<tr><td colspan="3" style="color:orange">You have reached guess number limit in this final round!</td></tr>').prependTo('#guessTableBody');
                        endGame();
                    }
                }

                @if (\App\Http\Controllers\GameController::isDebug())
                jQuery('<tr><td colspan="3">' + jsonData["roundData"]["secret"] +'</td></tr>').prependTo('#guessTableBody');
                @endif

            }).fail(function (jqXHR, textStatus, errorThrown) {
                reportError(jqXHR);
            });
        }

        function endGame() {
            jQuery('<tr><td colspan="3">You have finished the game! Thank you!</td></tr>').prependTo('#guessTableBody');
            jQuery('#btnGuess').prop('disabled', true);
            jQuery('#guess').prop('disabled', true);
            var homeURL = '{{url('/')}}';
            jQuery('<tr><td colspan="3"><a href="'+homeURL+'" style="font-size:18px">Return to Homepage</a></td></tr>').prependTo('#guessTableBody');
        }

        function guessOnSubmit() {
            var guessValue = jQuery('#guess').val();
            if (!pattern.test(guessValue)) {
                alert('Please input 4 digits only.');
                return;
            }
            else if (hasDuplicates(guessValue)) {
                alert('Please input 4 different digits.');
                return;
            }
            ajaxGuess(guessValue);
        }

        jQuery(document).ready(function () {
            jQuery('#btnStart').click(function () {
                // var confirmed = confirm('Once start, you will have to wait for another 6 hours for starting the next game. Confirm to start?');
                // if (confirmed) {
                //     ajaxStart();
                // }
                ajaxStart();
            });

            jQuery('#btnGuess').click(guessOnSubmit);

            jQuery('#guess').keydown(function (event) {
                if (event.keyCode == 13) {
                    guessOnSubmit();
                }
            });
        })

        function hasDuplicates(array) {
            var valueSoFar = Object.create(null);

            for (var i = 0; i < array.length; i++) {
                var value = array[i];
                if (value in valueSoFar) {
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

        function reportError(jqXHR) {
            @if(\App\Http\Controllers\GameController::isDebug())
                console.log(jqXHR);
                showLaravelErrorStack(jqXHR.responseText);
            @endif
            alert("Sorry, request failed due to: " + jqXHR.status + " - " + jqXHR.statusText);
        }

    </script>
@stop
