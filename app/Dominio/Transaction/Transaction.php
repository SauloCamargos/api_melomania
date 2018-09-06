<?php

namespace App\Dominio\Transaction;

use App\Dominio\Debit\Debit;
use App\Dominio\Record\Record;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function record(){
        return $this->belongsTo(Record::class);
    }

    public function debit(){
        return $this->hasOne(Debit::class);
    }
}
