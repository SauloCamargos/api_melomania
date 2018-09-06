<?php namespace App\Dominio\State;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class StateServico extends Controller {

    function __construct() {
        
    }

    public static function getAll() {
        $x = State::all();

        return $x;        
    }

    public static function get($id) {
        $x = State::where("id", $id)->first();
       
        return $x;       
    }

    public static function getPagination($qtd) {
        $x = State::paginate($qtd);
        
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
        $x = State::find($id);        

        return $x->delete();
    }

    private static function _setObject($request, $id = null) {
        if (is_null($id)) {
            $y = new State;
        } else {
            $y = State::find($id);
        }

        //Crie a associação dos campos do request com os dados do seu objeto do banco;


        return $y;
    }

}
