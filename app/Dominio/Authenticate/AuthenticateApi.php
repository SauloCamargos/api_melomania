<?php namespace App\Dominio\Authenticate;

use App\Dominio\User\User;
use Illuminate\Http\Request;
use Laravel\Passport\Client;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class AuthenticateApi extends Controller {

   private $client;

    public function __construct()
    {
        $this->client = Client::find(env('API_CLIENT_ID', null));
        //$this->middleware("scope:create_logins")->only('create');
        //$this->middleware("scope:update_logins")->only('update');
        //$this->middleware("scope:view_logins")->only('getAll');
        //$this->middleware("scope:view_logins")->only('getPagination');
        //$this->middleware("scope:view_logins")->only('get');
        //$this->middleware("scope:delete_logins")->only('delete');
    }

    public function login(Request $request)
    {
        $roles = [
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ];
        $this->validator($request->all(), $roles)->validate();

        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password, 'status' => User::USER_ACTIVE])) {
            return response()->json([
                 'error' => true,
                 'name' => 'login.credentials_error',
                ], 200);
        }

        $this->logoutOtherTokensUser();
        if(Auth::user()->roles()->first()){
            $scope = Auth::user()->roles()->first()->perms()->pluck('name')->toArray();
        }else{
            $scope = [];
        }


        $params = [
            'grant_type' => 'password',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'username' => $request->email,
            'password' => $request->password,
            'scope' => implode(' ', $scope),
        ];

        $request->request->add($params);

        $proxy = Request::create('oauth/token', 'POST');

        $returnLogin = Route::dispatch($proxy)->getContent();


        return json_encode(array_merge(json_decode($returnLogin, true),['user' => json_decode(Auth::user(), true)]));
        return response()->json($returnLogin, 200);
    }

    public function refresh(Request $request)
    {
        $roles = [
             'refresh_token' => 'required',
            ];
        $this->validator($request->all(), $roles)->validate();

        $params = [
                'grant_type' => 'refresh_token',
                'client_id' => $this->client->id,
                'client_secret' => $this->client->secret,
            ];

        $request->request->add($params);

        $proxy = Request::create('oauth/token', 'POST');

        return Route::dispatch($proxy);

        // return response()->json(['response' => 'Erro!'], 400);
    }

    public function logout(Request $request)
    {
        $accessToken = Auth::user()->token();

        DB::table('oauth_refresh_tokens')
        ->where('access_token_id', $accessToken->id)
        ->update(['revoked' => true]);

        $accessToken->revoke();

        return response()->json([], 204);
    }

    public function logoutOtherTokensUser()
    {
        $tokensUser = DB::table('oauth_access_tokens')
        ->where('user_id', Auth::user()->id)->get();
        // ->update(['revoked' => true]);

        foreach ($tokensUser as $key => $token) {
            DB::table('oauth_refresh_tokens')
            ->where('access_token_id', $token->id)
            ->update(['revoked' => true]);

            DB::table('oauth_access_tokens')
            ->where('id', $token->id)
            ->update(['revoked' => true]);
        }

        return true;

        // DB::table('oauth_refresh_tokens')
        // ->where('access_token_id', $accessToken->id)
        // ->update(['revoked' => true]);
    }

    function check() {
            
        if (Auth::user()){
            return json_encode(['user' => json_decode(Auth::user(), true)]);
        }else{
            return response()->json(['user' =>null,'error'=>true], 401);
        }
    }

    protected function validator(array $data, $roles = null)
    {
        return Validator::make($data, $roles);
    }
}
