<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class UserManagement extends Component
{
    use WithPagination;

    public $search = '';

    public $showModal = false;

    public $editingUser = null;

    public $name = '';

    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    public $role = 'cashier';

    public $commission_rate = 10.00;

    public function mount(): void
    {
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->role = 'cashier';
        $this->commission_rate = 10.00;
        $this->editingUser = null;
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(User $user): void
    {
        $this->editingUser = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->commission_rate = $user->commission_rate;
        $this->showModal = true;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.($this->editingUser?->id ?? ''),
            'password' => $this->editingUser
                ? ['nullable', 'string', 'confirmed', Password::min(8)->letters()->numbers()]
                : ['required', 'string', 'confirmed', Password::min(8)->letters()->numbers()],
            'role' => 'required|in:cashier,admin',
            'commission_rate' => 'required|numeric|min:0|max:100',
        ]);

        if ($this->editingUser) {
            if (filled($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }
            $this->editingUser->update($validated);
            $message = 'User updated successfully!';
        } else {
            $validated['password'] = Hash::make($validated['password']);
            User::create($validated);
            $message = 'User created successfully!';
        }

        $this->showModal = false;
        $this->resetForm();
        $this->dispatch('notify', message: $message);
    }

    public function delete(User $user): void
    {
        if ($user->id === auth()->id()) {
            $this->dispatch('notify', message: 'Cannot delete yourself.', type: 'error');

            return;
        }
        $user->delete();
        $this->dispatch('notify', message: 'User deleted successfully!');
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function render()
    {
        $users = User::when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%")
            ->orWhere('email', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users,
        ]);
    }
}
