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

Route::prefix('/user')->group(function (){
    Route::middleware('auth:api')->post('/register', 'UserController@store');
    Route::middleware('auth:api')->post('/update', 'UserController@update');
    Route::middleware('auth:api')->post('/login', 'TaskListController@login');
    Route::middleware('auth:api')->post('/logout', 'TaskListController@logout');
});

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

// Exception routes
<<<<<<< Updated upstream
Route::get('exception/index', 'ExceptionController@index');
=======
// Route::get('exception/index', 'ExceptionController@index');
>>>>>>> Stashed changes
