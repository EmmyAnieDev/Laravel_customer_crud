<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('customers/search', [CustomerController::class, 'search'])->name('customers.search');
Route::resource('customers', CustomerController::class);

