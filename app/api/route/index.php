<?php

use think\facade\Route;
Route::group('index', function(){

    Route::get('index', 'Index/index');

    Route::get('room/:id', 'Index/room')
    ->model(function ($id) {
        $model = new \app\model\User();
        return $model->getUserByIdWithStatus($id);
    });

})->middleware(\app\api\middleware\checkJWT::class, 'api');