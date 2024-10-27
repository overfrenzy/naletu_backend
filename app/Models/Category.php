<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    protected $fillable = ['name', 'image', 'slug'];

    protected static function boot()
    {
        parent::boot();

        // Автоматически создавать slug перед сохранением
        static::saving(function ($category) {
            if (empty($category->slug)) {
                $slug = Str::slug($category->name);
                $category->slug = static::generateUniqueSlug($slug);
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

    /**
     * Создать уникальный slug, добавив цифру, если slug уже существует.
     *
     * @param string $slug
     * @return string
     */
    public static function generateUniqueSlug($slug)
    {
        $originalSlug = $slug;
        $count = 1;

        // проверить, если slug уже в таблице продуктов
        while (DB::table('categories')->where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . ltrim($this->image, '/')) : null;
    }

    public function setImageAttribute($value)
    {
        if ($value) {
            if (!empty($this->attributes['image']) && $this->attributes['image'] !== $value) {
                Storage::disk('public')->delete($this->attributes['image']);
            }
            $this->attributes['image'] = ltrim(str_replace('public/', '', $value), '/');
        } else {
            if (!empty($this->attributes['image'])) {
                Storage::disk('public')->delete($this->attributes['image']);
            }
            $this->attributes['image'] = null;
        }
    }

    // Реляция с продуктами
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
