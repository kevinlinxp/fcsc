@extends('layout')
@section('main')
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <h1 id="main_title">Hello {{$student['firstName']}} {{$student['lastName']}}</h1>
        </div>
        <div class="col-md-2"></div>
    </div>
@stop