<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Slider extends Model
{
    protected $fillable = ['name', 'image', 'slug', 'description', 'image2', 'clickable'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($slider) {
            if (empty($slider->slug)) {
                $slug = Str::slug($slider->name);
                $slider->slug = static::generateUniqueSlug($slug);
            }
        });

        static::updating(function ($slider) {
            if ($slider->isDirty('image')) {
                $oldImage = $slider->getOriginal('image');
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            if ($slider->isDirty('image2')) {
                $oldImage2 = $slider->getOriginal('image2');
                if ($oldImage2) {
                    Storage::disk('public')->delete($oldImage2);
                }
            }
        });

        static::deleting(function ($slider) {
            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }
            if ($slider->image2) {
                Storage::disk('public')->delete($slider->image2);
            }
        });
    }

    public static function generateUniqueSlug($slug)
    {
        $originalSlug = $slug;
        $count = 1;

        while (DB::table('sliders')->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . ltrim($this->image, '/')) : null;
    }

    public function getImage2UrlAttribute()
    {
        return $this->image2 ? asset('storage/' . ltrim($this->image2, '/')) : null;
    }

    public function setImageAttribute($value)
    {
        if ($value) {
            // Если установлено новое изображение, удалить старое
            if (!empty($this->attributes['image']) && $this->attributes['image'] !== $value) {
                Storage::disk('public')->delete($this->attributes['image']);
            }
            $this->attributes['image'] = ltrim(str_replace('public/', '', $value), '/');
        } else {
            // Если значение не указано, удалить существующее изображение.
            if (!empty($this->attributes['image'])) {
                Storage::disk('public')->delete($this->attributes['image']);
            }
            $this->attributes['image'] = null;
        }
    }

    public function setImage2Attribute($value)
    {
        if ($value) {
            if (!empty($this->attributes['image2']) && $this->attributes['image2'] !== $value) {
                Storage::disk('public')->delete($this->attributes['image2']);
            }
            $this->attributes['image2'] = ltrim(str_replace('public/', '', $value), '/');
        } else {
            if (!empty($this->attributes['image2'])) {
                Storage::disk('public')->delete($this->attributes['image2']);
            }
            $this->attributes['image2'] = null;
        }
    }
}
