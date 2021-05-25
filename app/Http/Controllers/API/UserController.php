<?php

namespace App\Http\Controllers\API;

use App\Discipline;
use App\Http\Controllers\DocxController;
use App\Http\Resources\DisciplineResource;
use App\Http\Resources\LectureResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Task;
use App\User;
use App\UserTask;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PhpParser\Node\Expr\Cast\Object_;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response([ 'users' => UserResource::collection($users), 'message' => 'Retrieved successfully'], 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getDisciplines(){
        $user = User::find(auth()->user()->id);
        $userDisciplines = $user->disciplines;
        $disciplines = collect();
        foreach ($userDisciplines as $userDiscipline){
             $disciplines->push($userDiscipline->discipline);
        }
        return response(['userDisciplines' => DisciplineResource::collection($disciplines)]);
    }

    public function getLectures($disciplineId){
        $discipline = Discipline::find($disciplineId);
        $disciplineLectures = $discipline->disciplineLectures;

        return response(['userLectures' => LectureResource::collection($disciplineLectures)]);
    }

    public function getTasks(){
        $user = User::find(auth()->user()->id);
        $tasks = Task::join('user_tasks', 'user_tasks.task_id', '=', 'tasks.id')
            ->join('disciplines', 'tasks.discipline_id', '=', 'disciplines.id')
            ->where('user_tasks.user_id', $user->id)
            ->get(['tasks.*', 'disciplines.name as disciplineName', 'user_tasks.status', 'user_tasks.score', 'user_tasks.file']);

        return response(['userTasks' => TaskResource::collection($tasks)]);
    }

    public function  getStudentsByTeacher(){
        $id = auth()->user()->id;
        $teacher = User::find($id);
        $teacherDisciplines = $teacher->disciplines;
        $students = collect();
        foreach ($teacherDisciplines as $teacherDiscipline){
            $discipline = $teacherDiscipline->discipline;
            $disciplineStudents = $discipline->disciplineUsers;
            foreach ($disciplineStudents as $disciplineStudent){
                $student = $disciplineStudent->user;
                if($student->status === 'Student'){
                    $students->push($student);
                }
            }
        }
        return response(['students' => UserResource::collection($students)]);
    }

    public function getNotifications(){
        $user = User::find(auth()->user()->id);
        $notifications = $user->notifications;
        return response(['userNotifications' => TaskResource::collection($notifications)]);
    }
}
