<?php namespace App\Dominio\Record;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class RecordServico extends Controller {

    function __construct() {
        
    }

    public static function getAll() {
        $x = Record::all();

        return $x;        
    }

    public static function get($id) {
        $x = Record::where("id", $id)->first();
       
        return $x;       
    }

    public static function getPagination($qtd) {
        $x = Record::paginate($qtd);
        
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

    public static function update($id, $request) {
        $x = self::_setObject($request, $id);
        
        $salvou = $x->save();
        if ($salvou) {
            return $x;
        }
        return false;       
    }

    public static function delete($id) {
        $x = Record::find($id);        

        return $x->delete();
    }

    private static function _setObject($request, $id = null) {
        if (is_null($id)) {
            $y = new Record;
        } else {
            $y = Record::find($id);
        }

        //Crie a associação dos campos do request com os dados do seu objeto do banco;
        $y->lottery_id  =   $request->lottery_id;
        $y->user_id     =   $request->user_id;
        $y->numbers     =   $request->numbers;
        $y->image       =   $request->image;
        $y->price       =   $request->price;
        $y->admin_tax   =   $request->admin_tax;
        $y->order_id    =   $request->order_id;
        return $y;
    }

}
