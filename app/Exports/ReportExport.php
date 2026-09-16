<?php

namespace App\Exports;

class ReportExport
{
    public function __construct(
        public array $salesSummary,
        public array $bestSellers,
        public array $commissionSummary,
        public array $lowStockVariants,
        public string $dateFrom,
        public string $dateTo,
    ) {}

    public function generateCsv(string $path): void
    {
        $file = fopen($path, 'w');

        $this->writeHeader($file, 'SALES SUMMARY');
        $this->writeSalesSummary($file);

        $this->writeHeader($file, 'TOP 10 BEST SELLERS');
        $this->writeBestSellers($file);

        $this->writeHeader($file, 'COMMISSION SUMMARY');
        $this->writeCommissionSummary($file);

        $this->writeHeader($file, 'LOW STOCK ALERTS');
        $this->writeLowStock($file);

        fclose($file);
    }

    private function writeHeader($file, string $title): void
    {
        fputcsv($file, [$title]);
        fputcsv($file, ['Report Period', "{$this->dateFrom} to {$this->dateTo}"]);
        fputcsv($file, []);
    }

    private function writeSalesSummary($file): void
    {
        fputcsv($file, ['Metric', 'Value']);
        fputcsv($file, ['Total Sales', number_format($this->salesSummary['total_sales'], 2)]);
        fputcsv($file, ['Total Transactions', $this->salesSummary['total_transactions']]);
        fputcsv($file, ['Average Transaction', number_format($this->salesSummary['average_transaction'], 2)]);
        fputcsv($file, []);
    }

    private function writeBestSellers($file): void
    {
        fputcsv($file, ['Rank', 'Product', 'Variant', 'Units Sold']);

        foreach ($this->bestSellers as $index => $seller) {
            fputcsv($file, [
                $index + 1,
                $seller['product']['name'] ?? 'N/A',
                "{$seller['size']}/{$seller['color']}",
                $seller['total_sold'],
            ]);
        }

        if (empty($this->bestSellers)) {
            fputcsv($file, ['No sales data for this period']);
        }

        fputcsv($file, []);
    }

    private function writeCommissionSummary($file): void
    {
        fputcsv($file, ['Total Commission', number_format($this->commissionSummary['total_commission'], 2)]);
        fputcsv($file, []);

        fputcsv($file, ['Employee', 'Commission', 'Sales', 'Transactions']);

        foreach ($this->commissionSummary['by_employee'] as $emp) {
            fputcsv($file, [
                $emp['user']->name,
                number_format($emp['total_commission'], 2),
                number_format($emp['total_sales'], 2),
                $emp['transactions_count'],
            ]);
        }

        if (empty($this->commissionSummary['by_employee'])) {
            fputcsv($file, ['No commission data for this period']);
        }

        fputcsv($file, []);
    }

    private function writeLowStock($file): void
    {
        fputcsv($file, ['Product', 'Variant', 'Current Stock', 'Threshold', 'Status']);

        foreach ($this->lowStockVariants as $variant) {
            fputcsv($file, [
                $variant['product']['name'],
                "{$variant['size']}/{$variant['color']}",
                $variant['current_stock'],
                $variant['low_stock_threshold'],
                'Low Stock',
            ]);
        }

        if (empty($this->lowStockVariants)) {
            fputcsv($file, ['No low stock items']);
        }
    }
}
