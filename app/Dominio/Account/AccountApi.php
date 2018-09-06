<?php namespace App\Dominio\Account;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use \App\Dominio\Account\AccountServico;

class AccountApi extends Controller {

    public function __construct() {
        //$this->middleware("scope:create_accounts")->only('create');
        //$this->middleware("scope:update_accounts")->only('update');
        //$this->middleware("scope:view_accounts")->only('getAll');
        //$this->middleware("scope:view_accounts")->only('getPagination');
        //$this->middleware("scope:view_accounts")->only('get');
        //$this->middleware("scope:delete_accounts")->only('delete');
    }

    public function getAll() {
        $x = AccountServico::getAll();

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

        $x = AccountServico::getPagination($qtd);
        $x = $x->appends(['qtd'=>$qtd]);
        
        return response()->json($x, 200);
        
    }

    public function get(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:accounts,id'
        ];
        $this->validator(['id' => $request->id], $roles)->validate();
        
        $x = AccountServico::get($request->id);
        
        return response()->json(['data' => $x], 200);        
         
    }

    public function create(Request $request) {
        $roles = [];
        $this->validator($request->all(), $roles)->validate();
        
        $x = AccountServico::create($request);
        if ($x):
            return response()->json(['data' => $x], 201);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

    public function update(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:accounts,id'
        ];
        $this->validator(array_merge($request->all(),['id'=>$request->id]), $roles)->validate();

        $x = AccountServico::update($request->id, $request);
        if ($x):
            return response()->json(['data' => $x], 200);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

    public function delete(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:accounts,id'
        ];
        $this->validator(['id' => $request->id], $roles)->validate();
        
        $x = AccountServico::delete($request->id);
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
