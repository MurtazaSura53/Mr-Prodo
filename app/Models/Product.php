<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\UserOwnedScope;

class Product extends Model
{
    use SoftDeletes;
    protected static function booted(): void
    {
        static::addGlobalScope(new UserOwnedScope);
    }

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'stock',
        'unit',
        'purchase_price',
        'selling_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }
    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}
