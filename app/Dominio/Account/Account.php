<?php

namespace App\Dominio\Account;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    //

    public function user(){
        return $this->belongsTo(User::class);
    }
}
