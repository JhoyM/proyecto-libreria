<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'type',
        'status'
    ];

    protected $attributes = [
        'status' => true,
        'type' => 'libros'
    ];
    
    protected static function booted()
    {
        static::saving(function ($category) {
            if (empty($category->slug)) {
                $category->slug = \Illuminate\Support\Str::slug($category->name);
            }
        });
    }

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = [
        'status_badge',
        'type_badge',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });

        static::updating(function ($category) {
            if ($category->isDirty('name')) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Scope a query to only include active categories.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', true);
    }

    /**
     * Scope a query to only include categories of a given type.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Get the products for the category.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the status badge HTML.
     *
     * @return string
     */
    public function getStatusBadgeAttribute()
    {
        return $this->status 
            ? '<span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Activo</span>'
            : '<span class="badge bg-secondary"><i class="fas fa-times-circle me-1"></i> Inactivo</span>';
    }

    /**
     * Get the type badge HTML.
     *
     * @return string
     */
    public function getTypeBadgeAttribute()
    {
        $typeNames = [
            'libros' => 'Libros',
            'utiles_escolares' => 'Útiles Escolares',
            'oficina' => 'Oficina',
        ];

        $badgeClass = [
            'libros' => 'bg-primary',
            'utiles_escolares' => 'bg-warning text-dark',
            'oficina' => 'bg-info text-dark',
        ][$this->type] ?? 'bg-secondary';

        $typeName = $typeNames[$this->type] ?? ucfirst($this->type);
        
        return sprintf(
            '<span class="badge %s">%s</span>',
            $badgeClass,
            e($typeName)
        );
    }

    /**
     * Get the display name of the type.
     *
     * @return string
     */
    public function getTypeNameAttribute()
    {
        return [
            'libros' => 'Libros',
            'utiles_escolares' => 'Útiles Escolares',
            'oficina' => 'Oficina',
        ][$this->type] ?? 'Sin tipo';
    }
}
