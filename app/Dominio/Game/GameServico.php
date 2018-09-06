<?php namespace App\Dominio\Game;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class GameServico extends Controller {

    function __construct() {
        
    }

    public static function getAll() {
        $x = Game::all();

        return $x;        
    }

    public static function get($id) {
        $x = Game::where("id", $id)->first();
       
        return $x;       
    }

    public static function getPagination($qtd) {
        $x = Game::paginate($qtd);
        
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
        $x = Game::find($id);        

        return $x->delete();
    }

    private static function _setObject($request, $id = null) {
        if (is_null($id)) {
            $y = new Game;
        } else {
            $y = Game::find($id);
        }

        //Crie a associação dos campos do request com os dados do seu objeto do banco;
        $y->name        =   $request->name; 
        $y->state_id    =   $request->state_id; 

        return $y;
    }

}
