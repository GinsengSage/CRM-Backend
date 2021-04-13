<?php

namespace App\Http\Controllers\API;

use App\Discipline;
use App\Http\Controllers\Controller;
use App\Http\Resources\DisciplineResource;
use App\User;
use Illuminate\Http\Request;


class DisciplineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $disciplines = Discipline::all();
        return response([ 'disciplines' => DisciplineResource::collection($disciplines), 'message' => 'Retrieved successfully'], 200);
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
     * @param  \App\Discipline  $discipline
     * @return \Illuminate\Http\Response
     */
    public function show(Discipline $discipline)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Discipline  $discipline
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Discipline $discipline)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Discipline  $discipline
     * @return \Illuminate\Http\Response
     */
    public function destroy(Discipline $discipline)
    {
        //
    }

    public function getTeacher($id)
    {
        $discipline = Discipline::find($id);
        $disciplineUsers = $discipline->disciplineUsers;
        $users = collect();
        foreach ($disciplineUsers as $disciplineUser) {
            $users->push($disciplineUser->user);
        }
        foreach ($users as $user) {
            if ($user->status === 'Teacher') {
                return response(['teacher' => $user, 'message' => 'Retrieved successfully'], 200);;
            }
        }
    }
}
