<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/testingRouting', function () {
    return view('RoutingTest');
});
