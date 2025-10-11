<?php

use App\Http\Controllers\CashRegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;

// Página Principal
Route::get('/', function () {
    return view('welcome');
});

// Ventas

Route::middleware(['auth', 'role_or_permission:admin|ventas.create|ventas.read|ventas.update|ventas.delete'])
    ->group(function () {
        Route::resource('sales', SaleController::class);
        Route::patch('/sales/{sale}/pay', [SaleController::class, 'pay'])->name('sales.pay');
    });

// Cajas

Route::middleware(['auth', 'role_or_permission:admin|cajas.open|cajas.read|cajas.close'])
    ->group(function () {
        Route::get('cash-registers/open', [CashRegisterController::class, 'openForm'])->name('cash-registers.open');
        Route::post('cash-registers/open', [CashRegisterController::class, 'open'])->name('cash-registers.open.store');
        Route::get('cash-registers/{cashRegister}/close', [CashRegisterController::class, 'closeForm'])->name('cash-registers.close');
        Route::put('cash-registers/{cashRegister}/close', [CashRegisterController::class, 'close'])->name('cash-registers.close.store');
        Route::get('cash-register/{cashRegister}/report', [CashRegisterController::class, 'report'])->name('cash-registers.report');
        Route::resource('cash-registers', CashRegisterController::class);
    });

// Gastos

Route::middleware(['auth', 'role_or_permission:admin|gastos.create|gastos.read|gastos.update|gastos.delete'])
    ->group(function () {

    });

// Inventario

Route::middleware(['auth', 'role_or_permission:admin|inventario.create|inventario.read|inventario.update|inventario.delete'])
    ->group(function () {

    });

// Mesas

Route::middleware(['auth', 'role_or_permission:admin|mesas.create|mesas.update|mesas.delete'])
    ->group(function () {
        Route::resource('tables', TableController::class);
    });

// Productos

Route::middleware(['auth', 'role_or_permission:admin|productos.create|productos.update|productos.delete'])
    ->group(function () {
        Route::resource('products', ProductController::class);
    });

// Estadisticas

Route::middleware(['auth', 'role_or_permission:admin|ventas_y_cajas.report|dashboard.show'])
    ->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/pdf/{type}', [ReportController::class, 'exportPdf'])->name('reports.pdf');
        Route::get('/reports/excel/{type}', [ReportController::class, 'exportExcel'])->name('reports.excel');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('cash-registers/{cashRegister}/export', [CashRegisterController::class, 'export'])->name('cash-registers.export');
    });

// Usuarios

Route::middleware(['auth', 'role_or_permission:admin|usuarios.create|usuarios.update|usuarios.delete'])
    ->group(function () {
        Route::resource('users', UserController::class);
    });

// Perfil

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [App\Http\Controllers\ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Roles

Route::middleware(['auth', 'role_or_permission:admin|roles.create|roles.read|roles.update|roles.delete'])
    ->group(function () {
        Route::resource('roles', RoleController::class);
    });

require __DIR__.'/auth.php';
