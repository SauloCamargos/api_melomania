<?php

namespace App\Dominio\Settings;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Dominio\Settings\SettingsServico;
use Illuminate\Support\Facades\DB;

class SettingsApi extends Controller {

    public function getAll() {
        $x = SettingsServico::all();
        if (count($x) > 0):
            return response()->json($x, 200);
        else:
            return response()->json(['response' => 'Erro!'], 400);
        endif;
    }

    public function get(Request $request) {
        $x = SettingsServico::find($request->id);
        if (!is_null($x)):
            return response()->json($x, 200);
        else:
            return response()->json(['response' => 'Erro!'], 400);
        endif;
    }

    public function getByName(Request $request) {
        $x = SettingsServico::findByName($request->name);
        if (!is_null($x)):
            return response()->json($x, 200);
        else:
            return response()->json([], 200);
        endif;
    }

    public function getByGroup(Request $request) {
        $x = SettingsServico::findByGroup($request->name);
        if (!is_null($x)):
            return response()->json($x, 200);
        else:
            return response()->json([], 200);
        endif;
    }


    public function getByNames(Request $request) {
        $dados = $request->all();
        $names = [];
        foreach ($dados as $key => $dado) {
          array_push($names,$dado['name']);
        }
        $x = SettingsServico::findByNames($names);
        if (!is_null($x)):
            return response()->json($x, 200);
        else:
            return response()->json([], 200);
        endif;
    }

    public function create(Request $request) {
        // Start transaction!
        DB::beginTransaction();
        $salvou = true;
        foreach ($request->all() as $name => $value) {
            $obj = (object) [
                        'name' => $name,
                        'value' => $value,
            ];
            $x = SettingsServico::save($obj);
            if ($x == false) {
                $salvou = false;
            }
        }
        if ($salvou == false):
            DB::rollback();
            return response()->json(['response' => 'Erro!'], 400);
        endif;

        DB::commit();
        return response()->json(['response' => 'Sucesso!'], 200);
    }

    public function update(Request $request) {
        $x = SettingsServico::update($request->id, $request);
        if ($x):
            return response()->json(['response' => 'Sucesso!'], 200);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

    public function delete(Request $request) {
        $x = SettingsServico::delete($request->id);
        if ($x):
            return response()->json(['response' => 'Sucesso!'], 200);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

}
