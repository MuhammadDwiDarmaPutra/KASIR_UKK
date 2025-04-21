<?php

namespace App\Models;

use App\Models\User;
use App\Models\Member;
use App\Models\DetailOrder;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $fillable = [
        'member_id',
        'user_id',
        'total_price',
        'total_pay',
        'total_return',
        'poin',
        'total_poin',
    ];

    public function member(){
        return $this->belongsTo(Member::class, 'member_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(DetailOrder::class, 'transaction_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
