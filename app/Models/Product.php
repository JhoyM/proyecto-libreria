<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'description', 'category_id', 'supplier_id',
        'purchase_price', 'sale_price', 'stock', 'min_stock',
        'author', 'publisher', 'isbn', 'publication_year', 'genre', 'pages', 'language',
        'brand', 'model', 'color', 'size',
        'image_path', 'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
