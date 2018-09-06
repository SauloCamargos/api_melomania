<?php namespace App\Dominio\Order;

use App\Dominio\Lottery\Lottery;
use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\Model;

class OrderServico extends Controller {

    function __construct() {
        
    }

    public static function getAll() {
        $x = Order::all();

        return $x;        
    }

    public static function get($id) {
        $x = Order::where("id", $id)->first();
       
        return $x;       
    }

    public static function getPagination($qtd) {
        $x = Order::paginate($qtd);
        
        return $x;        
    }

    public static function create($request) {
        $x = self::_setObject($request);        
        $salvou = $x->save();
        if ($salvou) {
            return $x;
        }
        return false;
    }

    /** Adiciona pedido no carrinho(tabela orders_products)  **/
    public static function addItem($request)
    {
        try {
            $lottery = Lottery::find($request->lottery_id);

            if (!$user = Auth::user()) {
                return 1;
            }
            $order = Orders::where('user_id', $user->id)->where('status', Orders::RESERVED)->first();
            if (!$order):
                $order = self::save((object) [
                    'user_id' => $user->id,
                    'status' => Orders::RESERVED
                ]);
            endif;

          
            $order->records()->create([
                'lottery_id' => $lottery->id, 
                'user_id' => $user->id,
                'numbers' => $request->numbers,
                'price' => $lottery->contest->price,
                'admin_tax' => $lottery->contest->admin_tax
            ]);

        return 2;
           
        } catch (\Exception $ex) {
            return 3;
        }
    }

    public static function update($id, $request) {
        $x = self::_setObject($request, $id);
        
        $salvou = $x->save();
        if ($salvou) {
            return $x;
        }
        return false;       
    }

    public static function delete($id) {
        $x = Order::find($id);        

        return $x->delete();
    }

    private static function _setObject($request, $id = null) {
        if (is_null($id)) {
            $y = new Order;
        } else {
            $y = Order::find($id);
        }

        //Crie a associação dos campos do request com os dados do seu objeto do banco;


        return $y;
    }

}
