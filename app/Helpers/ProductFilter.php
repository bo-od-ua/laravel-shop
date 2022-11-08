<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductFilter{
    private $builder;
    private $request;

    public function __construct(Request $request)
    {
        $this->request= $request;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder= $builder;
        foreach($this->request->query() as $filter=> $value){
            if(method_exists($this, $filter)){
                $this->$filter($value);
            }
        }
        return $this->builder;
    }

    public function price($value){
        if(in_array($value, ['min', 'max'])){
            $products= $this->builder->get();
            $count= $products->count();
            if($count> 1){
                $max= $this->builder->get()->max('price');
                $min= $this->builder->get()->min('price');
                $avg= ($min+ $max)* 0.5;

                if($value== 'min'){
                    $this->builder->where('price', '<=', $avg);
                }
                else{
                    $this->builder->where('price', '>=', $avg);
                }
            }
        }
    }

    public function new($value){
        if('yes'== $value){
            $this->builder->where('new', true);
        }
    }

    public function hit($value){
        if('yes'== $value){
            $this->builder->where('hit', true);
        }
    }

    public function sale($value){
        if('yes'== $value){
            $this->builder->where('sale', true);
        }
    }
}
