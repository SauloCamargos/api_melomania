<?php namespace App\Dominio\Role;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class RoleServico extends Controller {

    function __construct() {
        
    }

    public static function getAll() {
        $x = Role::all();

        return $x;        
    }

    public static function get($id) {
        $x = Role::where("id", $id)->first();
       
        return $x;       
    }

    public static function getPagination($qtd) {
        $x = Role::paginate($qtd);
        
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
        $x = Role::find($id);        

        return $x->delete();
    }

    private static function _setObject($request, $id = null) {
        if (is_null($id)) {
            $y = new Role;
        } else {
            $y = Role::find($id);
        }

        //Crie a associação dos campos do request com os dados do seu objeto do banco;


        return $y;
    }

}
