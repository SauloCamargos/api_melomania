<?php

namespace App\Dominio\Debit;

use App\Dominio\Record\Record;
use Illuminate\Database\Eloquent\Model;
use App\Dominio\Transaction\Transaction;

class Debit extends Model
{
    public function transaction(){
        return $this->belongsTo(Transaction::class);
    }

    public function record(){
        return $this->belongsTo(Record::class);
    }
}
