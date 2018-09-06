<?php

namespace App\Dominio\Contest;

use App\Dominio\Game\Game;
use App\Dominio\Lottery\Lottery;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    
    protected $appends = [
        "game_name", "lotteries_number"
    ];

    public function getGameNameAttribute(){
        if($this->game()->exists())
            return $this->game()->first()->name;

        return null;
    }

    public function game(){
        return $this->belongsTo(Game::class);
    }

    public function lotterys(){
        return $this->hasMany(Lottery::class);
    }

    public function getLotteriesNumberAttribute(){
        return $this->lotterys()->count();
    }
}
