<?php namespace App\Dominio\Contest;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Dominio\Record\Record;
use App\Dominio\User\User;
class ContestServico extends Controller {

    function __construct() {
        
    }

    public static function getAll() {
        $x = Contest::all();

        return $x;        
    }

    public static function get($id) {
        $x = Contest::where("id", $id)->first();
       
        return $x;       
    }

    public static function getLotteries($id) {
        $x = Contest::where("id", $id)->first();
       
        return $x->lotterys()->orderBy('name', "ASC")->get();
    }

    public static function getRecords($request) {
        $x = Contest::where("id", $request->id)->first();
        if(!isset($request->user_id)){
            if (Auth::user()){
               $user = Auth::user();
            }else{
                return 1;
            }
        }else{
            $user = User::find($request->user_id);
        }
        $arrayIds = $x->lotterys()->pluck('id')->toArray();
        return Record::whereIn('lottery_id',$arrayIds)->where('user_id',$user->id)->orderBy('created_at', "DESC")->paginate($request->qtd);
        
    }

    public static function getPagination($qtd) {
        $x = Contest::orderBy('created_at', "DESC")->paginate($qtd);
        
        return $x;        
    }

    public static function getUpcoming($qtd) {
        $limitDatetime = \Carbon\Carbon::now()->subHours(2);
        // dd($limitDatetime);

        $x = Contest::with(['game'])->where('draw_date','>=',$limitDatetime->toDateTimeString())->orderBy('draw_date', "ASC")->paginate($qtd);
        
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

    public static function addLottery($request) {
        $x = Contest::where("id", $request->id)->first();

        $x->lotterys()->create($request->toArray());
       
        return $x;       
    }

    public static function deleteLottery($request) {
        $x = Contest::where("id", $request->id)->first();

        $x->lotterys()->where('id',$request->lottery_id)->delete();
       
        return $x;       
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
        $x = Contest::find($id);        

        return $x->delete();
    }

    private static function _setObject($request, $id = null) {
        if (is_null($id)) {
            $y = new Contest;
        } else {
            $y = Contest::find($id);
        }

        //Crie a associação dos campos do request com os dados do seu objeto do banco;
        $y->game_id         =   $request->game_id;
        $y->name            =   $request->name;
        $y->price           =   $request->price;
        $y->admin_tax       =   $request->admin_tax;        
        $y->draw_date       =   $request->draw_date;        

        return $y;
    }

}
