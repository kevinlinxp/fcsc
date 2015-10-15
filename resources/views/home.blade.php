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
        <div class="col-md-6">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-8">
                    <h3 class="middle">Ranking</h3>
                    <ol>
                        <li>Young, 46, 2015-10-11 20:21:34</li>
                        <li>Younger, 42, 2015-10-08  08:05:55</li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-md-6" style="border-left: 1px solid #849aa5;">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="middle">Enter your Student ID and Go!</h3>
                    <form class="form-horizontal" action="/game" method="post">
                        <fieldset>
                            <!--<legend>Legend</legend>-->
                            <div class="form-group">
                                <div class="col-md-2"></div>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="studentId" placeholder="Student ID">
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                            <div class="form-group">
                                <div class="col-md-12 middle">
                                    <button type="submit" class="btn btn-primary">Go!</button>
                                </div>
                            </div>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        </fieldset>
                    </form>
                </div>
                <div class="col-md-4"></div>
            </div>
        </div>
    </div>
@stop