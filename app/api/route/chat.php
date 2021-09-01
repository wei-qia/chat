<?php

use think\facade\Route;
Route::group('chat', function(){

    Route::post('test', 'Chat/test');

})->middleware(\app\api\middleware\checkJWT::class, 'api');