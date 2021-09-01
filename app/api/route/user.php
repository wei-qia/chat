<?php
use think\facade\Route;

Route::group('user', function(){
    Route::post('register', 'User/register')
        ->validate(\app\validate\api\User::class,'register');
    Route::post('login', 'User/login')
        ->validate(\app\validate\api\User::class,'login');

    Route::group(function(){
        Route::get('logout', 'User/logout');
        Route::post('addFriend', 'User/addFriend')
            ->validate(\app\validate\api\User::class,'addFriend');
        Route::post('handleFriend', 'User/handleFriend')
            ->validate(\app\validate\api\User::class,'handleFriend');
    })->middleware(\app\api\middleware\checkJWT::class, 'api');

});
