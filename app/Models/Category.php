<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'image'];

    protected static function boot()
    {
        parent::boot();

        // Автоматически создавать идентифайер перед сохранением
        static::saving(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('image')) {
                $oldImage = $category->getOriginal('image');
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        });

        static::deleting(function ($category) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
        });
    }
    
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . ltrim($this->image, '/')) : null;
    }
    
    public function setImageAttribute($value)
    {
        if ($value) {
            $this->attributes['image'] = ltrim(str_replace('public/', '', $value), '/');
        }
    }    

    // Реляция с продуктами
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}