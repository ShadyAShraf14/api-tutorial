<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\User\AuthUserController;
use App\Http\Controllers\Api\CategoriesController;

Route::group(['middleware' => ['api', 'CheckPassword', 'ChangeLanguage']], function () {
    Route::post('get-main-categories', [CategoriesController::class, 'index']);
    Route::post('get-category-byId/', [CategoriesController::class, 'getCategoryById']);
    Route::post('change-category-status', [CategoriesController::class, 'changeStatus']);

    Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('logout',[AuthController::class, 'logout']) -> middleware(['auth.guard:admin-api']);

    });




    Route::group(['prefix' => 'user','namespace'=>'User'],function (){
        Route::post('login',[AuthUserController::class, 'login']) ;
    });


    Route::group(['prefix' => 'user' ,'middleware' => 'auth.guard:user-api'],function (){
        Route::post('profile',function(){
            return  Auth::user(); // return authenticated user data
        }) ;
    });





});























Route::group(['middleware' => ['api', 'CheckPassword', 'ChangeLanguage', 'CheckAdminToken:admin-api']], function () {
    Route::post('offers', [CategoriesController::class, 'index']);
});
