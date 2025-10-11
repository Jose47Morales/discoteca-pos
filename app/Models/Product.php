<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'bebidas' => 'Bebidas',
        'cocteles' => 'Cocteles',
        'snacks' => 'Snacks',
        'entrada' => 'Entrada',
        'otro' => 'Otro',
    ];

    protected $fillable = [
        'name',
        'category',
        'price',
        'stock',
        'min_stock',
    ];

    // Relación 1:M
    public function saleDetails(){
        return $this->hasMany(SaleDetail::class);
    }

    public function getCategoryLabelAttribute()
    {
        return self::CATEGORIES[$this->category] ?? ucfirst($this->category);
    }
}