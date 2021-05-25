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
    });
    //User
    Route::prefix('users')->group(function () {
        Route::get('/', 'API\UserController@index');
        Route::get('getDisciplines', 'API\UserController@getDisciplines');
        Route::get('getLectures/{disciplineId}', 'API\UserController@getLectures');
        Route::get('getTasks', 'API\UserController@getTasks');
        Route::get('getNotifications', 'API\UserController@getNotifications');
        Route::get('getStudentsByTeacher', 'API\UserController@getStudentsByTeacher');
    });
    //Lectures
    Route::prefix('lectures')->group(function () {
        Route::get('getLecture/{id}', 'API\LectureController@show');
        Route::post('createLecture', 'API\LectureController@store');
        Route::delete('removeLecture/{id}', 'API\LectureController@destroy');
    });
    //Tasks
    Route::prefix('tasks')->group(function () {
        Route::get('getTask/{id}', 'API\TaskController@show');
        Route::get('getDisciplineTasks/{disciplineId}', 'API\TaskController@getDisciplineTasks');
        Route::post('createTask', 'API\TaskController@store');
        Route::post('handOverTask', 'API\TaskController@handOverTask');
        Route::post('rateTask', 'API\TaskController@rateTask');
        Route::patch('changeStatus/{id}', 'API\TaskController@changeStatus');
        Route::delete('removeTask/{id}', 'API\TaskController@destroy');
    });
    //Notifications
    Route::prefix('notifications')->group(function () {
        Route::get('getNotification/{id}', 'API\NotificationController@show');
        Route::post('changeStatus', 'API\NotificationController@changeStatus');
        Route::delete('removeNotification/{id}', 'API\NotificationController@destroy');
    });
});

