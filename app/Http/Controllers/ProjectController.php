<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\UserResource;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $projects = Project::all();

        return response() -> json([
            'projects' => ProjectResource::collection($projects)
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
            'description' => 'required|string',
            'contact' => 'required|string',
            'user_id' => 'required'
        ]);

        $project = new Project([
            'name' => $request -> name,
            'description' => $request -> description,
            'contact' => $request -> contact,
            'user_id' => $request -> user_id
        ]);

        $project -> save();

        return response() -> json([
            'message' => 'Projeto criado com sucesso!'
        ], 201);


    }

    public function storeExperience (Request $request, $id){
        $request -> validate([
            'experience_name' => 'required|array',
        ]);

       Project::findOrFail($id)->experiences()->sync($request->experience_name);

        return response() -> json([
            'message' => 'Experiência adicionada com sucesso!'
        ], 201);
    }

    public function getCandidates($id){

        $project = Project::findOrFail($id);
        $experiences = $project->experiences;

        $candidates = $experiences->map(function($experience) use ($project){
            $candidates = $experience->users->where('id', '!=', $project->user_id);
            return UserResource::collection($candidates);
        })->flatten()->unique('id');

        return response() -> json([
            'candidates' => $candidates
        ], 200);
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $project = Project::findOrFail($id);

        return response() -> json([
            'project' => new ProjectResource($project)
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

        $project = Project::findOrFail($id);

        $request -> validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'contact' => 'required|string',
            'user_id' => 'required'
        ]);

        $project -> update([
            'name' => $request -> name,
            'description' => $request -> description,
            'contact' => $request -> contact,
            'user_id' => $request -> user_id
        ]);

        return response() -> json([
            'message' => 'Projeto atualizado com sucesso!'
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

        $project = Project::findOrFail($id);

        $project -> delete();

        return response() -> json([
            'message' => 'Projeto deletado com sucesso!'
        ], 200);

    }
}
