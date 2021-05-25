<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\DocxController;
use App\Http\Resources\LectureResource;
use App\Lecture;
use App\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class LectureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            'name' => 'required',
            'date' => 'required',
            'file' => 'required|mimes:docx'
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error']);
        }

        $fileName = time() . '.' . $request->file->extension();
        $request->file->move(public_path('files/lectures'), $fileName);

        array_pop($data);

        $data += ["file" => $fileName];

        $lecture = Lecture::create($data);

        $discipline = $lecture->discipline;

        $disciplineUsers = $discipline->disciplineUsers;

        foreach ($disciplineUsers as $disciplineUser){
            if($disciplineUser->user->status === 'Student'){
                $now = Carbon::now();
                $notification = Notification::create([
                    'name' => 'New lecture (' . $discipline->name . ')',
                    'discipline_id' => $discipline->id,
                    'user_id' => $disciplineUser->user->id,
                    'task_id' => 0,
                    'student_id' => 0,
                    'text' => "New lecture was added.\n Lecture name: " . $lecture->name . '.',
                    'checked' => false,
                    'rated' => true,
                    'date' => $now->toDateTimeString()
                ]);
            }
        }

        return response(['lecture' => new LectureResource($lecture), 'message' => 'Created successfully'], 201);

    }

    public function show($id)
    {
        $lecture = Lecture::find($id);

        $file = public_path("/files/lectures/$lecture->file");
        $docObj = new DocxController($file);
        $docText= $docObj->convertToText();
        $lecture->text = $docText;

        return response(['lecture' => new LectureResource($lecture), 'message' => 'Retrieved successfully'], 200);
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        $lecture = Lecture::find($id);
        $lecture->delete();
        return response(['message' => 'Deleted']);
    }


}
