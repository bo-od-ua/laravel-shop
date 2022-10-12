<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable= [
        'parent_id',
        'name',
        'slug',
        'content',
        'image'
    ];
    public function products(){
        return $this->hasMany(Product::class);
    }

    public function children(){
        return $this->hasMany(Category::class, 'parent_id');
    }

    public static function roots(){
        return self::where('parent_id', 0)->with('children')->get();
    }

    public function validParent($id){
        $id= (int)$id;

        $ids= $this->getAllChildren($this->id);
        $ids[]= $this->id;

        return ! in_array($id, $ids);
    }

    public function getAllChildren($id){
        $children= self::where('parent_id', $id)->with('children')->get();
        $ids= [];

        foreach ($children as $child){
            $ids[]= $child->id;

            if($child->children->count()){
                $ids= array_merge($ids, $this->getAllChildren($child->id));
            }
        }

        return $ids;
    }

    public function descendants(){
        return $this->hasMany(Category::class, 'parent_id')->with('descendants');
    }

    public function hierarchy(){
        return self::where('parent_id', 0)->with('descendants')->get();
    }
}
