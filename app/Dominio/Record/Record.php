<?php

namespace App\Dominio\Record;

use App\Dominio\Debit\Debit;
use App\Dominio\Credit\Credit;
use Illuminate\Database\Eloquent\Model;
use App\Dominio\Transaction\Transaction;

class Record extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function transaction(){
        return $this->hasOne(Transaction::class);
    }
    public function debit(){
        return $this->hasOne(Debit::class);
    }
    public function credit(){
        return $this->hasOne(Credit::class);
    }
}
