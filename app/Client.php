<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $guarded = [];


    public function orders(){
       return $this->hasMany(order::class);
    }
}
