<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function products(){
        return $this->hasMany(Product::class);
    }

    public static function roots(){
        return self::where('parent_id', 0)->get();
    }
}
