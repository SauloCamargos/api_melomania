<?php

namespace App\Dominio\Lottery;

use App\Dominio\Record\Record;
use App\Dominio\Contest\Contest;
use Illuminate\Database\Eloquent\Model;

class Lottery extends Model
{
    
    protected $fillable = [
        'name', 'hit'
    ];

    public function contest(){
        return $this->belongsTo(Contest::class);
    }

    public function records(){
        return $this->hasMany(Record::class);
    }
}
