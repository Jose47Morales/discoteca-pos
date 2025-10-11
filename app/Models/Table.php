<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
    ];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function currentSale()
    {
        return $this->hasOne(Sale::class)->where('status', 'pendiente');
    }

    public function scopeAvailable($query)
    {
        return $query->whereDoesntHave('currentSale');
    }

    public function scopeOccupied($query)
    {
        return $query->whereHas('currentSale');
    }

    public function getIsOccupiedAttribute()
    {
        return $this->currentSale !== null;
    }
}
