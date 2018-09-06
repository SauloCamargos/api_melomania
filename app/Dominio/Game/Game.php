<?php

namespace App\Dominio\Game;

use App\Dominio\State\State;
use App\Dominio\Contest\Contest;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{

    protected $appends = [
        "state_display", 'contests_number'
    ];

    public function getContestsNumberAttribute(){
        return $this->contests()->count();
    }

    public function getStateDisplayAttribute() {
        $state = $this->state()->first();

        return $state->name." (".$state->initials.")";
    }

    public function state(){
        return $this->belongsTo(State::class);
    }

    public function contests(){
        return $this->hasMany(Contest::class);
    }
}
