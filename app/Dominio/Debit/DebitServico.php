<?php namespace App\Dominio\Debit;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class DebitServico extends Controller {

    function __construct() {
        
    }

    public static function getAll() {
        $x = Debit::all();

        return $x;        
    }

    public static function get($id) {
        $x = Debit::where("id", $id)->first();
       
        return $x;       
    }

    public static function getPagination($qtd) {
        $x = Debit::paginate($qtd);
        
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
        $x = Debit::find($id);        

        return $x->delete();
    }

    private static function _setObject($request, $id = null) {
        if (is_null($id)) {
            $y = new Debit;
        } else {
            $y = Debit::find($id);
        }

        //Crie a associação dos campos do request com os dados do seu objeto do banco;
        $y->record_id       =   $request->record_id;
        $y->name            =   $request->name;        
        $y->amount          =   $request->amount;
        return $y;
    }

}
