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
Route::get('/user_tasks/{user_id}', 'TaskController@get_user_tasks');
Route::prefix('/task')->group(function () {
    Route::get('/', 'TaskController@index');
    Route::post('/', 'TaskController@store');
    Route::prefix('/{task_id}')->group(function () {
        Route::get('/', 'TaskController@show');
        Route::patch('/', 'TaskController@update');
        Route::delete('/', 'TaskController@destroy');
        Route::post('/', 'SubTaskController@store');
    });
});
Route::prefix('/project')->group(function () {
    Route::get('/', 'ProjectController@index');
    Route::post('/', 'ProjectController@store');
    Route::prefix('/{project_id}')->group(function () {
        Route::get('/', 'ProjectController@show');
        Route::patch('/', 'ProjectController@update');
        Route::delete('/', 'ProjectController@destroy');
        Route::prefix('/list')->group(function () {
            Route::get('/', 'TaskListController@index');
            Route::post('/', 'TaskListController@store');
            Route::prefix('/{list_id}')->group(function () {
                Route::get('/', 'TaskListController@show');
                Route::patch('/', 'TaskListController@update');
                Route::delete('/', 'TaskListController@destroy');
            });
        });
    });
});


// Exception routes
Route::get('exception/index', 'ExceptionController@index');
