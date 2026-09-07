<?php

namespace App\Traits;

use App\Models\CommissionLog;
use App\Models\ProductVariant;
use App\Models\SalesTransaction;

trait HasReportQueries
{
    public function getSalesSummary(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $query = SalesTransaction::with(['cashier', 'discount', 'lineItems.productVariant.product'])
            ->where('status', 'completed')
            ->when($dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('created_at', '<=', $dateTo));

        $transactions = $query->get();

        $totalSales = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        $averageTransaction = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;

        return [
            'total_sales' => $totalSales,
            'total_transactions' => $totalTransactions,
            'average_transaction' => $averageTransaction,
            'transactions' => $transactions,
        ];
    }

    public function getCommissionSummary(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $query = CommissionLog::with(['user', 'salesTransaction'])
            ->where('is_voided', false)
            ->whereHas('salesTransaction', fn ($q) => $q->where('status', 'completed'))
            ->when($dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('created_at', '<=', $dateTo));

        $logs = $query->get();

        $byEmployee = $logs->groupBy('user_id')->map(function ($employeeLogs) {
            return [
                'user' => $employeeLogs->first()->user,
                'total_commission' => $employeeLogs->sum('commission_earned'),
                'total_sales' => $employeeLogs->sum('final_price'),
                'transactions_count' => $employeeLogs->count(),
            ];
        })->values();

        return [
            'total_commission' => $logs->sum('commission_earned'),
            'by_employee' => $byEmployee,
        ];
    }

    public function getLowStockVariants(): array
    {
        return ProductVariant::with('product')
            ->whereColumn('current_stock', '<=', 'low_stock_threshold')
            ->orderBy('current_stock')
            ->get()
            ->toArray();
    }

    public function getDailySales(int $days = 7): array
    {
        $startDate = now()->subDays($days - 1)->startOfDay();
        $endDate = now()->endOfDay();

        $salesByDay = SalesTransaction::where('status', 'completed')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total, COUNT(*) as count')
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $result = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $key = $date->format('Y-m-d');
            $result[] = [
                'date' => $date->format('M d'),
                'day' => $date->format('D'),
                'total' => $salesByDay[$key]->total ?? 0,
                'count' => $salesByDay[$key]->count ?? 0,
            ];
        }

        return $result;
    }
}
