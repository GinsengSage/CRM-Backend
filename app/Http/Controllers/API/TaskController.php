<?php

namespace App\Http\Controllers\API;

use App\Discipline;
use App\Http\Controllers\DocxController;
use App\Http\Resources\TaskResource;
use App\Notification;
use App\Task;
use App\User;
use App\UserTask;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function index()
    {

    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
            'file' => 'required|mimes:docx'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $fileName = time().'.'.$request->file->extension();
        $request->file->move(public_path('files/tasks'), $fileName);

        array_pop($data);

        $data += ['file' => $fileName];

        $task = Task::create($data);

        $discipline = Discipline::find($data['discipline_id']);
        $disciplineUsers = $discipline->disciplineUsers;

        foreach ($disciplineUsers as $disciplineUser){
            if($disciplineUser->user->status === 'Student'){
                $userTask = UserTask::create([
                    'user_id' => $disciplineUser->user_id,
                    'task_id' => $task->id,
                    'status' => 'In process',
                    'score' => 0,
                    'file' => '-'
                ]);
            }else{
                $userTask = UserTask::create([
                    'user_id' => $disciplineUser->user_id,
                    'task_id' => $task->id,
                    'status' => '-',
                    'score' => 0,
                    'file' => '-'
                ]);
            }
        }

        foreach ($disciplineUsers as $disciplineUser){
            if($disciplineUser->user->status === 'Student'){
                $now = Carbon::now();
                $notification = Notification::create([
                    'name' => 'New task (' . $discipline->name . ')',
                    'discipline_id' => $discipline->id,
                    'user_id' => $disciplineUser->user->id,
                    'task_id' => 0,
                    'student_id' => 0,
                    'text' => "New task was added.\n Task name: " . $task->name . '.',
                    'checked' => false,
                    'rated' => true,
                    'date' => $now->toDateTimeString()
                ]);
            }
        }

        return response(['task' => new TaskResource($task), 'message' => 'Created successfully'], 201);

    }

    public function show($id)
    {
        $task = Task::find($id);

        $file = public_path("/files/tasks/$task->file");

        $docObj = new DocxController($file);
        $docText= $docObj->convertToText();
        $task->text = $docText;

        return response(['task' => new TaskResource($task), 'message' => 'Retrieved successfully'], 200);
    }

    public function handOverTask(Request $request)
    {
        $fileName = time().'.'.$request->file->extension();
        $request->file->move(public_path('files/students-tasks'), $fileName);

        $tasks = UserTask::where([
            ['user_id', '=', auth()->user()->id],
            ['task_id', '=', $request->taskId],
        ])->get();

        $userTask = $tasks[0];

        $userTask->file = $fileName;
        $userTask->status = 'Completed';
        $userTask->save();

        $task = Task::find($request->taskId);
        $disciplineId = $task->discipline_id;
        $discipline = Discipline::find($disciplineId);

        $file = public_path("/files/students-tasks/$userTask->file");

        $docObj = new DocxController($file);
        $docText= $docObj->convertToText();

        echo $docText;

        $users = $discipline->disciplineUsers;
        foreach ($users as $disciplineUser){
            if($disciplineUser->user->status === 'Teacher'){
                $now = Carbon::now();
                $notification = Notification::create([
                    'name' => 'Student '. auth()->user()->name . ' (' . auth()->user()->course . '-' .
                        auth()->user()->group . ') ' . 'complete task ' . $task->name,
                    'discipline_id' => $discipline->id,
                    'user_id' => $disciplineUser->user->id,
                    'student_id' => auth()->user()->id,
                    'task_id' => $task->id,
                    'text' => $docText,
                    'checked' => false,
                    'rated' => false,
                    'date' => $now->toDateTimeString()
                ]);
            }
        }

        return response(['message' => 'Success'], 202);
    }

    public function changeRated($id){
        $note = Notification::find($id);
        $note->rated = true;
        $note->save();
    }

    public function rateTask(Request $request)
    {
        $this->changeRated($request->notificationId);

        $tasks = UserTask::where([
            ['user_id', '=', $request->studentId],
            ['task_id', '=', $request->taskId],
        ])->get();

        $userTask = $tasks[0];

        $userTask->score = $request->score;
        $userTask->save();

        $student = User::find($request->studentId);

        if($student->average_score === 0.0){
            $student->average_score = $request->score;
            $student->save();
        }else{
            $studentTasks = $student->tasks;
            $score = 0;
            $count = 0;
            foreach ($studentTasks as $studentTask){
                if($studentTask->status === 'Completed'){
                    $score += $studentTask->score;
                    $count++;
                }
            }
            $student->average_score = $score / $count;
            $student->save();
        }

        $task = Task::find($request->taskId);
        $discipline = Discipline::find($task->discipline_id);

        $now = Carbon::now();
        $notification = Notification::create([
            'name' => 'New score (' . $discipline->name . ') ',
            'discipline_id' => $discipline->id,
            'user_id' => $student->id,
            'student_id' => 0,
            'task_id' => 0,
            'text' => 'New score (' . $discipline->name . ') for task ' . $task->name . 'is ' . $request->score,
            'checked' => false,
            'rated' => true,
            'date' => $now->toDateTimeString()
        ]);

        return response(['message' => 'Success'], 202);
    }

    public function changeStatus(Request $request, $id){
        echo $request->newStatus;
        $tasks = UserTask::where([
            ['user_id', '=', auth()->user()->id],
            ['task_id', '=', $id],
        ])->get();
        $task = $tasks[0];
        $task->status = $request->newStatus;
        $task->save();

        return response(['message' => 'Success'], 202);
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        $task->delete();
        $tasks = UserTask::where('task_id', '=', $id);
        foreach ($tasks as $task){
            $task->delete();
        }
        return response(['message' => 'Deleted']);
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
