<?php

use Illuminate\Http\Request;

Route::get('/test', function () {
    return __DIR__ . '/../index.php';
})->name('my route');

//Route::middleware('auth:api')->Route::get('/users', 'UserController@index');
//
//Route::middleware('auth:api')->Route::post('/users/signup', 'UserController@store');
//
//Route::middleware('auth:api')->Route::post('/users/login', 'UserController@login');
//
//Route::middleware('auth:api')->post('/users/logout', 'UserController@logout');
//
//
//Route::prefix('categories')->group(function () {
//    Route::get('/', 'CategoryController@index');
//    Route::get('/{category}', 'CategoryController@show');
//    Route::middleware('auth:api')->post('/', 'CategoryController@store');
//    Route::middleware('auth:api')->patch('/{category}', 'CategoryController@update');
//    Route::middleware('auth:api')->delete('/{category}', 'CategoryController@destroy');
//});
Route::prefix('/{desk_id}')->group(function () {
    Route::prefix('/list')->group(function () {
        Route::get('', 'TaskListController@index');
        Route::post('', 'TaskListController@store');
        Route::prefix('/{list_id}')->group(function () {
            Route::get('', 'TaskListController@show');
            Route::patch('', 'TaskListController@update');
            Route::delete('', 'TaskListController@destroy');
            Route::prefix('/task')->group(function () {
                Route::get('/', 'TaskController@show');
                Route::post('/', 'TaskController@store');
                Route::patch('/{task_id}', 'TaskController@update');
                Route::patch('/{task_id}/done', 'TaskController@done');
                Route::patch('/{task_id}/undone', 'TaskController@undone');
                Route::delete('/{task_id}', 'TaskController@destroy');
            });
        });
    });
});
