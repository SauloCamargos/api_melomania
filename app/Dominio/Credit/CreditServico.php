<?php namespace App\Dominio\Credit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class CreditServico extends Controller {

    function __construct() {
        
    }

    public static function getAll() {
        $x = Credit::all();

        return $x;        
    }

    public static function get($id) {
        $x = Credit::where("id", $id)->first();
       
        return $x;       
    }

    public static function getPagination($qtd) {
        $x = Credit::paginate($qtd);
        
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
        $x = Credit::find($id);        

        return $x->delete();
    }

    private static function _setObject($request, $id = null) {
        if (is_null($id)) {
            $y = new Credit;
        } else {
            $y = Credit::find($id);
        }

        //Crie a associação dos campos do request com os dados do seu objeto do banco;
        $y->record_id       =       $request->record_id;
        $y->lottery_id      =       $request->lottery_id;
        $y->name            =       $request->name;        
        $y->amount          =       $request->amount;
        return $y;
    }

}
