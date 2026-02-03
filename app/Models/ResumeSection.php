<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResumeSection extends Model
{
    protected $fillable = [
        'title',
        'type',
        'content',
        'data',
        'order',
        'is_active'
    ];

     protected $casts = [
        'data' => 'array',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
