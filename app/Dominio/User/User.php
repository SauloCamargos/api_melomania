<?php

namespace App\Dominio\User;

use App\Dominio\Record\Record;
use App\Dominio\Account\Account;
use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, EntrustUserTrait, HasApiTokens;
    const USER_ACTIVE = 1;
    const USER_BLOCKED = 2;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function account(){
        return $this->hasOne(Account::class);
    }

    public function records(){
        return $this->hasMany(Record::class);
    }
}
