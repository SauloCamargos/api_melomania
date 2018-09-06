<?php namespace App\Dominio\Lottery;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;

class LotteryServico extends Controller {

    function __construct() {
        
    }

    public static function getAll() {
        $x = Lottery::all();

        return $x;        
    }

    public static function get($id) {
        $x = Lottery::where("id", $id)->first();
       
        return $x;       
    }

    public static function getPagination($qtd) {
        $x = Lottery::paginate($qtd);
        
        return $x;        
    }

    public static function getUpcoming($qtd) {
        $limitDatetime = \Carbon\Carbon::now()->subHours(2);
        // dd($limitDatetime);

        $x = Lottery::whereHas('contest', function($query) use ($limitDatetime) {
            $query->where('draw_date','>=',$limitDatetime->toDateTimeString());
            $query->orderBy('draw_date', "ASC");
        })->with(['contest.game'])->paginate($qtd);
        
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
        $x = Lottery::find($id);        

        return $x->delete();
    }

    private static function _setObject($request, $id = null) {
        if (is_null($id)) {
            $y = new Lottery;
        } else {
            $y = Lottery::find($id);
        }

        //Crie a associação dos campos do request com os dados do seu objeto do banco;
        $y->contest_id      =       $request->contest_id;
        $y->name            =       $request->name;        
        $y->hit             =       $request->hit;
        $y->result          =       $request->result;
        return $y;
    }

}
