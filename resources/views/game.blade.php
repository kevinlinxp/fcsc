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
            <div class="row">
                <div class="col-md-6 middle lead">Round: <span>1</span>/5</div>
                <div class="col-md-6">
                    <button id="btnStart" class="btn btn-primary">Start</button>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
@stop
@section('footer_script')
    <script>
        jQuery(document).ready(function () {
            jQuery('#btnStart').click(function () {
                var confirmed = confirm('Once start, you will have to wait for another 6 hours for starting the next game. Confirm to start?');
                if (confirmed) {
                    jQuery.ajax('start', {
                        method: 'POST',
                        data: {
                            // '_token': '{{ csrf_token() }}',
                            'studentId': '{{$student['id']}}'
                        },
                        beforeSend: function (xhr) {
                            var token = '{{ csrf_token() }}';
                            if (token) {
                                return xhr.setRequestHeader('X-CSRF-TOKEN', token);
                            }
                        },
                    }).done(function (jsonData,textStatus,jqXHR) {
                        console.log(jsonData);
                        startGame();
                    }).fail(function(jqXHR, textStatus, errorThrown ) {
                        console.log();
                        alert( "Request failed: " + errorThrown );
                    });
                }
            });
        })

        function startGame() {
            alert('Game started!');
        }
    </script>
@stop