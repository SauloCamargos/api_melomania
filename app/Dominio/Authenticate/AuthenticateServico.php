<?php namespace App\Dominio\Authenticate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class AuthenticateServico extends Controller {

    function __construct() {
        
    }

    public static function getAll() {
        $x = Authenticate::all();

        return $x;        
    }

    public static function get($id) {
        $x = Authenticate::where("id", $id)->first();
       
        return $x;       
    }

    public static function getPagination($qtd) {
        $x = Authenticate::paginate($qtd);
        
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
        $x = Authenticate::find($id);        

        return $x->delete();
    }

    private static function _setObject($request, $id = null) {
        if (is_null($id)) {
            $y = new Authenticate;
        } else {
            $y = Authenticate::find($id);
        }

        //Crie a associação dos campos do request com os dados do seu objeto do banco;


        return $y;
    }

}
