<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    use HasFactory;
    
    // Toplu atama (Mass Assignment) için izin verilen alanlar
    protected $fillable = [
        'title',
        'subtitle',
        'link',
        'button_text',
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