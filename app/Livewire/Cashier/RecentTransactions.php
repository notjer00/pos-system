<?php

namespace App\Livewire\Cashier;

use App\Models\SalesTransaction;
use App\Services\TransactionReversalService;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class RecentTransactions extends Component
{
    use WithPagination;

    public $search = '';

    public $showVoidModal = false;

    public $selectedTransaction = null;

    public $voidNote = '';

    protected $listeners = ['refreshTransactions' => '$refresh'];

    public function openVoidModal(SalesTransaction $transaction): void
    {
        $this->selectedTransaction = $transaction;
        $this->voidNote = '';
        $this->showVoidModal = true;
    }

    public function processVoid(TransactionReversalService $service): void
    {
        try {
            $service->void($this->selectedTransaction, auth()->user(), $this->voidNote);
            $this->showVoidModal = false;
            $this->voidNote = '';
            $this->dispatch('notify', message: 'Transaction voided successfully!');
        } catch (ValidationException $e) {
            $this->dispatch('notify', message: $e->validator->errors()->first(), type: 'error');
        } catch (\Exception $e) {
            $this->dispatch('notify', message: 'Error: '.$e->getMessage(), type: 'error');
        }
    }

    public function closeModals(): void
    {
        $this->showVoidModal = false;
        $this->selectedTransaction = null;
        $this->voidNote = '';
    }

    public function canVoid(SalesTransaction $transaction): bool
    {
        return $transaction->isCompleted() && $transaction->created_at->isToday() && $transaction->cashier_id === auth()->id();
    }

    public function render()
    {
        $transactions = SalesTransaction::with(['discount', 'lineItems.productVariant.product'])
            ->where('cashier_id', auth()->id())
            ->whereDate('created_at', today())
            ->when($this->search, function ($query) {
                $query->where('id', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(10);

        return view('livewire.cashier.recent-transactions', [
            'transactions' => $transactions,
        ]);
    }
}
