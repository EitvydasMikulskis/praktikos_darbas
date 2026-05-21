<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
});

Route::get('/create-client', function () {
    return view('create-client');
});

Route::get('/create-product', function () {
    return view('create-product');
});

Route::get('/new-invoice', function () {
    return view('new-invoice');
});

Route::get('/invoice-list', function () {
    return view('invoice-list');
});

Route::get('/pdf-export', function () {
    return view('pdf-export');
});

Route::get('/invoice-send', function () {
    return view('invoice-send');
});

