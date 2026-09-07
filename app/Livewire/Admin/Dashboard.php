<?php

namespace App\Livewire\Admin;

use App\Models\Discount;
use App\Models\Product;
use App\Models\SalesTransaction;
use App\Traits\HasReportQueries;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class Dashboard extends Component
{
    use HasReportQueries;

    public function render()
    {
        $today = now()->toDateString();
        $weekStart = now()->startOfWeek()->toDateString();

        $dailySales = $this->getDailySales(7);

        $maxDailySale = collect($dailySales)->max('total');

        $todaySales = SalesTransaction::where('status', 'completed')
            ->whereDate('created_at', $today)
            ->sum('total_amount');

        $weekSales = SalesTransaction::where('status', 'completed')
            ->whereDate('created_at', '>=', $weekStart)
            ->sum('total_amount');

        $todayTransactions = SalesTransaction::where('status', 'completed')
            ->whereDate('created_at', $today)
            ->count();

        $weekTransactions = SalesTransaction::where('status', 'completed')
            ->whereDate('created_at', '>=', $weekStart)
            ->count();

        $todayCommission = $this->getCommissionSummary($today, $today);
        $weekCommission = $this->getCommissionSummary($weekStart, $today);

        $lowStockVariants = $this->getLowStockVariants();

        $totalProducts = Product::count();
        $activeDiscounts = Discount::where('is_active', true)->count();

        return view('livewire.admin.dashboard', [
            'dailySales' => $dailySales,
            'maxDailySale' => $maxDailySale,
            'todaySales' => $todaySales,
            'weekSales' => $weekSales,
            'todayTransactions' => $todayTransactions,
            'weekTransactions' => $weekTransactions,
            'todayCommission' => $todayCommission,
            'weekCommission' => $weekCommission,
            'lowStockVariants' => $lowStockVariants,
            'totalProducts' => $totalProducts,
            'activeDiscounts' => $activeDiscounts,
        ]);
    }
}
