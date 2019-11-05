<?php

Route::name('auth.')->group(function () {
    Route::post('/login', 'AuthController@login')->name('login');
    Route::post('/token', 'AuthController@token')->name('token');
});
