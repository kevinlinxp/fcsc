@extends('layout')
@section('main')
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <h1 id="main_title">Foundations of Computer Science Competition</h1>
        </div>
        <div class="col-md-2"></div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <h3 class="middle">Enter your Student ID and Go!</h3>
                    <form class="form-horizontal" action="/prepare" method="get">
                        <fieldset>
                            <!--<legend>Legend</legend>-->
                            <div class="form-group">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" name="studentId"
                                           placeholder="Student ID (Starts with 'a')">
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 middle">
                                    <button type="submit" class="btn btn-primary">Go!</button>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="col-md-2"></div>
            </div>
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-6 middle">
                    <h3 class="middle">Ranking</h3>
                    <ol id="rankingList">
                    </ol>
                </div>
                <div class="col-md-3"></div>
            </div>
        </div>
    </div>
@stop

@section('footer_script')
    <script>
        function getRankingList() {
            jQuery.ajax('ranking', {
                method: 'GET',
            }).done(function (jsonData, textStatus, jqXHR) {
                jQuery('#rankingList').empty();
                for(var index in jsonData) {
                    var studentInfo = jsonData[index];
                    console.log(jsonData[index]);
                    jQuery('<li>'+studentInfo.firstName + ' ' + studentInfo.lastName +', Highest Mark: <b>'+studentInfo.highestMark +'</b>, Date: '+ studentInfo.lastPlayed +' </li>').appendTo('#rankingList');
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            });
            ;
        }

        jQuery(document).ready(function () {
            getRankingList();
        });
    </script>
@stop