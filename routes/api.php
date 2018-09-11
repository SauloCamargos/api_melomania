<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

//################## Rotas para Authenticate ###############

Route::group(['namespace' => 'App\Dominio\Authenticate\\', 'prefix' => 'v1'], function () {
    $prefix_controller = 'AuthenticateApi';
    Route::post('authenticate', $prefix_controller.'@login');
    Route::post('refresh', $prefix_controller.'@refresh');
    Route::get('check', $prefix_controller.'@check')->middleware('auth:api');

    // Route::prefix('permissions')->group(function () use($prefix_controller) {
    //     Route::get('/getAll',   $prefix_controller . '@getAll');
    //     Route::get('/{id}',     $prefix_controller . '@get');
    //     Route::post('/',        $prefix_controller . '@create');
    //     Route::put('/{id}',     $prefix_controller . '@update');
    //     Route::delete('/{id}',  $prefix_controller . '@delete');
    // });
});


################### Rotas para o modelo User ###############

Route::group(['namespace' => 'App\Dominio\User\\', 'prefix' => 'v1'], function () {
    $prefix_controller = "UserApi";
    Route::prefix('users')->group(function () use($prefix_controller) {
        Route::get('/',         $prefix_controller . '@getPagination');        
        Route::get('/getAll',   $prefix_controller . '@getAll');        
        Route::get('/{id}',     $prefix_controller . '@get');
        Route::post('/',        $prefix_controller . '@create');
        Route::put('/{id}',     $prefix_controller . '@update');
        Route::delete('/{id}',  $prefix_controller . '@delete');
    });
});




################### Rotas para o modelo Role ###############

Route::group(['namespace' => 'App\Dominio\Role\\', 'prefix' => 'v1'], function () {
    $prefix_controller = "RoleApi";
    Route::prefix('roles')->group(function () use($prefix_controller) {
        Route::get('/',         $prefix_controller . '@getPagination');        
        Route::get('/getAll',   $prefix_controller . '@getAll');        
        Route::get('/{id}',     $prefix_controller . '@get');
        Route::post('/',        $prefix_controller . '@create');
        Route::put('/{id}',     $prefix_controller . '@update');
        Route::delete('/{id}',  $prefix_controller . '@delete');
    });
});




################### Rotas para o modelo Permission ###############

Route::group(['namespace' => 'App\Dominio\Permission\\', 'prefix' => 'v1'], function () {
    $prefix_controller = "PermissionApi";
    Route::prefix('permissions')->group(function () use($prefix_controller) {
        Route::get('/',         $prefix_controller . '@getPagination');        
        Route::get('/getAll',   $prefix_controller . '@getAll');        
        Route::get('/{id}',     $prefix_controller . '@get');
        Route::post('/',        $prefix_controller . '@create');
        Route::put('/{id}',     $prefix_controller . '@update');
        Route::delete('/{id}',  $prefix_controller . '@delete');
    });
});




################### Rotas para o modelo Account ###############

Route::group(['namespace' => 'App\Dominio\Account\\', 'prefix' => 'v1'], function () {
    $prefix_controller = "AccountApi";
    Route::prefix('accounts')->group(function () use($prefix_controller) {
        Route::get('/',         $prefix_controller . '@getPagination');        
        Route::get('/getAll',   $prefix_controller . '@getAll');        
        Route::get('/{id}',     $prefix_controller . '@get');
        Route::post('/',        $prefix_controller . '@create');
        Route::put('/{id}',     $prefix_controller . '@update');
        Route::delete('/{id}',  $prefix_controller . '@delete');
    });
});




################### Rotas para o modelo Game ###############

Route::group(['namespace' => 'App\Dominio\Game\\', 'prefix' => 'v1'], function () {
    $prefix_controller = "GameApi";
    Route::prefix('games')->group(function () use($prefix_controller) {
        Route::get('/',         $prefix_controller . '@getPagination');        
        Route::get('/getAll',   $prefix_controller . '@getAll');        
        Route::get('/{id}',     $prefix_controller . '@get');
        Route::post('/',        $prefix_controller . '@create');
        Route::put('/{id}',     $prefix_controller . '@update');
        Route::delete('/{id}',  $prefix_controller . '@delete');
    });
});




################### Rotas para o modelo Contest ###############

Route::group(['namespace' => 'App\Dominio\Contest\\', 'prefix' => 'v1'], function () {
    $prefix_controller = "ContestApi";
    Route::prefix('contests')->group(function () use($prefix_controller) {
        Route::get('/',         $prefix_controller . '@getPagination');        
        Route::get('/getAll',   $prefix_controller . '@getAll');        
        Route::get('/getUpcoming',   $prefix_controller . '@getUpcoming');        
        Route::get('/{id}/lotteries', $prefix_controller.'@getLotteries');
        Route::get('/{id}',     $prefix_controller . '@get');
        Route::post('/records', $prefix_controller.'@getRecords');
        Route::post('/{id}/lotteries', $prefix_controller.'@addLottery');
        Route::post('/',        $prefix_controller . '@create');
        Route::put('/{id}',     $prefix_controller . '@update');
        Route::delete('/{id}',  $prefix_controller . '@delete');
        Route::delete('/{id}/lotteries/{lottery_id}', $prefix_controller.'@deleteLottery');
    });
});




################### Rotas para o modelo Lottery ###############

Route::group(['namespace' => 'App\Dominio\Lottery\\', 'prefix' => 'v1'], function () {
    $prefix_controller = "LotteryApi";
    Route::prefix('lotterys')->group(function () use($prefix_controller) {
        Route::get('/',         $prefix_controller . '@getPagination');        
        Route::get('/getAll',   $prefix_controller . '@getAll'); 
        Route::get('/getUpcoming',   $prefix_controller . '@getUpcoming');          
        Route::get('/{id}',     $prefix_controller . '@get');
        Route::post('/',        $prefix_controller . '@create');
        Route::put('/{id}',     $prefix_controller . '@update');
        Route::delete('/{id}',  $prefix_controller . '@delete');
    });
});




################### Rotas para o modelo Record ###############

Route::group(['namespace' => 'App\Dominio\Record\\', 'prefix' => 'v1'], function () {
    $prefix_controller = "RecordApi";
    Route::prefix('records')->group(function () use($prefix_controller) {
        Route::get('/',         $prefix_controller . '@getPagination');        
        Route::get('/getAll',   $prefix_controller . '@getAll');        
        Route::get('/{id}',     $prefix_controller . '@get');
        Route::post('/',        $prefix_controller . '@create');
        Route::put('/{id}',     $prefix_controller . '@update');
        Route::delete('/{id}',  $prefix_controller . '@delete');
    });
});




################### Rotas para o modelo Transaction ###############

Route::group(['namespace' => 'App\Dominio\Transaction\\', 'prefix' => 'v1'], function () {
    $prefix_controller = "TransactionApi";
    Route::prefix('transactions')->group(function () use($prefix_controller) {
        Route::get('/',         $prefix_controller . '@getPagination');        
        Route::get('/getAll',   $prefix_controller . '@getAll');        
        Route::get('/{id}',     $prefix_controller . '@get');
        Route::post('/',        $prefix_controller . '@create');
        Route::put('/{id}',     $prefix_controller . '@update');
        Route::delete('/{id}',  $prefix_controller . '@delete');
    });
});




################### Rotas para o modelo Debit ###############

Route::group(['namespace' => 'App\Dominio\Debit\\', 'prefix' => 'v1'], function () {
    $prefix_controller = "DebitApi";
    Route::prefix('debits')->group(function () use($prefix_controller) {
        Route::get('/',         $prefix_controller . '@getPagination');        
        Route::get('/getAll',   $prefix_controller . '@getAll');        
        Route::get('/{id}',     $prefix_controller . '@get');
        Route::post('/',        $prefix_controller . '@create');
        Route::put('/{id}',     $prefix_controller . '@update');
        Route::delete('/{id}',  $prefix_controller . '@delete');
    });
});




################### Rotas para o modelo Credit ###############

Route::group(['namespace' => 'App\Dominio\Credit\\', 'prefix' => 'v1'], function () {
    $prefix_controller = "CreditApi";
    Route::prefix('credits')->group(function () use($prefix_controller) {
        Route::get('/',         $prefix_controller . '@getPagination');        
        Route::get('/getAll',   $prefix_controller . '@getAll');        
        Route::get('/{id}',     $prefix_controller . '@get');
        Route::post('/',        $prefix_controller . '@create');
        Route::put('/{id}',     $prefix_controller . '@update');
        Route::delete('/{id}',  $prefix_controller . '@delete');
    });
});




################### Rotas para o modelo State ###############

Route::group(['namespace' => 'App\Dominio\State\\', 'prefix' => 'v1'], function () {
    $prefix_controller = "StateApi";
    Route::prefix('states')->group(function () use($prefix_controller) {
        Route::get('/',         $prefix_controller . '@getPagination');        
        Route::get('/getAll',   $prefix_controller . '@getAll');        
        Route::get('/{id}',     $prefix_controller . '@get');
        Route::post('/',        $prefix_controller . '@create');
        Route::put('/{id}',     $prefix_controller . '@update');
        Route::delete('/{id}',  $prefix_controller . '@delete');
    });
});

################### Rotas para o modelo Moip ###############

Route::group(['namespace' => 'App\Dominio\Moip\\', 'prefix' => 'v1'], function () {
    $prefix_controller = "MoipApi";
    Route::prefix('moip')->group(function () use($prefix_controller) {
        Route::get('/',         $prefix_controller . '@getPagination');        
        Route::get('/getAll',   $prefix_controller . '@getAll');        
        Route::get('/{id}',     $prefix_controller . '@get');
        Route::post('/createUser',        $prefix_controller . '@createUser');
        Route::post('/',        $prefix_controller . '@create');
        Route::put('/{id}',     $prefix_controller . '@update');
        Route::delete('/{id}',  $prefix_controller . '@delete');
    });
});

//############### MODEL SETTINGS ############

Route::group(['namespace' => 'App\Dominio\Settings\\', 'prefix' => 'v1'], function () {
    $prefix_controller = 'SettingsApi';
    Route::prefix('settings')->group(function () use ($prefix_controller) {
        /*
         * Rotas para serviços relacionados a configuração
        */
        Route::get('/showConfigs', function(){
            print_r(config('app'));
        });
        Route::get('/', $prefix_controller.'@getAll');
        Route::get('/{id}', $prefix_controller.'@get');
        Route::post('/', $prefix_controller.'@create');
        Route::post('/byName', $prefix_controller.'@getByName');
        Route::post('/byNames', $prefix_controller.'@getByNames');
        Route::put('/{id}', $prefix_controller.'@update');
        Route::delete('/{id}', $prefix_controller.'@delete');
        Route::post('/group', $prefix_controller.'@getByGroup');
    });
});