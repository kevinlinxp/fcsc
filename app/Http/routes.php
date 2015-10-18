<?php

//Route::get('/', function () {
//    return view('pages/welcome');
//});

//Route::get('/sayHi', 'WelcomeController@index');

Route::get('/', 'PageController@home');

//Route::get('/contact', 'PageController@contact');

//Route::get('/about', 'PageController@about');

Route::get('/game', 'GameController@toGame');

Route::post('/start', 'GameController@asyncStartGame');

Route::post('/guess', 'GameController@asyncGuess');

Route::get('/ranking', 'GameController@asyncRanking');