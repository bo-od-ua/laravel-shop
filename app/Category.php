<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function products(){
        return $this->hasMany(Product::class);
    }
//    public function getProducts(){
//        return Product::where('category_id', $this->id)->get();
//    }
}
