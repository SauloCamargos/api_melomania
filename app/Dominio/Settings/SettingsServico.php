<?php

namespace App\Dominio\Settings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class SettingsServico extends Controller {

    function __construct() {

    }

    public static function all() {
        $x = Settings::all();
        if ($x->count() > 0) {
            return $x;
        } else {
            return null;
        }
    }

    public static function find($id) {
        $x = Settings::where("id", $id)->first();
        if ($x != "") {
            return $x;
        } else {
            return null;
        }
    }

    public static function setAllConfig() {
        try{ 
            $x = Settings::get();

            foreach ($x as $key => $config) {
                config(['app.'.$config->name => $config->value]);
            }

        } catch (Exception $ex) {
            throw new Exception('Erro definir configurações', 500);                  
        }       
    }
    public static function showAllConfig() {
        try{ 
            $x = Settings::get();
            echo $x;
            foreach ($x as $key => $config) {
                echo config('app.'.$config->name);
            }

        } catch (Exception $ex) {
            throw new Exception('Erro definir configurações', 500);                  
        }       
    }

    public static function findByName($name) {
        $x = Settings::where("name", $name)->first();
        if ($x != "") {
            return $x;
        } else {
            return null;
        }
    }

    public static function findByGroup($name) {
      $name = $name . '_';
        $x = Settings::where("name", 'like', $name . '_%')->get();
        if ($x != "") {
            return $x;
        } else {
            return null;
        }
    }


    public static function findByNames($name) {
        $x = Settings::whereIn("name", $name)->get();
        if ($x != "") {
            return $x;
        } else {
            return null;
        }
    }

    public static function save($request) {
        if (self::_exists($request->name)) {
            $id = self::findByName($request->name)->id;
            $x = self::_setObject($request, $id);
        } else {
            $x = self::_setObject($request);
        }
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
            return true;
        }
        return null;
    }

    public static function delete($id) {
        try {
            if (Settings::where('id', $id)->exists()) {
                $x = Settings::find($id);
                $removeu = $x->delete();
                if ($removeu) {
                    return true;
                }
            }
        } catch (Exception $ex) {
            return null;
        }
    }

    private static function _exists($name) {
        return Settings::where('name', $name)->exists();
    }

    private static function _setObject($request, $id = null) {
        if (is_null($id)) {
            $y = new Settings;
        } else {
            $y = Settings::find($id);
        }

        $y->name = $request->name;
        $y->value = $request->value;


        return $y;
    }

}
