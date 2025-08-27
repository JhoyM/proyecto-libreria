<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    // Dashboard principal
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Redirigir la ruta raíz autenticada al dashboard
    Route::redirect('/', '/dashboard');

    // Perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Módulo de productos
    Route::resource('products', ProductController::class);
    
    // Módulo de categorías
    Route::resource('categories', CategoryController::class);

    // Módulo de proveedores
    // Nota: vistas protegidas a nivel de UI con @canany; políticas/permits pueden añadirse luego
    Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);

    // Módulo de clientes
    Route::resource('customers', CustomerController::class);

    // Módulo de ventas
    Route::get('sales/report', [SalesController::class, 'report'])->name('sales.report');
    Route::middleware(['role:Administrador'])->group(function () {
        Route::get('sales/report/by-period', [SalesController::class, 'reportByPeriod'])->name('sales.report.by_period');
        Route::get('sales/report/by-category', [SalesController::class, 'reportByCategory'])->name('sales.report.by_category');
    });
    Route::resource('sales', SalesController::class)->only(['index','create','store','show','edit','update','destroy']);

    // Módulo de gestión de usuarios (solo para administradores)
    Route::prefix('admin')->name('admin.')->middleware(['role:Administrador'])->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    });

    // (Deprecated) Reportes generales separados — dejamos rutas por si hay enlaces guardados
    // Route::middleware(['role:Administrador'])->group(function () {
    //     Route::get('/reports/sales', [ReportsController::class, 'sales'])->name('reports.sales');
    //     Route::get('/reports/sales/by-month', [ReportsController::class, 'salesByMonth'])->name('reports.sales.by_month');
    //     Route::get('/reports/sales/by-category', [ReportsController::class, 'salesByCategory'])->name('reports.sales.by_category');
    // });
});

require __DIR__.'/auth.php';
