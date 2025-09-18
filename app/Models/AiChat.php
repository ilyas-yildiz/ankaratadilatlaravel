<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiChat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'history',
    ];

    /**
     * 'history' sütununun otomatik olarak PHP dizisine çevrilmesini sağlar.
     */
    protected $casts = [
        'history' => 'array',
    ];

    /**
     * Bu sohbetin hangi kullanıcıya ait olduğunu belirten ilişki.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
