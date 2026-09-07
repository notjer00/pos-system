<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\SaleLineItem;
use App\Models\SalesTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $user = Auth::user();
        $today = Carbon::today();

        if ($user->isAdmin()) {
            return view('dashboard', $this->adminStats($today));
        }

        return view('dashboard', $this->cashierStats($user, $today));
    }

    private function adminStats(Carbon $today): array
    {
        $todaySales = SalesTransaction::where('status', 'completed')
            ->whereDate('created_at', $today)
            ->sum('total_amount');

        $todayTransactions = SalesTransaction::where('status', 'completed')
            ->whereDate('created_at', $today)
            ->count();

        $totalProducts = Product::where('is_active', true)->count();

        $activeDiscounts = Discount::where('is_active', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', $today);
            })
            ->count();

        $lowStockVariants = ProductVariant::where('current_stock', '<=', 5)
            ->where('current_stock', '>', 0)
            ->count();

        $outOfStockVariants = ProductVariant::where('current_stock', 0)->count();

        $totalCashiers = User::where('role', 'cashier')->count();

        $recentTransactions = SalesTransaction::with('cashier')
            ->whereDate('created_at', $today)
            ->latest()
            ->take(5)
            ->get();

        return compact(
            'todaySales',
            'todayTransactions',
            'totalProducts',
            'activeDiscounts',
            'lowStockVariants',
            'outOfStockVariants',
            'totalCashiers',
            'recentTransactions',
        );
    }

    private function cashierStats(User $user, Carbon $today): array
    {
        $todaySales = SalesTransaction::where('cashier_id', $user->id)
            ->where('status', 'completed')
            ->whereDate('created_at', $today)
            ->sum('total_amount');

        $todayTransactions = SalesTransaction::where('cashier_id', $user->id)
            ->where('status', 'completed')
            ->whereDate('created_at', $today)
            ->count();

        $todayItems = SaleLineItem::whereHas('salesTransaction', function ($q) use ($user, $today) {
            $q->where('cashier_id', $user->id)
                ->where('status', 'completed')
                ->whereDate('created_at', $today);
        })->sum('quantity');

        $todayCommission = $user->commissionLogs()
            ->whereDate('created_at', $today)
            ->where('is_voided', false)
            ->sum('commission_earned');

        $recentTransactions = SalesTransaction::with('lineItems')
            ->where('cashier_id', $user->id)
            ->whereDate('created_at', $today)
            ->latest()
            ->take(5)
            ->get();

        return compact(
            'todaySales',
            'todayTransactions',
            'todayItems',
            'todayCommission',
            'recentTransactions',
        );
    }
}
