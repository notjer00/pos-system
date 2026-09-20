<?php

namespace App\Traits;

use App\Models\CommissionLog;
use App\Models\ProductVariant;
use App\Models\SalesTransaction;

trait HasReportQueries
{
    public function getSalesSummary(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $totalSales = SalesTransaction::where('status', 'completed')
            ->when($dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->sum('total_amount');

        $totalTransactions = SalesTransaction::where('status', 'completed')
            ->when($dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->count();

        $averageTransaction = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;

        return [
            'total_sales' => $totalSales,
            'total_transactions' => $totalTransactions,
            'average_transaction' => $averageTransaction,
        ];
    }

    public function getCommissionSummary(?string $dateFrom = null, ?string $dateTo = null): array
    {
        $grouped = CommissionLog::where('is_voided', false)
            ->whereHas('salesTransaction', fn ($q) => $q->where('status', 'completed'))
            ->when($dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn ($q) => $q->whereDate('created_at', '<=', $dateTo))
            ->select('user_id')
            ->selectRaw('SUM(commission_earned) as total_commission')
            ->selectRaw('SUM(final_price) as total_sales')
            ->selectRaw('COUNT(*) as transactions_count')
            ->groupBy('user_id')
            ->with('user')
            ->get();

        $byEmployee = $grouped->map(fn ($log) => [
            'user' => $log->user,
            'total_commission' => $log->total_commission,
            'total_sales' => $log->total_sales,
            'transactions_count' => $log->transactions_count,
        ])->values();

        return [
            'total_commission' => $byEmployee->sum('total_commission'),
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
