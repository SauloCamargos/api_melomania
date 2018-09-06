<?php namespace App\Dominio\Debit;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use \App\Dominio\Debit\DebitServico;

class DebitApi extends Controller {

    public function __construct() {
        //$this->middleware("scope:create_debits")->only('create');
        //$this->middleware("scope:update_debits")->only('update');
        //$this->middleware("scope:view_debits")->only('getAll');
        //$this->middleware("scope:view_debits")->only('getPagination');
        //$this->middleware("scope:view_debits")->only('get');
        //$this->middleware("scope:delete_debits")->only('delete');
    }

    public function getAll() {
        $x = DebitServico::getAll();

        return response()->json(['data' => $x], 200);
    }

    public function getPagination(Request $request) {
        $roles = [];
        $this->validator($request->all(), $roles)->validate();
        $qtd  = ($request->qtd)? $request->qtd : 15;
        $page = ($request->page)? $request->page : 1 ;

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $x = DebitServico::getPagination($qtd);
        $x = $x->appends(['qtd'=>$qtd]);
        
        return response()->json($x, 200);
        
    }

    public function get(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:debits,id'
        ];
        $this->validator(['id' => $request->id], $roles)->validate();
        
        $x = DebitServico::get($request->id);
        
        return response()->json(['data' => $x], 200);        
         
    }

    public function create(Request $request) {
        $roles = [];
        $this->validator($request->all(), $roles)->validate();
        
        $x = DebitServico::create($request);
        if ($x):
            return response()->json(['data' => $x], 201);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

    public function update(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:debits,id'
        ];
        $this->validator(array_merge($request->all(),['id'=>$request->id]), $roles)->validate();

        $x = DebitServico::update($request->id, $request);
        if ($x):
            return response()->json(['data' => $x], 200);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

    public function delete(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:debits,id'
        ];
        $this->validator(['id' => $request->id], $roles)->validate();
        
        $x = DebitServico::delete($request->id);
        if ($x):
            return response()->json(['response' => 'Sucesso!'], 200);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

    protected function validator(array $data, $roles = null)
    { 
        return Validator::make($data, $roles);
    }

}
