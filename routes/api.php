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
    
    Route::get('pages',[PagesController::class,'getAll'])->middleware('AdminCheck');
    Route::post('pages',[PagesController::class,'add'])->middleware('AdminCheck');
    Route::get('pages/{id}',[PagesController::class,'show'])->middleware('AdminCheck');
    Route::put('pages/{id}',[PagesController::class,'edit'])->middleware('AdminCheck');
    Route::delete('pages/{id}',[PagesController::class,'destroy'])->middleware('AdminCheck');
    
    Route::get('components',[ComponentsController::class,'getAll'])->middleware('AdminCheck');
    Route::post('components',[ComponentsController::class,'add'])->middleware('AdminCheck');
    Route::get('components/{id}',[ComponentsController::class,'show'])->middleware('AdminCheck');
    Route::put('components/{id}',[ComponentsController::class,'edit'])->middleware('AdminCheck');
    Route::delete('components/{id}',[ComponentsController::class,'destroy'])->middleware('AdminCheck');
    
    Route::get('list-components',[ListComponentsController::class,'getAll'])->middleware('AdminCheck');
    Route::post('list-components',[ListComponentsController::class,'add'])->middleware('AdminCheck');
    Route::get('list-components/{id}',[ListComponentsController::class,'show'])->middleware('AdminCheck');
    Route::put('list-components/{id}',[ListComponentsController::class,'edit'])->middleware('AdminCheck');
    Route::delete('list-components/{id}',[ListComponentsController::class,'destroy'])->middleware('AdminCheck');
    Route::put('list-components/{id}/order-number',[ListComponentsController::class,'changeOrderNumber'])->middleware('AdminCheck');
    
    Route::get('email-list',[EmailListController::class,'getAll'])->middleware('AdminCheck');
    Route::get('email-list/{id}',[EmailListController::class,'getOne'])->middleware('AdminCheck');
    Route::put('email-list/{id}',[EmailListController::class,'edit'])->middleware('AdminCheck');
    Route::delete('email-list/{id}',[EmailListController::class,'destroy'])->middleware('AdminCheck');
    Route::post('email-list',[EmailListController::class,'add'])->middleware('AdminCheck');
    
    Route::get('message-list',[MessageListController::class,'getAll'])->middleware('AdminCheck');
    Route::get('message-list/{id}',[MessageListController::class,'getOne'])->middleware('AdminCheck');
    Route::put('message-list/{id}',[MessageListController::class,'edit'])->middleware('AdminCheck');
    Route::delete('message-list/{id}',[MessageListController::class,'destroy'])->middleware('AdminCheck');
    
    Route::get('images',[ImageController::class,'getAll'])->middleware('AdminCheck');
    Route::post('images',[ImageController::class,'uploadData'])->middleware('AdminCheck');
    Route::get('images/{id}',[ImageController::class,'getOne'])->middleware('AdminCheck');
    Route::put('images/{id}',[ImageController::class,'edit'])->middleware('AdminCheck');
    Route::delete('images/{id}',[ImageController::class,'destroy'])->middleware('AdminCheck');
});


Route::post('email-list/{id}/{encrypToken}/{messageId}',[EmailListController::class,'verification']);

Route::post('message-list',[MessageListController::class,'add']);