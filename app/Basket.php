<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    public function products(){
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }
}
