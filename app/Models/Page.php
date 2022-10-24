<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable= [
        'name',
        'slug',
        'content',
        'parent_id',
    ];

    public function children(){
        return $this->hasMany(Page::class, 'parent_id');
    }

    public function parent(){
        return $this->belongsTo(Page::class);
    }

    public function getRouteKeyName(): string // 577
    {
        $current= \Route::currentRouteName();
        if('page.show'== $current){
            return 'slug';
        }
        return 'id';
    }
}
