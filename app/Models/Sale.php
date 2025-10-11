<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    protected $fillable = [
        'user_id',
        'table_id',
        'cash_register_id',
        'total',
        'payment_method',
        'status',
        'paid_at',
        'paid_by',
    ];

    // Relación 1:1
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    // Relación 1:M
    public function items()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function table()
    {
        return $this->belongsTo(Table::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function cashRegister()
    {
        return $this->belongsTo(CashRegister::class);
    }
}