<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashRegister extends Model
{
    use HasFactory;

    protected $casts = [
        'opened_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    protected $fillable = [
        'user_id',        
        'opening_amount',        
        'closing_amount',        
        'opened_at',        
        'closed_at',        
        'status',       
        'expected_amount',
        'difference', 
        ''
    ];

    public function open($userId, $openingBalance = 0)
    {
        $this->update([
            'user_id' => $userId,
            'opened_at' => now(),
            'status' => 'abierta',
            'opening_balance' => $openingBalance,
        ]);
    }

    public function close($closingBalance = 0)
    {
        $this->update([
            'closed_at' => now(),
            'status' => 'cerrada',
            'closing_balance' => $closingBalance,
        ]);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
