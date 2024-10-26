<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Slider extends Model
{
    protected $fillable = ['name', 'image', 'slug', 'description', 'image2'];

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
            $this->attributes['image'] = ltrim(str_replace('public/', '', $value), '/');
        }
    }

    public function setImage2Attribute($value)
    {
        if ($value) {
            $this->attributes['image2'] = ltrim(str_replace('public/', '', $value), '/');
        }
    }
}
