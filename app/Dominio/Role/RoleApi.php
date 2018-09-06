<?php namespace App\Dominio\Role;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use \App\Dominio\Role\RoleServico;

class RoleApi extends Controller {

    public function __construct() {
        //$this->middleware("scope:create_roles")->only('create');
        //$this->middleware("scope:update_roles")->only('update');
        //$this->middleware("scope:view_roles")->only('getAll');
        //$this->middleware("scope:view_roles")->only('getPagination');
        //$this->middleware("scope:view_roles")->only('get');
        //$this->middleware("scope:delete_roles")->only('delete');
    }

    public function getAll() {
        $x = RoleServico::getAll();

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

        $x = RoleServico::getPagination($qtd);
        $x = $x->appends(['qtd'=>$qtd]);
        
        return response()->json($x, 200);
        
    }

    public function get(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:roles,id'
        ];
        $this->validator(['id' => $request->id], $roles)->validate();
        
        $x = RoleServico::get($request->id);
        
        return response()->json(['data' => $x], 200);        
         
    }

    public function create(Request $request) {
        $roles = [];
        $this->validator($request->all(), $roles)->validate();
        
        $x = RoleServico::create($request);
        if ($x):
            return response()->json(['data' => $x], 201);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

    public function update(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:roles,id'
        ];
        $this->validator(array_merge($request->all(),['id'=>$request->id]), $roles)->validate();

        $x = RoleServico::update($request->id, $request);
        if ($x):
            return response()->json(['data' => $x], 200);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

    public function delete(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:roles,id'
        ];
        $this->validator(['id' => $request->id], $roles)->validate();
        
        $x = RoleServico::delete($request->id);
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
