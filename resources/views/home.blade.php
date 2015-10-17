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
                                <div class="col-md-4"></div>
                                <div class="col-md-4">
                                    <input type="text" class="form-control" name="studentId"
                                           placeholder="Student ID (Starts with 'a')">
                                </div>
                                <div class="col-md-4"></div>
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
                <div id="rankContainer" class="col-md-6 middle">

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
                jQuery('#rankContainer').empty();
                if (jsonData.length > 0) {
                    jQuery('<h3 class="middle">Ranking</h3>').appendTo('#rankContainer');
                    jQuery('<table class="table"><thead class="middle"><th>#</th><th>Name</th><th>Score</th><th>Date</th></thead><tbody id="rankTableBody"></tbody></table>').appendTo('#rankContainer');
                    for (var index in jsonData) {
                        var studentInfo = jsonData[index];
                        jQuery('<tr><td>'+index+'</td><td>' + studentInfo.firstName + ' ' + studentInfo.lastName + '</td><td>' + studentInfo.highestMark + '</td><td>' + studentInfo.lastPlayed + ' </td></tr>').appendTo('#rankTableBody');
                    }
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
