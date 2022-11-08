<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $fillable= [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'content',
        'image',
        'price',
        'new',
        'hit',
        'sale'
    ];
    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function brand(){
        return $this->belongsTo(Brand::class);
    }

    public function baskets(){
        return $this->belongsToMany(Basket::class)->withPivot('quantity');
    }

    /**
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param integer $id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCategoryProducts(Builder $builder, $id){
        $descendants= Category::getAllChildren($id);
        $descendants[]= $id;
        return $builder->whereIn('category_id', $descendants);
    }

    /**
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param array $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilterProducts($builder, $filters){
        return $filters->apply($builder);
    }
}
