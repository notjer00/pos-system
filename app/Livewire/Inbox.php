<?php

namespace App\Livewire;

use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Inbox extends Component
{
    use WithPagination;

    public $search = '';
    public $showComposeModal = false;
    public $showMessageModal = false;
    public $selectedMessage = null;
    public $recipientId = '';
    public $messageBody = '';
    public $filter = 'all'; // all, unread, sent

    protected $listeners = ['refreshInbox' => '$refresh'];

    public function mount(): void
    {
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->recipientId = '';
        $this->messageBody = '';
    }

    public function compose(int $recipientId = null): void
    {
        $this->resetForm();
        if ($recipientId) {
            $this->recipientId = (string) $recipientId;
        }
        $this->showComposeModal = true;
    }

    public function viewMessage(Message $message): void
    {
        if ($message->receiver_id === Auth::id() && ! $message->is_read) {
            $message->markAsRead();
        }
        $this->selectedMessage = $message;
        $this->showMessageModal = true;
    }

    public function reply(): void
    {
        if ($this->selectedMessage) {
            $this->recipientId = (string) $this->selectedMessage->sender_id;
            $this->messageBody = '';
            $this->showMessageModal = false;
            $this->showComposeModal = true;
        }
    }

    public function sendMessage(): void
    {
        $this->validate([
            'recipientId' => 'required|exists:users,id',
            'messageBody' => 'required|string|max:5000',
        ]);

        $recipient = User::find($this->recipientId);

        // Check permissions: Cashier can only message Admin, Admin can message anyone
        $user = Auth::user();
        if ($user->isCashier() && ! $recipient->isAdmin()) {
            $this->addError('recipientId', 'Cashiers can only message administrators.');
            return;
        }

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->recipientId,
            'body' => $this->messageBody,
            'is_read' => false,
        ]);

        $this->showComposeModal = false;
        $this->resetForm();
        $this->dispatch('notify', message: 'Message sent successfully!');
    }

    public function deleteMessage(Message $message): void
    {
        if ($message->sender_id === Auth::id() || $message->receiver_id === Auth::id()) {
            $message->delete();
            $this->dispatch('notify', message: 'Message deleted!');
        }
    }

    public function setFilter(string $filter): void
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function getRecipients(): array
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            // Admin can message all cashiers and other admins
            return User::where('id', '!=', $user->id)
                ->whereIn('role', ['cashier', 'admin'])
                ->orderBy('name')
                ->get()
                ->mapWithKeys(fn ($u) => [$u->id => $u->name . ' (' . ucfirst($u->role) . ')'])
                ->toArray();
        } else {
            // Cashier can only message admins
            return User::where('role', 'admin')
                ->orderBy('name')
                ->get()
                ->mapWithKeys(fn ($u) => [$u->id => $u->name . ' (Admin)'])
                ->toArray();
        }
    }

    public function getMessages()
    {
        $user = Auth::user();

        $query = match ($this->filter) {
            'unread' => $user->receivedMessages()->where('is_read', false),
            'sent' => $user->sentMessages(),
            default => Message::where(function ($q) use ($user) {
                $q->where('sender_id', $user->id)
                    ->orWhere('receiver_id', $user->id);
            }),
        };

        return $query->with(['sender', 'receiver'])
            ->when($this->search, function ($q) {
                $q->where('body', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(15);
    }

    public function getUnreadCount(): int
    {
        return Auth::user()->unreadMessagesCount();
    }

    public function render()
    {
        return view('livewire.inbox', [
            'messageList' => $this->getMessages(),
            'recipients' => $this->getRecipients(),
            'unreadCount' => $this->getUnreadCount(),
        ]);
    }
}