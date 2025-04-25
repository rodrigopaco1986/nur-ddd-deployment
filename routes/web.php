<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::webhooks('webhook-github-push-event', 'github-push-event');
