<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class MongoBrand extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'brands';
    
    protected $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Auto-generate slug when name is set
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = \Illuminate\Support\Str::slug($value);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}