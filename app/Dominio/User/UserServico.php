<?php namespace App\Dominio\User;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class UserServico extends Controller {

    function __construct() {
        
    }

    public static function getAll() {
        $x = User::all();

        return $x;        
    }

    public static function get($id) {
        $x = User::where("id", $id)->first();
       
        return $x;       
    }

    public static function getPagination($qtd) {
        $x = User::paginate($qtd);
        
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
        $x = User::find($id);        

        return $x->delete();
    }

    private static function _setObject($request, $id = null) {
        if (is_null($id)) {
            $y = new User;
            $y->password    =   bcrypt($request->password);    
        } else {
            $y = User::find($id);
        }

        //Crie a associação dos campos do request com os dados do seu objeto do banco;
        if(isset($request->name))
        $y->name                   =   $request->name;    

        if(isset($request->email))
        $y->email                  =   $request->email;    

        if(isset($request->status))
        $y->status                 =   $request->status;    

        if(isset($request->id_moip))
        $y->id_moip                =   $request->id_moip;    

        if(isset($request->access_token_moip))
        $y->access_token_moip      =   $request->access_token_moip;    

        return $y;
    }

}
