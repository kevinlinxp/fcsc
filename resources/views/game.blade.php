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
                beforeSend: function (xhr) {
                    var token = '{{ csrf_token() }}';
                    if (token) {
                        return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }
                },
            }).done(function (jsonData, textStatus, jqXHR) {
                // TODO
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
                alert("Sorry, request failed due to: " + errorThrown);
            });;
        }

        jQuery(document).ready(function () {
            jQuery('#btnStart').click(function () {
                var confirmed = confirm('Once start, you will have to wait for another 6 hours for starting the next game. Confirm to start?');
                if (confirmed) {
                    ajaxStart();
                }
            });

            jQuery('#btnGuess').click(function () {
                var guessValue = jQuery('#guess').val();
                console.log(guessValue);
                if (!pattern.test(guessValue)) {
                    alert('Please input 4 digits only.');
                    return;
                }
                ajaxGuess(guessValue);
            });

        })

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

            jQuery('#tempSecret').text(roundData['secret']);

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