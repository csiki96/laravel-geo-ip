<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Middleware\{
    CheckLocation,
};


Route::middleware([CheckLocation::class])->group(function (){
    Route::get('/', function () {
        return view('welcome');
    });
});

