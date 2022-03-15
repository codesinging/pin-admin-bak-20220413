<?php
/**
 * Author: codesinging <codesinging@gmail.com>
 * Github: https://github.com/codesinging
 */

use CodeSinging\PinAdmin\Foundation\Admin;
use Illuminate\Support\Facades\Route;

Admin::boot('__DUMMY_NAME__')
    ->defaultRoutes()
    ->routeGroup(function () {

    })->routeGroup(function () {

    }, false);
