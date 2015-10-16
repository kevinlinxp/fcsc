<?php

//Route::get('/', function () {
//    return view('pages/welcome');
//});

//Route::get('/sayHi', 'WelcomeController@index');

Route::get('/', 'PageController@home');

Route::get('/contact', 'PageController@contact');

Route::get('/about', 'PageController@about');

Route::get('/prepare', 'GameController@prepare');

Route::post('/start', 'GameController@asyncStartGame');

Route::post('/guess', 'GameController@asyncGuess');