<?php

namespace App\Http\Middleware;

use App\Models\CashRegister;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureCashRegisterIsOpen
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $cashRegister = CashRegister::where('status', 'abierta')
                                    ->where('user_id', Auth::id())
                                    ->latest()
                                    ->first();
        
        if (!$cashRegister) {
            return redirect()->route('cash-registers.open')
                            ->with('error', 'Debes abrir una caja antes de registrar ventas.');
        }
        
        return $next($request);
    }
}
