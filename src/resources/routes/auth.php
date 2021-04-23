<?php

Route::name('auth.')->group(function () {
    Route::post('/login', 'BearerAuthController@login')->name('login');
    Route::post('/token', 'BearerAuthController@token')->name('token');
});
