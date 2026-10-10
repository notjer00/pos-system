<div class="space-y-6" x-data>
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-lg font-semibold" style="color: hsl(var(--foreground));">User Management</h1>
            <p class="text-sm mt-0.5" style="color: hsl(var(--muted-foreground));">Manage staff accounts, roles, and access permissions.</p>
        </div>
        <button wire:click="create" class="btn-primary">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add User
        </button>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="um-stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium" style="color: hsl(var(--muted-foreground));">Total Users</p>
                    <p class="text-2xl font-bold mt-1" style="color: hsl(var(--foreground));">{{ $totalUsers }}</p>
                </div>
                <div class="um-stat-icon" style="background: rgba(200,155,60,0.12); border: 1px solid rgba(200,155,60,0.12);">
                    <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="um-stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium" style="color: hsl(var(--muted-foreground));">Admins</p>
                    <p class="text-2xl font-bold mt-1" style="color: hsl(var(--foreground));">{{ $adminCount }}</p>
                </div>
                <div class="um-stat-icon" style="background: rgba(200,155,60,0.1); border: 1px solid rgba(200,155,60,0.1);">
                    <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="um-stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium" style="color: hsl(var(--muted-foreground));">Cashiers</p>
                    <p class="text-2xl font-bold mt-1" style="color: hsl(var(--foreground));">{{ $regularCount }}</p>
                </div>
                <div class="um-stat-icon" style="background: rgba(200,155,60,0.1); border: 1px solid rgba(200,155,60,0.1);">
                    <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="um-stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium" style="color: hsl(var(--muted-foreground));">This Month</p>
                    <p class="text-2xl font-bold mt-1" style="color: hsl(var(--foreground));">{{ $recentlyAdded }}</p>
                </div>
                <div class="um-stat-icon" style="background: rgba(200,155,60,0.1); border: 1px solid rgba(200,155,60,0.1);">
                    <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- All Users Panel --}}
    <div class="card overflow-hidden">
        {{-- Toolbar --}}
        <div class="px-5 sm:px-6 py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3" style="border-bottom: 1px solid hsl(var(--border));">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: rgba(200,155,60,0.12); border: 1px solid rgba(200,155,60,0.1);">
                    <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-sm font-semibold" style="color: hsl(var(--foreground));">All Users</h2>
                    <p class="text-xs" style="color: hsl(var(--muted-foreground));">{{ $users->total() }} total users</p>
                </div>
            </div>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color: hsl(var(--muted-foreground));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Search by name or email..."
                    class="input-field pl-10 w-full sm:w-72"
                >
            </div>
        </div>

        @if ($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full um-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th class="hidden sm:table-cell">Email</th>
                            <th>Role</th>
                            <th class="hidden md:table-cell">Joined</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="text-xs font-mono" style="color: hsl(var(--muted-foreground));">#{{ $user->id }}</td>
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="um-avatar"
                                             @if ($user->isAdmin())
                                                 style="background: rgba(200,155,60,0.15); color: hsl(var(--gold-text)); border: 1px solid rgba(200,155,60,0.2);"
                                             @else
                                                 style="background: rgba(200,155,60,0.1); color: hsl(var(--gold-text)); border: 1px solid rgba(200,155,60,0.15);"
                                             @endif
                                        >
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-sm font-medium truncate" style="color: hsl(var(--foreground));">{{ $user->name }}</p>
                                            <p class="text-xs truncate sm:hidden" style="color: hsl(var(--muted-foreground));">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="hidden sm:table-cell" style="color: hsl(var(--muted-foreground));">{{ $user->email }}</td>
                                <td>
                                    @if ($user->isAdmin())
                                        <span class="badge-primary text-[11px]">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5" style="background: #d4a850;"></span>
                                            Admin
                                        </span>
                                    @else
                                        <span class="badge-warning text-[11px]">
                                            <span class="w-1.5 h-1.5 rounded-full mr-1.5" style="background: #d4b060;"></span>
                                            Cashier
                                        </span>
                                    @endif
                                </td>
                                <td class="text-xs hidden md:table-cell" style="color: hsl(var(--muted-foreground));">{{ $user->created_at->format('M d, Y') }}</td>
                                <td>
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button wire:click="edit({{ $user->id }})" class="um-action-edit">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                            Edit
                                        </button>
                                        <button
                                            @click.prevent="$dispatch('confirm-delete', { id: {{ $user->id }}, title: 'Delete User?', message: 'Are you sure you want to delete this user? This action cannot be undone.' })"
                                            class="um-action-delete"
                                            @if ($user->id === auth()->id()) disabled style="opacity: 0.4; cursor: not-allowed;" @endif
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-5 sm:px-6 py-3" style="border-top: 1px solid hsl(var(--border));">
                {{ $users->links() }}
            </div>
        @else
            <div class="um-empty">
                <div class="um-empty-icon">
                    <svg class="w-7 h-7" style="color: hsl(var(--muted-foreground));" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h3 class="text-sm font-semibold" style="color: hsl(var(--muted-foreground));">No users found</h3>
                <p class="text-xs mt-1" style="color: hsl(var(--muted-foreground));">
                    @if ($search)
                        No results for "{{ $search }}". Try a different search.
                    @else
                        Get started by adding your first team member.
                    @endif
                </p>
                @if (!$search)
                    <button wire:click="create" class="mt-4 btn-primary">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add User
                    </button>
                @endif
            </div>
        @endif
    </div>

    {{-- Create/Edit Modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: false }" x-init="$nextTick(() => show = true)">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="fixed inset-0 transition-opacity duration-200"
                     :class="show ? 'opacity-100' : 'opacity-0'"
                     style="background: rgba(0,0,0,0.6);"
                     wire:click="closeModal"></div>
                <div class="relative w-full max-w-md rounded-xl shadow-xl transition-all duration-200"
                     :class="show ? 'scale-100 opacity-100' : 'scale-95 opacity-0'"
                     style="background: hsl(var(--popover)); border: 1px solid hsl(var(--border));">

                    {{-- Modal Header --}}
                    <div class="px-6 pt-5 pb-4 flex items-center justify-between" style="border-bottom: 1px solid hsl(var(--border));">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-lg flex items-center justify-center" style="background: rgba(200,155,60,0.12); border: 1px solid rgba(200,155,60,0.15);">
                                @if ($editingUser)
                                    <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" style="color: hsl(var(--gold-text));" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <h3 class="text-base font-semibold" style="color: hsl(var(--foreground));">
                                    {{ $editingUser ? 'Edit User' : 'Create New User' }}
                                </h3>
                                <p class="text-xs" style="color: hsl(var(--muted-foreground));">
                                    {{ $editingUser ? 'Update account details and permissions' : 'Add a new team member to the system' }}
                                </p>
                            </div>
                        </div>
                        <button wire:click="closeModal" class="btn-icon-sm" style="color: hsl(var(--muted-foreground));">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <form wire:submit.prevent="save" class="px-6 py-5 space-y-5">
                        {{-- Section: Account Information --}}
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-widest mb-3 flex items-center gap-2" style="color: hsl(var(--muted-foreground));">
                                Account Information
                            </p>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium mb-1.5" style="color: hsl(var(--muted-foreground));">Full Name</label>
                                    <input type="text" wire:model="name" class="input-field w-full" placeholder="e.g. Juan Dela Cruz" required>
                                    @error('name')
                                        <p class="mt-1.5 text-xs flex items-center gap-1" style="color: hsl(var(--danger-text));">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-medium mb-1.5" style="color: hsl(var(--muted-foreground));">Email Address</label>
                                    <input type="email" wire:model="email" class="input-field w-full" placeholder="user@example.com" required>
                                    @error('email')
                                        <p class="mt-1.5 text-xs flex items-center gap-1" style="color: hsl(var(--danger-text));">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Section: Security --}}
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-widest mb-3 flex items-center gap-2" style="color: hsl(var(--muted-foreground));">
                                Security
                            </p>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium mb-1.5" style="color: hsl(var(--muted-foreground));">
                                        Password
                                        @if ($editingUser)
                                            <span class="font-normal" style="color: hsl(var(--muted-foreground));">— leave blank to keep current</span>
                                        @endif
                                    </label>
                                    <input type="password" wire:model="password" class="input-field w-full" placeholder="Min. 8 characters" @if (!$editingUser) required @endif>
                                    @error('password')
                                        <p class="mt-1.5 text-xs flex items-center gap-1" style="color: hsl(var(--danger-text));">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-medium mb-1.5" style="color: hsl(var(--muted-foreground));">Confirm Password</label>
                                    <input type="password" wire:model="password_confirmation" class="input-field w-full" placeholder="Re-enter password" @if (!$editingUser) required @endif>
                                </div>
                            </div>
                        </div>

                        {{-- Section: Role & Commission --}}
                        <div>
                            <p class="text-[11px] font-semibold uppercase tracking-widest mb-3 flex items-center gap-2" style="color: hsl(var(--muted-foreground));">
                                Role &amp; Commission
                            </p>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium mb-1.5" style="color: hsl(var(--muted-foreground));">Role</label>
                                    <x-select
                                        wire-model="role"
                                        :value="$role"
                                        :options="[
                                            ['label' => 'Cashier', 'value' => 'cashier'],
                                            ['label' => 'Admin', 'value' => 'admin'],
                                        ]"
                                        placeholder="Select role"
                                    />
                                    @error('role')
                                        <p class="mt-1.5 text-xs flex items-center gap-1" style="color: hsl(var(--danger-text));">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-medium mb-1.5" style="color: hsl(var(--muted-foreground));">Commission Rate (%)</label>
                                    <input type="number" step="0.01" min="0" max="100" wire:model="commission_rate" class="input-field w-full" placeholder="e.g. 5.00" required>
                                    @error('commission_rate')
                                        <p class="mt-1.5 text-xs flex items-center gap-1" style="color: hsl(var(--danger-text));">
                                            <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="tx-modal-actions">
                            <button type="button" wire:click="closeModal" class="btn-secondary">Cancel</button>
                            <button type="submit" class="btn-primary">
                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    @if ($editingUser)
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    @endif
                                </svg>
                                {{ $editingUser ? 'Save Changes' : 'Create User' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <x-confirm-delete-modal />
</div>
