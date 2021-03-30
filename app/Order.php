<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $gourder=[];
    protected $fillable = ['price','area','created_at','updated_at'];
    protected $table = 'Orders';



    public function client(){

        return $this->belongsTo(client::class);
 
     }


     public function products(){

        return $this->belongsToMany(product::class,'product_order')->withPivot(['quantity','created_at','updated_at']);
 
     }

}
