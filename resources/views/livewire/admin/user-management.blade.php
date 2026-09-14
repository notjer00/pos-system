<div class="p-4 sm:p-6 space-y-6" x-data>
    {{-- Hero Section --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest mb-1" style="color: #5cbfaa;">Admin Panel</p>
            <h1 class="text-2xl font-bold" style="color: #e8e4dc;">User Management</h1>
            <p class="text-sm mt-1" style="color: #6a6560;">Manage system users, roles, and account access.</p>
        </div>
        <button wire:click="create" class="btn-primary">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Add User
        </button>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="rounded-2xl p-5 transition-all duration-300" style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #6a6560;">Total Users</p>
                    <p class="text-2xl font-bold mt-1" style="color: #e8e4dc;">{{ $totalUsers }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(45,133,112,0.12); border: 1px solid rgba(45,133,112,0.12);">
                    <svg class="w-5 h-5" style="color: #5cbfaa;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl p-5 transition-all duration-300" style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #6a6560;">Admins</p>
                    <p class="text-2xl font-bold mt-1" style="color: #e8e4dc;">{{ $adminCount }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(200,155,60,0.1); border: 1px solid rgba(200,155,60,0.1);">
                    <svg class="w-5 h-5" style="color: #d4b060;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl p-5 transition-all duration-300" style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #6a6560;">Cashiers</p>
                    <p class="text-2xl font-bold mt-1" style="color: #e8e4dc;">{{ $regularCount }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(61,139,139,0.1); border: 1px solid rgba(61,139,139,0.1);">
                    <svg class="w-5 h-5" style="color: #5aa8a8;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-2xl p-5 transition-all duration-300" style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium" style="color: #6a6560;">Recently Added</p>
                    <p class="text-2xl font-bold mt-1" style="color: #e8e4dc;">{{ $recentlyAdded }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background: rgba(45,133,112,0.1); border: 1px solid rgba(45,133,112,0.1);">
                    <svg class="w-5 h-5" style="color: #5cbfaa;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- All Users Panel --}}
    <div class="rounded-2xl overflow-hidden" style="background: rgba(255,255,255,0.03); backdrop-filter: blur(20px); border: 1px solid rgba(255,255,255,0.06); box-shadow: 0 8px 32px rgba(0,0,0,0.2);">
        <div class="px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
            <div>
                <h2 class="text-base font-semibold" style="color: #e8e4dc;">All Users</h2>
                <p class="text-xs mt-0.5" style="color: #6a6560;">View and manage all registered users.</p>
            </div>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color: #4a4540;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search users..."
                    class="input-field pl-10 w-full sm:w-72"
                >
            </div>
        </div>

        @if ($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #6a6560;">ID</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #6a6560;">User</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #6a6560;">Email</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #6a6560;">Role</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #6a6560;">Created</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold uppercase tracking-wider" style="color: #6a6560;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="transition-all duration-200" style="border-bottom: 1px solid rgba(255,255,255,0.03);"
                                onmouseover="this.style.background='rgba(255,255,255,0.02)';"
                                onmouseout="this.style.background='';">
                                <td class="px-6 py-4 text-sm font-mono" style="color: #6a6560;">#{{ $user->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-xs font-semibold shrink-0"
                                             @if ($user->isAdmin())
                                                 style="background: rgba(45,133,112,0.15); color: #5cbfaa; border: 1px solid rgba(45,133,112,0.15);"
                                             @else
                                                 style="background: rgba(200,155,60,0.1); color: #d4b060; border: 1px solid rgba(200,155,60,0.1);"
                                             @endif
                                        >
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <span class="text-sm font-medium" style="color: #e8e4dc;">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm" style="color: #8a8580;">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    @if ($user->isAdmin())
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium"
                                              style="background: rgba(45,133,112,0.12); color: #5cbfaa; border: 1px solid rgba(45,133,112,0.15);">
                                            <span class="w-1.5 h-1.5 rounded-full" style="background: #5cbfaa;"></span>
                                            Admin
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-medium"
                                              style="background: rgba(61,139,139,0.1); color: #5aa8a8; border: 1px solid rgba(61,139,139,0.1);">
                                            <span class="w-1.5 h-1.5 rounded-full" style="background: #5aa8a8;"></span>
                                            Cashier
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm" style="color: #6a6560;">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <button wire:click="edit({{ $user->id }})" class="px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200"
                                                style="background: rgba(255,255,255,0.04); color: #c0bbb0; border: 1px solid rgba(255,255,255,0.06);"
                                                onmouseover="this.style.background='rgba(45,133,112,0.12)'; this.style.color='#5cbfaa'; this.style.borderColor='rgba(45,133,112,0.15)';"
                                                onmouseout="this.style.background='rgba(255,255,255,0.04)'; this.style.color='#c0bbb0'; this.style.borderColor='rgba(255,255,255,0.06)';">
                                            Edit
                                        </button>
                                        <button
                                            @click.prevent="$dispatch('confirm-delete', { id: {{ $user->id }}, title: 'Delete User?', message: 'Are you sure you want to delete this user? This action cannot be undone.' })"
                                            class="px-3 py-1.5 rounded-lg text-xs font-medium transition-all duration-200"
                                            style="background: rgba(255,255,255,0.04); color: #c0bbb0; border: 1px solid rgba(255,255,255,0.06);"
                                            onmouseover="this.style.background='rgba(180,50,50,0.12)'; this.style.color='#f0a0a0'; this.style.borderColor='rgba(180,50,50,0.15)';"
                                            onmouseout="this.style.background='rgba(255,255,255,0.04)'; this.style.color='#c0bbb0'; this.style.borderColor='rgba(255,255,255,0.06)';"
                                            @if ($user->id === auth()->id()) disabled style="opacity: 0.4; cursor: not-allowed;" @endif
                                        >Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4" style="border-top: 1px solid rgba(255,255,255,0.04);">
                {{ $users->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-16 px-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-4" style="background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.06);">
                    <svg class="w-7 h-7" style="color: #4a4540;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="text-sm font-medium" style="color: #8a8580;">No users found</h3>
                <p class="text-xs mt-1" style="color: #4a4540;">Get started by creating a new user.</p>
                <button wire:click="create" class="mt-4 btn-primary">Create User</button>
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="fixed inset-0" style="background: rgba(0,0,0,0.6); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);" wire:click="closeModal"></div>
                <div class="relative w-full max-w-md rounded-2xl p-6 shadow-2xl"
                     style="background: rgba(18, 20, 24, 0.95); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border: 1px solid rgba(255,255,255,0.08);">
                    <div class="flex items-center justify-between mb-5" style="border-bottom: 1px solid rgba(255,255,255,0.06); padding-bottom: 1rem;">
                        <h3 class="text-lg font-semibold" style="color: #e8e4dc;">
                            {{ $editingUser ? 'Edit User' : 'Create User' }}
                        </h3>
                        <button wire:click="closeModal" class="p-1.5 rounded-lg transition-all duration-200" style="color: #6a6560;" onmouseover="this.style.color='#e8e4dc';" onmouseout="this.style.color='#6a6560';">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="save" class="space-y-5">
                        {{-- Section: Account Information --}}
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-widest mb-3" style="color: #5cbfaa;">Account Information</p>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium mb-1.5" style="color: #8a8580;">Name *</label>
                                    <input type="text" wire:model="name" class="input-field w-full" required>
                                    @error('name') <p class="mt-1.5 text-xs" style="color: #f0a0a0;">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1.5" style="color: #8a8580;">Email *</label>
                                    <input type="email" wire:model="email" class="input-field w-full" required>
                                    @error('email') <p class="mt-1.5 text-xs" style="color: #f0a0a0;">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section: Security --}}
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-widest mb-3" style="color: #5cbfaa;">Security</p>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium mb-1.5" style="color: #8a8580;">
                                        Password {{ $editingUser ? '(leave blank to keep current)' : '*' }}
                                    </label>
                                    <input type="password" wire:model="password" class="input-field w-full" @if (!$editingUser) required @endif>
                                    @error('password') <p class="mt-1.5 text-xs" style="color: #f0a0a0;">{{ $message }}</p> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1.5" style="color: #8a8580;">Confirm Password</label>
                                    <input type="password" wire:model="password_confirmation" class="input-field w-full" @if (!$editingUser) required @endif>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Access & Commission --}}
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-widest mb-3" style="color: #5cbfaa;">Access & Commission</p>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium mb-1.5" style="color: #8a8580;">Role *</label>
                                    <select wire:model="role" class="input-field w-full" required>
                                        <option value="cashier">Cashier</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium mb-1.5" style="color: #8a8580;">Commission Rate (%) *</label>
                                    <input type="number" step="0.01" min="0" max="100" wire:model="commission_rate" class="input-field w-full" required>
                                    @error('commission_rate') <p class="mt-1.5 text-xs" style="color: #f0a0a0;">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-2" style="border-top: 1px solid rgba(255,255,255,0.06); padding-top: 1rem;">
                            <button type="button" wire:click="closeModal" class="btn-secondary">Cancel</button>
                            <button type="submit" class="btn-primary">
                                {{ $editingUser ? 'Update' : 'Create' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <x-confirm-delete-modal />
</div>
