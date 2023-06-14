<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\ForgotPasswordMail;




class AuthController extends Controller {
    public function signup(Request $request){

        $request -> validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required'
        ]);

        $user = new User([
            'name' => $request -> name,
            'email' => $request -> email,
            'password' => Hash::make($request -> password)
        ]);

        $user -> save();

        return response() -> json([
            'message' => 'Usuário criado com sucesso!'
        ], 201);

    }

    public function signin(Request $request){
        $user = User::where('email', $request -> email) -> first();

        if(!$user){
            return response() -> json([
                'message' => 'Usuário não encontrado!'
            ], 401);
        }

        if(!Hash::check($request -> password, $user -> password)){
            return response() -> json([
                'message' => 'Senha errada!'
            ], 401);
        }

        return response() -> json([
            'token' => $user -> createToken('BT')->accessToken
        ], 201);
    }

    public function changeUserCredential(Request $request){

            $request -> validate([
                'name' => 'string',
                'password' => 'string|min:6|max:256 '
            ]);

            $user = auth() -> user();

            if ($request -> name != null){
                $user -> name = $request -> name;
            }

            if ($request -> password != null){
                $user -> password = Hash::make($request -> password);
            }

            $user -> save();

            return response() -> json([
                'message' => 'Atributo alterado com sucesso!'
            ], 201);
    }

    public function forgotPassword(Request $request){

        $request -> validate([
            'email' => "required|email|min:6|max:256",

        ]);

        $user = User::where('email', $request -> email) -> first();


        if(!$user){
            return response() -> json([
                'message' => 'Usuário não encontrado!'
            ], 401);
        }

        $token = Str::random(60).$user['id'];

        $user -> token = $token;

        $user -> save();

        Mail::send(new ForgotPasswordMail($user,$token));

        return response() -> json([
            'message' => 'Solicitação de troca de senha enviada!'
        ], 201);
    }

    public function resetPassword(Request $request){
            $request -> validate([
                'token' => "required|string|min:6|max:256",
                'password' => "required|string|min:6|max:256|confirmed",
            ]);

            if ($token !== null) {
                $user = User::where('token', $request -> token) -> first();

                if(!$user){
                    return response() -> json([
                        'message' => 'Usuário não encontrado!'
                    ], 401);
                }

                $user -> password = Hash::make($request -> password);

                $user -> token = null;

                $user -> save();

                return response() -> json([
                    'message' => 'Senha alterada com sucesso!'
                ], 201);
            }
    }
}




