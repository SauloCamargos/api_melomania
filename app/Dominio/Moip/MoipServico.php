<?php namespace App\Dominio\Moip;

use App\Dominio\User\UserServico;
use Illuminate\Routing\Controller;
use Illuminate\Database\Eloquent\Model;

class MoipServico extends Controller {

    function __construct() {
        
    }

    public static function getAll() {
        $x = Moip::all();

        return $x;        
    }

    public static function get($id) {
        $x = Moip::where("id", $id)->first();
       
        return $x;       
    }

    public static function getPagination($qtd) {
        $x = Moip::paginate($qtd);
        
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

    public static function createUser($request) {
        $dataArray = $request->all();

        $user = UserServico::get($request->user_id);
        if(!is_null($user->id_moip)){
            return 1;
        }
        // $x = self::_setObject($request);        
        // $salvou = $x->save();
        // if ($salvou) {
        //     return $x;
        // }
        // return false;
        
        $moipEnvironments = config('app.moip_environments', false);       
       
        if($moipEnvironments == 1){
            $urlEnvironments = env('URL_MOIP_API_SANDBOX', false);
        }
        if($moipEnvironments == 2){
            $urlEnvironments = env('URL_MOIP_API_PROCUCTION', false);
        }
        $access_token = env('ACCESS_TOKEN_MOIP', false);

        $urlRequest = $urlEnvironments.'/v2/accounts';
        $methodRequest = 'POST';
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $urlRequest,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $methodRequest,
            CURLOPT_POSTFIELDS => json_encode($dataArray),
            CURLOPT_HTTPHEADER => array(
                'cache-control: no-cache',
                'content-type: application/json',
                "Authorization: OAuth $access_token"                
            ),
        ));

        $response = curl_exec($curl);
        self::webhookObservable($response,'created_user_moip');
        $responseObjc = json_decode($response);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return 2;
        } else {
            if (isset($responseObjc->id)) {
                $dataUpdated = (object)[
                    "id_moip" => $responseObjc->id,
                    "access_token_moip"=> $responseObjc->accessToken
                ];
                $salvou = UserServico::update($request->user_id, $dataUpdated);
                if ($salvou) {
                    return 3;
                } else {
                    return 4;
                }
            } 
        }
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
        $x = Moip::find($id);        

        return $x->delete();
    }

    private static function _setObject($request, $id = null) {
        if (is_null($id)) {
            $y = new Moip;
        } else {
            $y = Moip::find($id);
        }

        //Crie a associação dos campos do request com os dados do seu objeto do banco;


        return $y;
    }

    public static function webhookObservable($request, $method)
    {
        $myfile = fopen($method.'_'.time().'.json', 'w') or die('Unable to open file!');
        $txt = json_encode($request);
        fwrite($myfile, $txt);
        fclose($myfile);
    }

}
