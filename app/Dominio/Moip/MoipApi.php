<?php namespace App\Dominio\Moip;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use \App\Dominio\Moip\MoipServico;
use Illuminate\Validation\Rule;
class MoipApi extends Controller {

    public function __construct() {
        //$this->middleware("scope:create_moips")->only('create');
        //$this->middleware("scope:update_moips")->only('update');
        //$this->middleware("scope:view_moips")->only('getAll');
        //$this->middleware("scope:view_moips")->only('getPagination');
        //$this->middleware("scope:view_moips")->only('get');
        //$this->middleware("scope:delete_moips")->only('delete');
    }

    public function getAll() {
        $x = MoipServico::getAll();

        return response()->json(['data' => $x], 200);
    }

    public function getPagination(Request $request) {
        $roles = [];
        $this->validator($request->all(), $roles)->validate();
        $qtd  = ($request->qtd)? $request->qtd : 15;
        $page = ($request->page)? $request->page : 1 ;

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        $x = MoipServico::getPagination($qtd);
        $x = $x->appends(['qtd'=>$qtd]);
        
        return response()->json($x, 200);
        
    }

    public function get(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:moips,id'
        ];
        $this->validator(['id' => $request->id], $roles)->validate();
        
        $x = MoipServico::get($request->id);
        
        return response()->json($x, 200);        
         
    }

    public function create(Request $request) {
        $roles = [];
        $this->validator($request->all(), $roles)->validate();
        
        $x = MoipServico::create($request);
        if ($x):
            return response()->json(['data' => $x], 201);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }
    public function createUser(Request $request) {
        $roles = [
            'user_id' => 'required|numeric|exists:users,id',
            'transparentAccount' => 'required|boolean',
            'email.address' => 'required|email',
            'person.name' => 'required|string',
            'person.lastName' => 'required|string',
            'person.birthDate' => 'required|date',
            'person.phone.areaCode' => 'required|string|digits_between:1,2',
            'person.phone.number' => 'required|string|digits_between:1,16',
            "person.address.street"=>'required|string',
            "person.address.streetNumber"=>'required|string',            
            "person.address.district"=>'required|string',
            "person.address.zipCode"=>'required|string',
            "person.address.city"=>'required|string',
            "person.address.state"=>'required|string|size:2',
            'person.taxDocument.type' => 'required|'.Rule::in(['CPF', 'CNPJ']),
            'person.taxDocument.number' => ['required',function($attribute, $value, $fail) use ($request) {             
                $object = json_decode(json_encode($request->all()));
                if($object->person->taxDocument->type == "CPF"){
                    if(strlen($value) != 11){
                        $fail($attribute.' is invalid. Length is 11 char');
                    }
                }
                if($object->person->taxDocument->type == "CNPJ"){
                    if(strlen($value) != 14){
                        $fail($attribute.' is invalid.Length is 14 char');
                    }
                }
            }],
            
            
        ];
        $this->validator($request->all(), $roles)->validate();
        
        $x = MoipServico::createUser($request);

         switch ($x) {
                case 1:
                    return response()->json(['error' => true,"name"=>"user.user_moip_exists"], 200);
                    break;
                case 2:
                    return response()->json(['error' => true,"name"=>"user.curl_error"], 200);
                    break;
                case 3:
                    return response()->json(['error' => false,"name"=>"user.created_success"], 200);
                    break;
                case 54:
                    return response()->json(['error' => true,"name"=>"user.error_save_data"], 500);
                    break;
         }


        if ($x):
            return response()->json(['data' => $x], 201);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

    public function update(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:moips,id'
        ];
        $this->validator(array_merge($request->all(),['id'=>$request->id]), $roles)->validate();

        $x = MoipServico::update($request->id, $request);
        if ($x):
            return response()->json(['data' => $x], 200);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

    public function delete(Request $request) {
        $roles = [
            'id' => 'required|numeric|exists:moips,id'
        ];
        $this->validator(['id' => $request->id], $roles)->validate();
        
        $x = MoipServico::delete($request->id);
        if ($x):
            return response()->json(['response' => 'Sucesso!'], 200);
        endif;

        return response()->json(['response' => 'Erro!'], 400);
    }

    protected function validator(array $data, $roles = null)
    { 
        return Validator::make($data, $roles);
    }

}
