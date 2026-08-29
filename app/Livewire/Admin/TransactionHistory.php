<?php

namespace App\Livewire\Admin;

use App\Models\SalesTransaction;
use App\Services\TransactionReversalService;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class TransactionHistory extends Component
{
    use WithPagination;

    public $search = '';

    public $statusFilter = 'all';

    public $dateFrom;

    public $dateTo;

    public $showVoidModal = false;

    public $showRefundModal = false;

    public $selectedTransaction = null;

    public $reversalNote = '';

    protected $listeners = ['refreshTransactions' => '$refresh'];

    public function mount(): void
    {
        $this->dateFrom = now()->subDays(30)->format('Y-m-d');
        $this->dateTo = now()->format('Y-m-d');
    }

    public function openVoidModal(SalesTransaction $transaction): void
    {
        $this->selectedTransaction = $transaction;
        $this->reversalNote = '';
        $this->showVoidModal = true;
    }

    public function openRefundModal(SalesTransaction $transaction): void
    {
        $this->selectedTransaction = $transaction;
        $this->reversalNote = '';
        $this->showRefundModal = true;
    }

    public function processVoid(TransactionReversalService $service): void
    {
        try {
            $service->void($this->selectedTransaction, auth()->user(), $this->reversalNote);
            $this->showVoidModal = false;
            $this->reversalNote = '';
            $this->dispatch('notify', message: 'Transaction voided successfully!');
        } catch (ValidationException $e) {
            $this->dispatch('notify', message: $e->validator->errors()->first(), type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Error: '.$e->getMessage(), type: 'error');
        }
    }

    public function processRefund(TransactionReversalService $service): void
    {
        try {
            $service->refund($this->selectedTransaction, auth()->user(), $this->reversalNote);
            $this->showRefundModal = false;
            $this->reversalNote = '';
            $this->dispatch('notify', message: 'Transaction refunded successfully!');
        } catch (ValidationException $e) {
            $this->dispatch('notify', message: $e->validator->errors()->first(), type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Error: '.$e->getMessage(), type: 'error');
        }
    }

    public function closeModals(): void
    {
        $this->showVoidModal = false;
        $this->showRefundModal = false;
        $this->selectedTransaction = null;
        $this->reversalNote = '';
    }

    public function getStatusBadgeClass(string $status): string
    {
        return match ($status) {
            'completed' => 'bg-green-100 text-green-800',
            'voided' => 'bg-yellow-100 text-yellow-800',
            'refunded' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function canVoid(SalesTransaction $transaction): bool
    {
        return $transaction->isCompleted() && $transaction->created_at->isToday();
    }

    public function canRefund(SalesTransaction $transaction): bool
    {
        return $transaction->isCompleted();
    }

    public function render()
    {
        $transactions = SalesTransaction::with(['cashier', 'discount', 'lineItems.productVariant.product'])
            ->when($this->search, function ($query) {
                $query->where('id', 'like', "%{$this->search}%")
                    ->orWhereHas('cashier', fn ($q) => $q->where('name', 'like', "%{$this->search}%"));
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->dateFrom, fn ($q) => $q->whereDate('created_at', '>=', $this->dateFrom))
            ->when($this->dateTo, fn ($q) => $q->whereDate('created_at', '<=', $this->dateTo))
            ->latest()
            ->paginate(15);

        return view('livewire.admin.transaction-history', [
            'transactions' => $transactions,
        ]);
    }
}
