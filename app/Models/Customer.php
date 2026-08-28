<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\UserOwnedScope;

class Customer extends Model
{
    use SoftDeletes;
    protected static function booted(): void
    {
        static::addGlobalScope(new UserOwnedScope);
    }

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
