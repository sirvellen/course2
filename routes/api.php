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
//Route::middleware('auth:api')->post('/users/logout', 'UserController@logout');]


Route::prefix('/users')->group(function () {
    Route::get('/', 'UserController@index');
    Route::post('/', 'UserController@show');
    Route::post('/register', 'UserController@store');
    Route::patch('/update', 'UserController@update');
    Route::post('/login', 'UserController@login');
    Route::get('/logout/{id}', 'UserController@logout');
});

Route::prefix('/project')->group(function () {
    Route::get('/', 'DeskController@index');
    Route::post('/', 'DeskController@store');
    Route::prefix('/{desk_id}')->group(function () {
        Route::get('/', 'DeskController@show');
        Route::patch('/', 'DeskController@update');
        Route::delete('/', 'DeskController@destroy');
        Route::prefix('/list')->group(function () {
            Route::get('/', 'TaskListController@index');
            Route::post('/', 'TaskListController@store');
            Route::prefix('/{list_id}')->group(function () {
                Route::get('/', 'TaskListController@show');
                Route::patch('/', 'TaskListController@update');
                Route::delete('/', 'TaskListController@destroy');
                Route::prefix('/task')->group(function () {
                    Route::get('/', 'TaskController@index');
                    Route::post('/', 'TaskController@store');
                    Route::prefix('/{task_id}')->group(function () {
                        Route::get('/', 'TaskController@show');
                        Route::patch('/', 'TaskController@update');
                        Route::delete('/', 'TaskController@destroy');
                    });
                });
            });
        });
    });
});

// Exception routes
Route::get('exception/index', 'ExceptionController@index');
