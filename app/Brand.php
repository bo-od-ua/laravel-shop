<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    public function products(){
        return $this->hasMany(Product::class);
    }
//    public function getProducts(){
//        return Product::where('brand_id', $this->id)->get();
//    }
}
