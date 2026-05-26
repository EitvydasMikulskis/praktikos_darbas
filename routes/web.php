<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InvoiceController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/create-client', function () {
    return view('create-client');
});
Route::get('/create-client', [ClientController::class, 'index']);
Route::post('/create-client', [ClientController::class, 'store']);

Route::get('/client-list', [ClientController::class, 'list']);
Route::delete('/client-delete/{id}', [ClientController::class, 'delete']);
Route::put('/client-update/{id}', [ClientController::class, 'update']);

Route::get('/create-product', function () {
    return view('create-product');
});
Route::get('/create-product', [ProductController::class, 'index']);
Route::post('/create-product', [ProductController::class, 'store']);

Route::get('/product-list', [ProductController::class, 'list']);
Route::put('/product-update/{id}', [ProductController::class, 'update']);
Route::delete('/product-delete/{id}', [ProductController::class, 'delete']);

Route::get('/new-invoice', [InvoiceController::class, 'create']);
Route::post('/new-invoice', [InvoiceController::class, 'store']);
Route::get('/invoice/{id}', [InvoiceController::class, 'show']);

Route::get('/invoice-list', function () {
    return view('invoice-list');
});

Route::get('/pdf-export', function () {
    return view('pdf-export');
});

Route::get('/invoice-send', function () {
    return view('invoice-send');
});

