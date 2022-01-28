<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class FuncionariosController extends Controller
{
    public function list()
    {
        try{
            $lista = DB::select('select * from funcionarios');
        }catch(QueryException $e){
            return response([
                'message' => $e->errorInfo[2],
            ], 500);
        }

        return response(
            $lista
        );   
    }

    public function add(Request $request)
    {
        $validator = Validator::make([
            'nome' => $request->nome,
            'cpf' => $request->cpf,
            'celular' => $request->celular,
            'email' => $request->email
        ], [
            'nome' => ['required', 'string', 'min:6'],
            'cpf' => ['required', 'string', 'digits:11'],
            'celular' => ['required', 'string', 'digits:11'],
            'email' => ['required', 'string', 'email']
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Erro ao validar campos',
                'erros' => $validator->errors()->messages()
            ], 400);
        }
        
        try{
            DB::insert('insert into funcionarios
            (nome, cpf, celular, email) values (?, ?, ?, ?)', 
            [$request->nome, $request->cpf, 
            $request->celular, $request->email]);
        }catch(QueryException $e){
            return response([
                'message' => $e->errorInfo[2],
            ], 500);
        }

        return response(
            'Funcionário adicionado com sucesso.'
        );   
    }

    public function delete(Request $request)
    {
        $validator = Validator::make([
            'id' => $request->id,
        ], [
            'id' => ['required', 'integer'],
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Erro ao validar campos',
                'erros' => $validator->errors()->messages()
            ], 400);
        }

        try{
            $return = DB::delete(DB::raw("DELETE FROM funcionarios WHERE id = {$request->id}"));
        }catch(QueryException $e){
            return response([
                'message' => $e->errorInfo[2],
            ], 500);
        }

        if($return==0){
            return response(
                'Funcionário não encontrado.'
            );
        }

        return response(
            'Funcionário deletado com sucesso.'
        );   
    }

    public function update(Request $request)
    {
        $validator = Validator::make([
            'id' => $request->id,
            'alter' => $request->alter
        ], [
            'id' => ['required', 'integer'],
            'alter' => ['required', 'json']
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Erro ao validar campos',
                'erros' => $validator->errors()->messages()
            ], 400);
        }

        $decodedAlter = json_decode( $request->alter );

        $decodedAlterArray = [];
        while ($partAlter = current($decodedAlter)) {
            $decodedAlterArray[key($decodedAlter)] = $partAlter; 
            next($decodedAlter);
        }
        
        try{
            $return = DB::table('funcionarios')->where('id',$request->id)->update($decodedAlterArray);
        }catch(QueryException $e){
            return response([
                'message' => $e->errorInfo[2],
            ], 500);
        }

        if($return==0){
            return response(
                'Funcionário não encontrado.'
            );
        }

        return response(
            'Funcionário alterado com sucesso.'
        );   
    }
}
