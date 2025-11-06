<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\CashRegister;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashRegisterController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin', 'vendedor', 'caja'])) {
            abort(403, 'No tienes permisos para aceder al sistema POS');
        }

        $cashRegisters = CashRegister::with('user')
        ->when($request->from, fn($q) => $q->whereDate('opened_at', '>=', $request->from))
        ->when($request->to, fn($q) => $q->whereDate('opened_at', '<=', $request->to))
        ->when($request->status, fn($q) => $q->where('status', $request->status))
        ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
        ->orderBy('opened_at', 'desc')
        ->paginate(15);

        $users = User::all();

        return view('cash-registers.index', compact('cashRegisters', 'users'));
    }

    public function openForm()
    {
        return view('cash-registers.open');
    }

    public function closeForm(CashRegister $cashRegister)
    {
        if ($cashRegister->status === 'cerrada') {
            return redirect()->route('cash-registers.report', $cashRegister->id)
                            ->with('error', 'La caja ya está cerrada.');
        }

        $salesByUser = $cashRegister->sales()
            ->where('status', 'pagado')
            ->with('user')
            ->get()
            ->groupBy('user_id')
            ->map(function ($sales) {
                return [
                    'count' => $sales->count(),
                    'total' => $sales->sum('total'),
                    'user' => $sales->first()->user,
                ];
            });

        return view('cash-registers.close', compact('cashRegister', 'salesByUser'));
    }

    public function open(Request $request)
    {
        $request->validate([
            'opening_amount' => 'required|numeric|min:0',
        ]);

        $alreadyOpen = CashRegister::where('status', 'abierta')
                        ->where('user_id', Auth::id())
                        ->exists();

        if ($alreadyOpen) {
            return redirect()->route('cash-registers.index')
                            ->with('error', 'Ya tienes una caja abierta.');
        }

        CashRegister::create([
            'user_id' => Auth::id(),
            'opening_amount' => $request->opening_amount,
            'opened_at' => now(),
            'status' => 'abierta',
        ]);

        return redirect()->route('sales.index')
                        ->with('success', 'Caja abierta exitosamente.');
    }

    public function close(Request $request, CashRegister $cashRegister)
    {
        if ($cashRegister->status === 'cerrada') {
            return back()->with('error', 'La caja ya está cerrada.');
        }

        $salesTotal = $cashRegister->sales()->where('status', 'pagado')->sum('total');

        $expectedAmount = $cashRegister->opening_amount + $salesTotal;
        $closingAmount  = $request->input('closing_amount');
        $difference     = $closingAmount - $expectedAmount;

        $request->validate([
            'closing_amount' => 'required|numeric|min:0'
        ]);
        
        $cashRegister->update([
            'expected_amount' => $expectedAmount,
            'closing_amount'  => $closingAmount,
            'difference'      => $difference, 
            'closed_at'       => now(),
            'status'          => 'cerrada',
        ]);

        return redirect()->route('cash-registers.report', $cashRegister->id)
                        ->with('success', 'Caja cerrada exitosamente.');
    }

    public function report(CashRegister $cashRegister)
    {
        $payments = $cashRegister->sales()->with('payments.user')->get()->flatMap->payments;

        $resumenPorMetodo = $payments->groupBy('payment_method')->map(function($group){
            return $group->sum('amount');
        });

        return view('cash-registers.report', compact('cashRegister', 'payments', 'resumenPorMetodo'));
    }

    public function show(CashRegister $cashRegister)
    {
        $cashRegister->load(['user', 'sales.user']);
        return view('cash-registers.show', compact('cashRegister'));
    }

    public function export(CashRegister $cashRegister)
    {
        $cashRegister->load(['user', 'sales.payments.user']);

        $payments = $cashRegister->sales()->with('payments.user')->get()->flatMap->payments;

        $resumenPorMetodo = $payments->groupBy('payment_method')->map(function($group){
            return $group->sum('amount');
        });

        $pdf = Pdf::loadView('cash-registers.export', compact('cashRegister', 'payments', 'resumenPorMetodo'))
                ->setPaper('a4', 'portrait');
        return $pdf->stream('reporte_caja_'.$cashRegister->id.'.pdf');
    }
}
