<?php

use App\Livewire\Admin\DiscountManagement;
use App\Livewire\Admin\ProductManagement;
use App\Livewire\Admin\ReportDashboard;
use App\Livewire\Admin\TransactionHistory;
use App\Livewire\Admin\UserManagement;
use App\Livewire\Cashier\Checkout;
use App\Livewire\Cashier\RecentTransactions;
use App\Livewire\Inbox;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::get('admin/products', ProductManagement::class)->name('admin.products');
        Route::get('admin/discounts', DiscountManagement::class)->name('admin.discounts');
        Route::get('admin/reports', ReportDashboard::class)->name('admin.reports');
        Route::get('admin/users', UserManagement::class)->name('admin.users');
        Route::get('admin/transactions', TransactionHistory::class)->name('admin.transactions');
        Route::get('admin/inbox', Inbox::class)->name('admin.inbox');
    });

    Route::middleware('role:cashier')->group(function () {
        Route::get('cashier/checkout', Checkout::class)->name('cashier.checkout');
        Route::get('cashier/transactions', RecentTransactions::class)->name('cashier.transactions');
        Route::get('cashier/inbox', Inbox::class)->name('cashier.inbox');
    });
});

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
