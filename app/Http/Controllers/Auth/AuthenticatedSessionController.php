<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    protected function authenticated($request, $user)
    {
        if ($user->role === 'admin') {
            return redirect()->route('dashboard');
        }

        if ($user->role === 'vendedor') {
            return redirect()->route('sales.index');
        }

        return redirect('/sales.index');
    }

    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login')->withErrors(['email' => 'Credenciales inválidas.']);
        }
        
        if ($user->role === 'admin') {
            return redirect()->route('dashboard');
        }

        return redirect()->route('sales.index');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
