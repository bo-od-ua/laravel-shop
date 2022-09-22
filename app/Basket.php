<?php

namespace App;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Basket extends Model
{
    public function products(){
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public static function getOrCreate()
    {
        $basket_id= request()->cookie('basket_id');
        if(!empty($basket_id)){
            try{
                $basket= Basket::findOrFail($basket_id);
            }catch(ModelNotFoundException $e){
                $basket= Basket::create();
            }
        }
        else{
            $basket= Basket::create();
        }
        Cookie::queue('basket_id', $basket->id, 525600);
        return $basket;
    }

    public static function getCount()
    {
        $basket_id= request()->cookie('basket_id');
        if(empty($basket_id)){
            return 0;
        }

        return self::find($basket_id)->products->count();
    }

    public function increase($id, $count= 1)
    {
        $this->change($id, $count);
    }

    public function decrease($id, $count= 1)
    {
        $this->change($id, -1* $count);
    }

    public function change($id, $count= 0){
        if(!$count) {
            return;
        }

        if($this->products->contains($id)){
            $pivotRow = $this->products()->where('product_id', $id)->first()->pivot;
            $quantity = $pivotRow->quantity + $count;
            if ($quantity > 0) {
                $pivotRow->update(['quantity' => $quantity]);
            } else {
                $pivotRow->delete();
            }
        }elseif ($count> 0){
            $this->products()->attach($id, ['quantity' => $count]);
        }

        $this->touch();
    }
    public function remove($id)
    {
        $this->products()->detach($id);
        $this->touch();
    }
}

