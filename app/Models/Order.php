<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable= [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'comment',
        'amount',
        'status',
    ];
    public const STATUSES= [
        0=> 'Новый',
        1=> 'Обработан',
        2=> 'Оплачен',
        3=> 'Доставлен',
        4=> 'Завершен',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getCreatedAtAttribute($value){
        return Carbon::createFromFormat('Y-m-d H:i:s', $value)->timezone('Europe/Moscow');
    }

    public function getUpdatedAtAttribute($value){
        return Carbon::createFromFormat('Y-m-d H:i:s', $value)->timezone('Europe/Moscow');
    }
}
