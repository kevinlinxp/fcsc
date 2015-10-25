<?php

Route::get('/', 'PageController@home');

Route::get('/game', 'GameController@toGame');

Route::post('/start', 'GameController@asyncStartGame');

Route::post('/guess', 'GameController@asyncGuess');

Route::get('/ranking', 'GameController@asyncRanking');

//Route::get('/', function () {
//    return view('pages/welcome');
//});