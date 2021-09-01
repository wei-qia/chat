<?php

use think\facade\Route;

Route::group('index', function () {
    Route::get('index', 'Index/index');

    Route::get('room/:id', 'Index/room');
});