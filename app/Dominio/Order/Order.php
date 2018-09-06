<?php

namespace App\Dominio\Order;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const RESERVED = 1;
    const REQUESTED = 2;
    const SOLD = 3;
    const BLOCKED = 4;

    public function records()
    {
        return $this->hasMany(Record::class);
    }
}
