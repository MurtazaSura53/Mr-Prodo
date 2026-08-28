<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\UserOwnedScope;

class Sale extends Model
{
    protected static function booted(): void
    {
        static::addGlobalScope(new UserOwnedScope);
    }

    protected $fillable = [
        'user_id',
        'customer_id',
        'sale_date',
        'total_amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
