<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\UserMeProjectsCollection;
use App\Http\Resources\UserMeProjectsResource;

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

        return response() -> json([
            'users' => UserResource::collection($users)
        ], 200);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request -> validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string',
        ]);

        $user = new User([
            'name' => $request -> name,
            'email' => $request -> email,
            'password' => $request -> password,

        ]);

        $user -> save();

        return response() -> json([
            'message' => 'Projeto criado com sucesso!'
        ], 201);


    }

    public function storeExperience (Request $request, $id){
        $request -> validate([
            'experience_name' => 'required|array',
        ]);

       User::findOrFail($id)->experiences()->sync($request->experience_name);

        return response() -> json([
            'message' => 'Experiência adicionada com sucesso!'
        ], 201);
    }

   /*  public function getVacancies(Request $request, $id){

        $user = User::findOrFail($id);
        $experiences = $user->experiences;

        $vacancies = $experiences->map(function($experience){
            return ProjectResource::collection($experience->projects);
        })->flatten()->unique('id');

        return response() -> json([
            'vacancies' => $vacancies
        ], 200);


    } */

    public function getVacancies(Request $request, $id){

        $user = User::findOrFail($id);
        $experiences = $user->experiences;

        $vacancies = $experiences->map(function($experience) use ($user){
            return ProjectResource::collection($experience->projects)
                ->filter(function ($project) use ($user) {
                    return $project->user_id !== $user->id;
                });
        })->flatten()->unique('id');

        return response()->json([
            'vacancies' => $vacancies
        ], 200);
    }

    public function getCandidatesFromProjects(){
        $user = auth()->user();
        $projects = $user->projects;

        $projectsCandidates= UserMeProjectsResource::collection($projects);

        return response() -> json($projectsCandidates, 200);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $user = User::findOrFail($id);

        return response() -> json([
            'user' => new UserResource($user)
        ], 200);

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

        $user = User::findOrFail($id);

        $request -> validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email,'.$user -> id ,
            'password' => 'required|string',
        ]);

        $user -> update([
            'name' => $request -> name,
            'email' => $request -> email,
            'password' => Hash::make($request -> password),

        ]);

        return response() -> json([
            'message' => 'Usuário atualizado com sucesso!'
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $user = User::findOrFail($id);

        $user -> delete();

        return response() -> json([
            'message' => 'Usuário deletado com sucesso!'
        ], 200);

    }
}
