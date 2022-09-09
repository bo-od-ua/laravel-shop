<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function getProducts(){
        return Product::where('category_id', $this->id)->get();
    }
}
