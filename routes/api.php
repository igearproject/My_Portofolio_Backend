<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PagesController;
use App\Http\Controllers\ComponentsController;
use App\Http\Controllers\ListComponentsController;
use App\Http\Controllers\EmailListController;
use App\Http\Controllers\MessageListController;
use App\Http\Controllers\ImageController;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/auth/login',[AuthController::class,'login']);
Route::post('/auth/register',[AuthController::class,'register']);

Route::get('page/{url}',[PagesController::class,'showPage']);
Route::get('pages-publish',[PagesController::class,'getAllPublish']);

Route::group(['middleware'=>['auth:sanctum']],function(){
    Route::get('/auth/me',function(Request $request){
        return auth()->user();
    });
    Route::post('auth/logout',[AuthController::class,'logout']);
    
    Route::get('pages',[PagesController::class,'getAll']);
    Route::post('pages',[PagesController::class,'add']);
    Route::get('pages/{id}',[PagesController::class,'show']);
    Route::put('pages/{id}',[PagesController::class,'edit']);
    Route::delete('pages/{id}',[PagesController::class,'destroy']);
    
    Route::get('components',[ComponentsController::class,'getAll']);
    Route::post('components',[ComponentsController::class,'add']);
    Route::get('components/{id}',[ComponentsController::class,'show']);
    Route::put('components/{id}',[ComponentsController::class,'edit']);
    Route::delete('components/{id}',[ComponentsController::class,'destroy']);
    
    Route::get('list-components',[ListComponentsController::class,'getAll']);
    Route::post('list-components',[ListComponentsController::class,'add']);
    Route::get('list-components/{id}',[ListComponentsController::class,'show']);
    Route::put('list-components/{id}',[ListComponentsController::class,'edit']);
    Route::delete('list-components/{id}',[ListComponentsController::class,'destroy']);
    Route::put('list-components/{id}/order-number',[ListComponentsController::class,'changeOrderNumber']);
    
    Route::get('email-list',[EmailListController::class,'getAll']);
    Route::get('email-list/{id}',[EmailListController::class,'getOne']);
    Route::put('email-list/{id}',[EmailListController::class,'edit']);
    Route::delete('email-list/{id}',[EmailListController::class,'destroy']);
    Route::post('email-list',[EmailListController::class,'add']);
    
    Route::get('message-list',[MessageListController::class,'getAll']);
    Route::get('message-list/{id}',[MessageListController::class,'getOne']);
    Route::put('message-list/{id}',[MessageListController::class,'edit']);
    Route::delete('message-list/{id}',[MessageListController::class,'destroy']);
    
    Route::get('images',[ImageController::class,'getAll']);
    Route::post('images',[ImageController::class,'uploadData']);
    Route::get('images/{id}',[ImageController::class,'getOne']);
    Route::put('images/{id}',[ImageController::class,'edit']);
    Route::delete('images/{id}',[ImageController::class,'destroy']);
});


Route::post('email-list/{id}/{encrypToken}/{messageId}',[EmailListController::class,'verification']);

Route::post('message-list',[MessageListController::class,'add']);