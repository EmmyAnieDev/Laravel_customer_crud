<?php

use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('customers/search', [CustomerController::class, 'search'])->name('customers.search');
Route::get('customers/trash', [CustomerController::class, 'trash'])->name('customers.trash');
Route::delete('customers/trash/{customer}', [CustomerController::class, 'forceDestroy'])->name('customers.force_destroy');
Route::get('customers/restore/{customer}', [CustomerController::class, 'restore'])->name('customers.restore');
Route::resource('customers', CustomerController::class);

