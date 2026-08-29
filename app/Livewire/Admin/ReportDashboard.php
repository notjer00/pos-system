<?php

namespace App\Livewire\Admin;

use App\Models\CommissionLog;
use App\Models\ProductVariant;
use App\Models\SalesTransaction;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ReportDashboard extends Component
{
    use WithPagination;

    public $dateFrom;

    public $dateTo;

    public $reportType = 'sales';

    public $exportFormat = 'pdf';

    public function mount(): void
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function getSalesSummary(): array
    {
        $query = SalesTransaction::with(['cashier', 'discount', 'lineItems.productVariant.product'])
            ->where('status', 'completed')
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo));

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

    public function getBestSellers(): array
    {
        return ProductVariant::select('product_variants.*', DB::raw('SUM(sale_line_items.quantity) as total_sold'))
            ->join('sale_line_items', 'product_variants.id', '=', 'sale_line_items.product_variant_id')
            ->join('sales_transactions', 'sale_line_items.sales_transaction_id', '=', 'sales_transactions.id')
            ->where('sales_transactions.status', 'completed')
            ->when($this->dateFrom, fn ($q) => $q->whereDate('sales_transactions.created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('sales_transactions.created_at', '<=', $this->dateTo))
            ->groupBy('product_variants.id')
            ->with('product')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get()
            ->toArray();
    }

    public function getCommissionSummary(): array
    {
        $query = CommissionLog::with(['user', 'salesTransaction'])
            ->where('is_voided', false)
            ->whereHas('salesTransaction', fn ($q) => $q->where('status', 'completed'))
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo));

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

    public function exportReport(): void
    {
        $this->dispatch('notify', message: "Exporting {$this->exportFormat} report... (Feature coming soon)");
    }

    public function render()
    {
        $salesSummary = $this->getSalesSummary();
        $bestSellers = $this->getBestSellers();
        $commissionSummary = $this->getCommissionSummary();
        $lowStockVariants = $this->getLowStockVariants();

        return view('livewire.admin.report-dashboard', [
            'salesSummary' => $salesSummary,
            'bestSellers' => $bestSellers,
            'commissionSummary' => $commissionSummary,
            'lowStockVariants' => $lowStockVariants,
        ]);
    }
}
