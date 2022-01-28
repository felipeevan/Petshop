<?php

namespace App\Http\Controllers\API;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;

class AtendimentosController extends Controller
{
    public function list()
    {
        try{
            $lista = DB::select('select * from atendimentos');
        }catch(QueryException $e){
            return response([
                'message' => $e->errorInfo[2],
            ], 500);
        }

        $listaMapped = array_map(function ($li){
            $listaItems = DB::select("select * from items_atendimento WHERE atendimento_id = {$li->id}");
            $li->items = $listaItems;
            return $li;
        }, $lista);
        

        return response(
            $listaMapped
        );   
    }

    public function listB()
    {
        try{
            $lista = DB::select('SELECT a.id, a.data, c.nome AS nome_cliente, f.nome AS nome_funcionario
            FROM atendimentos a
            INNER JOIN clientes c ON a.cliente_id = c.id
            INNER JOIN funcionarios f ON a.funcionario_id = f.id'
        );
        }catch(QueryException $e){
            return response([
                'message' => $e->errorInfo[2],
            ], 500);
        }

        $listaMapped = array_map(function ($li){
            $listaItems = DB::select("SELECT ai.id, ai.nome, ai.descricao, ai.qtd, ai.preco_uni_freeze, ai.preco_total
            FROM items_atendimento ai 
            WHERE atendimento_id = {$li->id}");
            $li->items = $listaItems;
            return $li;
        }, $lista);
        

        return response(
            $listaMapped
        );   
    }

    
    public function add(Request $request)
    {
        $validator = Validator::make([
            'cliente_id' => $request->cliente_id,
            'funcionario_id' => $request->funcionario_id,
            'items' => $request->items
        ], [
            'cliente_id' => ['required', 'integer'],
            'funcionario_id' => ['required', 'integer'],
            'items' => ['required', 'array']
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
        
        $listaFuncionario = DB::select("select * from funcionarios where id = {$request->funcionario_id}");
        if(!$listaFuncionario){
            return response([
                'message' => "Não foi encontrado funcionário com este id",
            ], 500);
        }

        $datetime = new DateTime('NOW');

        try{
            $id = DB::table('atendimentos')->insertGetId(array(
                'data' => $datetime,
                'cliente_id' => $request->cliente_id,
                'funcionario_id' => $request->funcionario_id
            ));
        
            foreach ($request->items as $item) {
                $validator = Validator::make([
                    'servico_id' => $item['servico_id'],
                    'qtd' => $item['qtd']
                ], [
                    'servico_id' => ['required', 'integer'],
                    'qtd' => ['required', 'integer'],
                ]);
        
                if ($validator->fails()) {
                    return response([
                        'message' => 'Erro ao validar items',
                        'erros' => $validator->errors()->messages()
                    ], 400);
                }

                $listaServico = DB::select("select * from servicos where id = {$item['servico_id']}");
                if(!$listaServico){
                    return response([
                        'message' => "Não foi encontrado servico com o id {$item['servico_id']}",
                    ], 500);
                }
            }

            foreach ($request->items as $item) {
                $servico = DB::select("select * from servicos where id = {$item['servico_id']}")[0];
                DB::table('items_atendimento')->insert(array(
                    'nome' => $servico->nome,
                    'descricao' => $servico->descricao,
                    'qtd' => $item['qtd'],
                    'preco_uni_freeze' => $servico->preco_uni,
                    'preco_total' => $servico->preco_uni*$item['qtd'],
                    'atendimento_id' =>$id,
                    'servico_id' => $servico->id
                ));
            }
        }catch(QueryException $e){
            return response([
                'message' => $e->errorInfo[2],
            ], 500);
        }

        return response(
            'Atendimento adicionado com sucesso.'
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
            $return = DB::delete(DB::raw("DELETE FROM atendimentos WHERE id = {$request->id}"));
        }catch(QueryException $e){
            return response([
                'message' => $e->errorInfo[2],
            ], 500);
        }

        if($return==0){
            return response(
                'Atendimento não encontrado.'
            );
        }

        return response(
            'Atendimento deletado com sucesso.'
        );   
    }
}
