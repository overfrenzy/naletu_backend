<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    protected $fillable = [
        'name', 
        'description', 
        'mrp', 
        'selling_price', 
        'image',
        'category_id',
        'quantity_type_id',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();

        // Автоматически создавать slug перед сохранением
        static::saving(function ($product) {
            if (empty($product->slug)) {
                $slug = Str::slug($product->name);
                $product->slug = static::generateUniqueSlug($slug);
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('image')) {
                $oldImage = $product->getOriginal('image');
                if ($oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
        });

        static::deleting(function ($product) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
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
        while (DB::table('products')->where('slug', $slug)->exists()) {
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
            $this->attributes['image'] = ltrim(str_replace('public/', '', $value), '/');
        }
    }

    // Реляция с категорией
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Реляция с количеством
    public function quantityType()
    {
        return $this->belongsTo(QuantityType::class);
    }
}
