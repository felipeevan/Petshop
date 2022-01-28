<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class ServicosController extends Controller
{
    public function list()
    {
        try{
            $lista = DB::select('select * from servicos');
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
            'descricao' => $request->descricao,
            'preco_uni' => $request->preco_uni,
        ], [
            'nome' => ['required', 'string', 'min:2'],
            'descricao' => ['required', 'string', 'max:300'],
            'preco_uni' => ['required', 'numeric', 'between:0,999999.99'],
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Erro ao validar campos',
                'erros' => $validator->errors()->messages()
            ], 400);
        }
        
        try{
            DB::insert('INSERT into servicos
            (nome, descricao, preco_uni) values (?, ?, ?)', 
            [$request->nome, $request->descricao, $request->preco_uni]);
        }catch(QueryException $e){
            return response([
                'message' => $e->errorInfo[2],
            ], 500);
        }

        return response(
            'Serviço adicionado com sucesso.'
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
            $return = DB::delete(DB::raw("DELETE FROM servicos WHERE id = {$request->id}"));
        }catch(QueryException $e){
            return response([
                'message' => $e->errorInfo[2],
            ], 500);
        }
        
        if($return==0){
            return response(
                'Serviço não encontrado.'
            );
        }

        return response(
            'Serviço deletado com sucesso.'
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
            $return = DB::table('servicos')->where('id',$request->id)->update($decodedAlterArray);
        }catch(QueryException $e){
            return response([
                'message' => $e->errorInfo[2],
            ], 500);
        }

        if($return==0){
            return response(
                'Serviço não encontrado.'
            );
        }

        return response(
            'Serviço alterado com sucesso.'
        );   
    }
}
