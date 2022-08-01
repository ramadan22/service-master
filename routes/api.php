<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::middleware(['cors'])->group(function () {
  Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::group(['namespace' => 'Authentication'], function () {
      Route::post('/login', 'Login@index');
    });

    Route::middleware(['checkToken'])->group(function () {
      Route::middleware(['permission'])->group(function () {
        Route::group(['namespace' => 'ManageData'], function () {
          // Service Users
          Route::get('/users', 'UsersController@index');
          Route::post('/users/add', 'UsersController@add');
          Route::post('/users/edit', 'UsersController@edit');
          Route::get('/users/delete', 'UsersController@delete');

          // Service permission
          Route::get('/permission', 'PermissionController@index');
          Route::post('/permission/edit', 'PermissionController@edit');
        });
      });
    });
  });
});
