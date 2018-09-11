<?php namespace App\Dominio\Contest;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use \App\Dominio\Contest\ContestServico;


class ContestApi extends Controller {

    public function __construct() {
        //$this->middleware("scope:create_contests")->only('create');
        //$this->middleware("scope:update_contests")->only('update');
        //$this->middleware("scope:view_contests")->only('getAll');
        //$this->middleware("scope:view_contests")->only('getPagination');
        //$this->middleware("scope:view_contests")->only('get');
        //$this->middleware("scope:delete_contests")->only('delete');
    }

    public function getAll() {
        $x = ContestServico::getAll();

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
        $x = ContestServico::getPagination($qtd);
        $x = $x->appends(['qtd'=>$qtd]);        
        return response()->json($x, 200);        
    }

    public function getUpcoming(Request $request) {
        $roles = [];
        $this->validator($request->all(), $roles)->validate();
        $qtd  = ($request->qtd)? $request->qtd : 15;
        $page = ($request->page)? $request->page : 1 ;
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        $x = ContestServico::getUpcoming($qtd);
        $x = $x->appends(['qtd'=>$qtd]);        
        return response()->json($x, 200);        
    }

    public function get(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:contests,id'
        ];
        $this->validator(['id' => $request->id], $roles)->validate();
        
        $x = ContestServico::get($request->id);
        
        return response()->json(['data' => $x], 200);        
         
    }

    public function getLotteries(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:contests,id'
        ];
        $this->validator(['id' => $request->id], $roles)->validate();
        
        $x = ContestServico::getLotteries($request->id);        
        return response()->json(["data" => $x], 200);  
    }

    public function getRecords(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:contests,id',            
            'user_id' => 'numeric|exists:users,id'
        ];
        $this->validator($request->all(), $roles)->validate();
        $qtd  = ($request->qtd)? $request->qtd : 15;
        $page = ($request->page)? $request->page : 1 ;
        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });
        $x = ContestServico::getRecords($request);    
        $x = $x->appends(['qtd'=>$request->qtd]);     
        return response()->json( $x, 200);  
    }

    public function create(Request $request) {
        $roles = [];
        $this->validator($request->all(), $roles)->validate();
        
        $x = ContestServico::create($request);
        if ($x):
            return response()->json(['data' => $x], 201);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

    public function addLottery(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:contests,id',
            'name' => 'required|string',
            'hit' => 'required|numeric'            
        ];
        $this->validator(array_merge($request->all(),['id'=>$request->id]), $roles)->validate();
        
        $x = ContestServico::addLottery($request);
        if ($x):
            return response()->json($x, 201);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

    public function update(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:contests,id'
        ];
        $this->validator(array_merge($request->all(),['id'=>$request->id]), $roles)->validate();

        $x = ContestServico::update($request->id, $request);
        if ($x):
            return response()->json(['data' => $x], 200);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

    public function delete(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:contests,id'
        ];
        $this->validator(['id' => $request->id], $roles)->validate();
        
        $x = ContestServico::delete($request->id);
        if ($x):
            return response()->json(['response' => 'Sucesso!'], 200);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

     public function deleteLottery(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:contests,id'
        ];
        $this->validator(['id' => $request->id], $roles)->validate();
        
        $x = ContestServico::deleteLottery($request);
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
