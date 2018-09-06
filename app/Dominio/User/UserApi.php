<?php namespace App\Dominio\User;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use \App\Dominio\User\UserServico;


class UserApi extends Controller {

    public function __construct() {
        //$this->middleware("scope:create_users")->only('create');
        //$this->middleware("scope:update_users")->only('update');
        //$this->middleware("scope:view_users")->only('getAll');
        //$this->middleware("scope:view_users")->only('getPagination');
        //$this->middleware("scope:view_users")->only('get');
        //$this->middleware("scope:delete_users")->only('delete');
    }

    public function getAll() {
        $x = UserServico::getAll();

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

        $x = UserServico::getPagination($qtd);
        $x = $x->appends(['qtd'=>$qtd]);
        
        return response()->json($x, 200);
        
    }

    public function get(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:users,id'
        ];
        $this->validator(['id' => $request->id], $roles)->validate();
        
        $x = UserServico::get($request->id);
        
        return response()->json(['data' => $x], 200);        
         
    }

    public function create(Request $request) {
        $roles = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:191|unique:users',
            'password' => 'required|string|max:255|confirmed',
            'status' => 'required|numeric'                       
        ];
        $this->validator($request->all(), $roles)->validate();                
        $x = UserServico::create($request);
        if ($x):
            return response()->json(['data' => $x], 201);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

    public function update(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:users,id'
        ];
        $this->validator(array_merge($request->all(),['id'=>$request->id]), $roles)->validate();

        $x = UserServico::update($request->id, $request);
        if ($x):
            return response()->json(['data' => $x], 200);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

    public function delete(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:users,id'
        ];
        $this->validator(['id' => $request->id], $roles)->validate();
        
        $x = UserServico::delete($request->id);
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
