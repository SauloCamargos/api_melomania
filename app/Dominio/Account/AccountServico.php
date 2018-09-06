<?php namespace App\Dominio\Account;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class AccountServico extends Controller {

    function __construct() {
        
    }

    public static function getAll() {
        $x = Account::all();

        return $x;        
    }

    public static function get($id) {
        $x = Account::where("id", $id)->first();
       
        return $x;       
    }

    public static function getPagination($qtd) {
        $x = Account::paginate($qtd);
        
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
        $x = Account::find($id);        

        return $x->delete();
    }

    private static function _setObject($request, $id = null) {
        if (is_null($id)) {
            $y = new Account;
        } else {
            $y = Account::find($id);
        }

        //Crie a associação dos campos do request com os dados do seu objeto do banco;
        $y->user_id     = $request->user_id;
        $y->amount      = $request->amount;
        return $y;
    }

}
