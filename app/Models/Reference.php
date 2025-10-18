<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    use HasFactory;
    
    // Toplu atama (Mass Assignment) için izin verilen alanlar
    protected $fillable = [
        'name',
        'title',
        'description',
        'website_url',
        'image_url',
        'order',
        'status',
    ];
    
    // Genellikle 'status' alanı boolean olarak tanımlanır
    protected $casts = [
        'status' => 'boolean',
    ];
    
    /**
     * Global Scope: Varsayılan olarak sıralamaya göre sıralar.
     */
    protected static function booted()
    {
        static::addGlobalScope('ordered', function (\Illuminate\Database\Eloquent\Builder $builder) {
            $builder->orderBy('order');
        });
    }
}