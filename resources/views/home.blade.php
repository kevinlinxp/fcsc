@extends('layout')
@section('main')
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8 middle">
            <h1 id="main_title">Foundations of Computer Science Competition</h1>
        </div>
        <div class="col-md-2"></div>
    </div>
    @if (!\App\Http\Controllers\GameController::isGameEnded())
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 middle">
                <div style="font-style:italic; font-size: 16px; color:blue; margin-top:10px;">Ends
                    on: <?php echo env('END_DATE')?></div>
            </div>
            <div class="col-md-2"></div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <h3 class="middle">Enter your Student ID and Go!</h3>

                        <form id="startForm" class="form-horizontal" action="game" method="get">
                            <fieldset>
                                <!--<legend>Legend</legend>-->
                                <div class="form-group">
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" name="studentId"
                                               placeholder="Student ID (Starts with 'a')"
                                               onkeydown="if (event.keyCode == 13) {document.forms[0].submit();}">
                                    </div>
                                    <div class="col-md-4"></div>
                                </div>
                                @if (count($errors) > 0)
                                    <div class="form-group">
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4 alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        <div class="col-md-4"></div>
                                    </div>
                                @endif
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
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8 middle">
                <div style="font-style:italic; font-size: 16px; color:blue; margin-top:10px;">Game Ended</div>
            </div>
            <div class="col-md-2"></div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-3"></div>
        <div id="rankContainer" class="col-md-6 middle">

        </div>
        <div class="col-md-3"></div>
    </div>

@stop

@section('footer_script')
    <script>
        function getRankingList() {
            jQuery.ajax('ranking', {
                method: 'GET',
            }).done(function (jsonData, textStatus, jqXHR) {
                console.log(jsonData);
                jQuery('#rankContainer').empty();
                if (jsonData.length > 0) {
                    jQuery('<h3 class="middle">Ranking</h3>').appendTo('#rankContainer');
                    jQuery('<table class="table"><thead class="middle"><th>#</th><th>Name</th><th>Score</th><th>Date</th></thead><tbody id="rankTableBody"></tbody></table>').appendTo('#rankContainer');
                    for (var index in jsonData) {
                        var studentInfo = jsonData[index];
                        var recordDate = '1970-01-01 00:00:00' == studentInfo.recordDate ? '--' : studentInfo.recordDate;
                        jQuery('<tr><td>' + (parseInt(index) + 1) + '</td><td>' + studentInfo.firstName + ' ' + studentInfo.lastName + '</td><td>' + studentInfo.highestMark + '</td><td>' + recordDate + ' </td></tr>').appendTo('#rankTableBody');
                    }
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            });
        }

        jQuery(document).ready(function () {
            getRankingList();
        });
    </script>
@stop
