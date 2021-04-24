<?php

namespace App\Http\Controllers\API;

use App\Discipline;
use App\Http\Resources\TaskResource;
use App\Task;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    public function index()
    {

    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        $task = Task::find($id);
        return response(['task' => new TaskResource($task), 'message' => 'Retrieved successfully'], 200);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function getDisciplineTasks($disciplineId){

        $user = User::find(auth()->user()->id);
        $discipline = Discipline::find($disciplineId);

        $tasks = Task::join('user_tasks', 'user_tasks.task_id', '=', 'tasks.id')
            ->join('disciplines', 'tasks.discipline_id', '=', 'disciplines.id')
            ->where('user_tasks.user_id', $user->id)
            ->where('tasks.discipline_id', $discipline->id)
            ->get(['tasks.*', 'disciplines.name as disciplineName', 'user_tasks.status', 'user_tasks.file', 'user_tasks.score']);

        return response(['userTasks' => $tasks]);
    }
}
