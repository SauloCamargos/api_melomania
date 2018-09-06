<?php namespace App\Dominio\Order;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use \App\Dominio\Order\OrderServico;

class OrderApi extends Controller {

    public function __construct() {
        //$this->middleware("scope:create_orders")->only('create');
        //$this->middleware("scope:update_orders")->only('update');
        //$this->middleware("scope:view_orders")->only('getAll');
        //$this->middleware("scope:view_orders")->only('getPagination');
        //$this->middleware("scope:view_orders")->only('get');
        //$this->middleware("scope:delete_orders")->only('delete');
    }

    public function getAll() {
        $x = OrderServico::getAll();

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

        $x = OrderServico::getPagination($qtd);
        $x = $x->appends(['qtd'=>$qtd]);
        
        return response()->json($x, 200);
        
    }

    public function get(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:orders,id'
        ];
        $this->validator(['id' => $request->id], $roles)->validate();
        
        $x = OrderServico::get($request->id);
        
        return response()->json($x, 200);        
         
    }

    public function create(Request $request) {
        $roles = [];
        $this->validator($request->all(), $roles)->validate();
        
        $x = OrderServico::create($request);
        if ($x):
            return response()->json(['data' => $x], 201);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

    /** Deve vir no Request: 'product_id e 'quantity'
     *  OBS: Adiciona pedido no carrinho(tabela orders_products)  **/
    public function addItem(Request $request)
    {
         $roles = [
             'lottery_id' => 'required|integer|exists:lotteries,id',             
             'numbers' => 'required|array|integer'             
        ];
        $this->validator($request->all(), $roles)->validate();
        
        $x = OrdersServico::addItem($request);
        if ($x):
            switch ($x) {
                case 1:
                    return response()->json(['error' => 'user.not_found'], 404);
                    break;                
                case 2:
                    return response()->json(['response' => 'order.not_addItem'], 200);
                    break;
                case 3:
                    return response()->json(['error' => 'order.internalerror'], 500);
                    break;
            }
        endif;

        return response()->json(['error' => 'order.not_addItem'], 400);
    }

    public function update(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:orders,id'
        ];
        $this->validator(array_merge($request->all(),['id'=>$request->id]), $roles)->validate();

        $x = OrderServico::update($request->id, $request);
        if ($x):
            return response()->json(['data' => $x], 200);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

    public function delete(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:orders,id'
        ];
        $this->validator(['id' => $request->id], $roles)->validate();
        
        $x = OrderServico::delete($request->id);
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
