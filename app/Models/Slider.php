<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = ['name', 'image', 'type'];

    protected static function boot()
    {
        parent::boot();

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
}
