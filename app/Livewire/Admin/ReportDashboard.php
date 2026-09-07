<?php

namespace App\Livewire\Admin;

use App\Models\ProductVariant;
use App\Traits\HasReportQueries;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class ReportDashboard extends Component
{
    use HasReportQueries, WithPagination;

    public $dateFrom;

    public $dateTo;

    public $reportType = 'sales';

    public $exportFormat = 'pdf';

    public function mount(): void
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
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

    public function exportReport(): void
    {
        $this->dispatch('notify', message: "Exporting {$this->exportFormat} report... (Feature coming soon)");
    }

    public function render()
    {
        $salesSummary = $this->getSalesSummary($this->dateFrom, $this->dateTo);
        $bestSellers = $this->getBestSellers();
        $commissionSummary = $this->getCommissionSummary($this->dateFrom, $this->dateTo);
        $lowStockVariants = $this->getLowStockVariants();

        return view('livewire.admin.report-dashboard', [
            'salesSummary' => $salesSummary,
            'bestSellers' => $bestSellers,
            'commissionSummary' => $commissionSummary,
            'lowStockVariants' => $lowStockVariants,
        ]);
    }
}
