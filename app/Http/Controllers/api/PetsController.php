<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class PetsController extends Controller
{
    public function list()
    {
        try{
            $lista = DB::select('select * from pets');
        }catch(QueryException $e){
            return response([
                'message' => $e->errorInfo[2],
            ], 500);
        }

        return response(
            $lista
        );   
    }

    public function listBest()
    {
        try{
            $lista = DB::select('select 
            p.id, p.nome, p.raca, 
            CASE 
                WHEN p.condespecial IS NULL THEN \'Não se aplica\' 
                ELSE p.condespecial
            END AS condicao_especial,
            c.nome AS nome_dono
            from pets p INNER JOIN clientes c ON p.cliente_id = c.id');
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
            'raca' => $request->raca,
            'condespecial' => $request->condespecial,
            'cliente_id' => $request->cliente_id
        ], [
            'nome' => ['required', 'string'],
            'raca' => ['required', 'string'],
            'condespecial' => ['string', 'nullable'],
            'cliente_id' => ['required', 'integer']
        ]);

        if ($validator->fails()) {
            return response([
                'message' => 'Erro ao validar campos',
                'erros' => $validator->errors()->messages()
            ], 400);
        }
        
        $listaCliente = DB::select("select * from clientes where id = {$request->cliente_id}");
        if(!$listaCliente){
            return response([
                'message' => "Não foi encontrado cliente com este id",
            ], 500);
        }

        try{
            DB::insert('insert into pets
            (nome, raca, condespecial, cliente_id) values (?, ?, ?, ?)', 
            [$request->nome, $request->raca, 
            $request->condespecial, $request->cliente_id]);
        }catch(QueryException $e){
            return response([
                'message' => $e->errorInfo[2],
            ], 500);
        }

        return response(
            'Pet adicionado com sucesso.'
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
            $return = DB::delete(DB::raw("DELETE FROM pets WHERE id = {$request->id}"));
        }catch(QueryException $e){
            return response([
                'message' => $e->errorInfo[2],
            ], 500);
        }

        if($return==0){
            return response(
                'Pet não encontrado.'
            );
        }

        return response(
            'Pet deletado com sucesso.'
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
            $return = DB::table('pets')->where('id',$request->id)->update($decodedAlterArray);
        }catch(QueryException $e){
            return response([
                'message' => $e->errorInfo[2],
            ], 500);
        }

        if($return==0){
            return response(
                'Pet não encontrado.'
            );
        }

        return response(
            'Pet alterado com sucesso.'
        );   
    }

    public function listClientesPets(Request $request)
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
            $lista = DB::select("select * from pets where cliente_id = {$request->id}");
        }catch(QueryException $e){
            return response([
                'message' => $e->errorInfo[2],
            ], 500);
        }

        return response(
            $lista
        );   
    }

}
