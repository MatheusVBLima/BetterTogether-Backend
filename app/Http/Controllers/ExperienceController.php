<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Experience;
/* use App\Http\Resources\Experience; */

class ExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $experience = Experience::all();

        return response() -> json([
            'experience' => $experience
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
            'experience_name' => 'required|string',
        ]);

        $experience = new Experience([
            'experience_name' => $request -> experience_name
        ]);

        $experience -> save();

        return response() -> json([
            'message' => 'Experiência adicionada com sucesso!'
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   /*  public function show($id)
    {

        $experience = Experience::findOrFail($id);

        return response() -> json([
            'experience' => new Experience($experience)
        ], 200);

    } */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   public function update(Request $request, $id)
    {
        $experience = Experience::findOrFail($id);

        $request -> validate([
            'name' => 'required|string',

        ]);

        $experience -> update([
            'name' => $request -> name,

        ]);

        return response() -> json([
            'message' => 'Experiência atualizada com sucesso!'
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

        $experience = Experience::findOrFail($id);

        $experience -> delete();

        return response() -> json([
            'message' => 'Experiência deletado com sucesso!'
        ], 200);

    }
}
