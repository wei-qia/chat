<?php

use think\facade\Route;

Route::group('user', function () {
    Route::get('register', 'User/register');
    Route::get('login', 'User/login');

});
