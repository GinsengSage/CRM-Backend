<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

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

//Auth
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


Route::group(['middleware' => 'auth:api'], function() {
    //Disciplines
    Route::prefix('disciplines')->group(function () {
        Route::get('/', 'API\DisciplineController@index');
        Route::get('/getTeacher/{id}', 'API\DisciplineController@getTeacher');
        Route::get('/getIdByLectureId/{lectureId}', 'API\DisciplineController@getIdByLectureId');
        Route::get('/getIdByTaskId/{taskId}', 'API\DisciplineController@getIdByTaskId');
        Route::get('/getName/{id}', 'API\DisciplineController@getName');
//    Route::get('articles/{article}', 'ArticleController@show');
//    Route::post('articles', 'ArticleController@store');
//    Route::put('articles/{article}', 'ArticleController@update');
//    Route::delete('articles/{article}', 'ArticleController@delete');
    });
    //User
    Route::prefix('users')->group(function () {
        Route::get('/', 'API\UserController@index');
        Route::get('getDisciplines', 'API\UserController@getDisciplines');
        Route::get('getLectures/{disciplineId}', 'API\UserController@getLectures');
        Route::get('getTasks', 'API\UserController@getTasks');
    });
    //Lectures
    Route::prefix('lectures')->group(function () {
        Route::get('getLecture/{id}', 'API\LectureController@show');
        Route::post('createLecture', 'API\LectureController@store');
    });
    //Tasks
    Route::prefix('tasks')->group(function () {
        Route::get('getTask/{id}', 'API\TaskController@show');
        Route::get('getDisciplineTasks/{disciplineId}', 'API\TaskController@getDisciplineTasks');
        Route::post('createTask', 'API\TaskController@store');
        Route::delete('removeTask', 'API\TaskController@destroy');
    });
});

