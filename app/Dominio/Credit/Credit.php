<?php

namespace App\Dominio\Credit;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    public function record(){
        return $this->belongsTo(Record::class);
    }
}
