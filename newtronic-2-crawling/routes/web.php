<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExchangeRateController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/crawl-exchange-rates', [ExchangeRateController::class, 'crawlAndStore']);


