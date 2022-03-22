<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

use CodeSinging\PinAdmin\Foundation\Admin;
use CodeSinging\PinAdmin\Controllers;
use Illuminate\Support\Facades\Route;

// 需要认证授权的路由
Admin::routeGroup(function (){

    Route::get('/', [Controllers\IndexController::class, 'index'])->name(Admin::routeName('index.index'));
    Route::get('auth/user', [Controllers\AuthController::class, 'user'])->name(Admin::routeName('auth.user'));
    Route::post('auth/logout', [Controllers\AuthController::class, 'logout'])->name(Admin::routeName('auth.logout'));

});

// 不需要认证授权的路由
Admin::routeGroup(function (){

    Route::get('auth', [Controllers\AuthController::class, 'index'])->name(Admin::routeName('auth.index'));
    Route::post('auth/login', [Controllers\AuthController::class, 'login'])->name(Admin::routeName('auth.login'));

}, false);
