<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\UserOwnedScope;

class Purchase extends Model
{
    protected static function booted(): void
    {
        static::addGlobalScope(new UserOwnedScope);
    }

    protected $fillable = [
        'user_id',
        'supplier_name',
        'purchase_date',
        'total_amount',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
