<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;


class AccessController extends Controller
{
    public function login(Request $request)
    {
        if(!Auth::attempt($request->only('email', 'password'))){
            return response([
                'message' => "Credenciais inválidas"
            ], 500);
        }

        $user = Auth::user();
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 24);

        return response([
            'message' => "Sucesso",
        ], 200)->withCookie($cookie);
    } 

    public function register(Request $request)
    {
        $validator = Validator::make([
            'nome' => $request->nome,
            'email' => $request->email,
            'senha' => $request->senha
        ], [
            'nome' => ['required', 'string', 'min:8'],
            'email' => ['required', 'string', 'email'],
            'senha' => ['required', 'string', 'min:8']
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Erro ao validar campos',
                'erros' => $validator->errors()->messages()
            ], 400);
        }

        try{
            $user = new User();
            $user->password = Hash::make($request->senha);
            $user->email = $request->email;
            $user->name = $request->nome;
            $user->save();
        }catch(QueryException $e){
            return response([
                'message' => $e->errorInfo[2],
            ], 500);
        }

        return response('Usuário criado');
    } 
}

