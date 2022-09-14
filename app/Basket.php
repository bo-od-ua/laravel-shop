<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Basket extends Model
{
    public function products(){
        return $this->belongsToMany(Product::class)->withPivot('quantity');
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

