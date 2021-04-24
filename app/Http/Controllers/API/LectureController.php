<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\LectureResource;
use App\Lecture;
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

        $fileName = time().'.'.$request->file->extension();
        $request->file->move(public_path('files/lectures'), $fileName);

        array_pop($data);

        $data += ["file" => $fileName];
        $data += ["image" => "jBJkbK.jpg"];

        $lecture = Lecture::create($data);
        return response(['lecture' => new LectureResource($lecture), 'message' => 'Created successfully'], 201);

    }

    public function show($id)
    {
        $lecture = Lecture::find($id);
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
