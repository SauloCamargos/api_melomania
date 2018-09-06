<?php namespace App\Dominio\Permission;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class PermissionServico extends Controller {

    function __construct() {
        
    }

    public static function getAll() {
        $x = Permission::all();

        return $x;        
    }

    public static function get($id) {
        $x = Permission::where("id", $id)->first();
       
        return $x;       
    }

    public static function getPagination($qtd) {
        $x = Permission::paginate($qtd);
        
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
        $x = Permission::find($id);        

        return $x->delete();
    }

    private static function _setObject($request, $id = null) {
        if (is_null($id)) {
            $y = new Permission;
        } else {
            $y = Permission::find($id);
        }

        //Crie a associação dos campos do request com os dados do seu objeto do banco;


        return $y;
    }

}
