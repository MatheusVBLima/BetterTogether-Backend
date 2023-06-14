<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Resources\UserResource;

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

/* Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
 */

Route::post('signup', [App\Http\Controllers\AuthController::class, 'signup']);
Route::post('signin', 'App\Http\Controllers\AuthController@signin') -> name('login');
Route::post('forgotPassword', [App\Http\Controllers\AuthController::class, 'forgotPassword']);
Route::post('resetPassword', [App\Http\Controllers\AuthController::class, 'resetPassword']);

Route::middleware('auth:api')->group(function() {
    Route::get("/me", function (Request $request) {
        return response(new UserResource($request->user()), 200);
    });
    Route::get("/me/projects", 'App\Http\Controllers\UserController@getCandidatesFromProjects');

    //add experiencia no projeto passando o id do projeto
    Route::post('projects/{id}/experiences', 'App\Http\Controllers\ProjectController@storeExperience');
    //acha os candidatos para o projeto do id passado
    Route::get('projects/{id}/candidates', 'App\Http\Controllers\ProjectController@getCandidates');
    Route::apiResource('projects', 'App\Http\Controllers\ProjectController');
    // add experiencia no usuario passando o id do usuario
    Route::post('users/{id}/experiences', 'App\Http\Controllers\UserController@storeExperience');
    // acha as vagas para o usuario do id passado
    Route::get('users/{id}/vacancies', 'App\Http\Controllers\UserController@getVacancies');
    Route::apiResource('users', 'App\Http\Controllers\UserController');
    Route::apiResource('experiences', 'App\Http\Controllers\ExperienceController')->except(['show', 'update']);
    // mudar nome ou password
    Route::post('changeUserCredential', [App\Http\Controllers\AuthController::class, 'changeUserCredential']);
});








