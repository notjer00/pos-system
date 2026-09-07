<div class="p-6">

    {{-- Header --}}
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-foreground flex items-center gap-2">
            Messages
            @if ($unreadCount > 0)
                <span class="badge badge-destructive">{{ $unreadCount }}</span>
            @endif
        </h1>
        <button wire:click="compose" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            Compose
        </button>
    </div>

    {{-- Filter Tabs --}}
    <div class="mb-4 border-b border-border">
        <nav class="-mb-px flex space-x-6" aria-label="Tabs">
            @foreach (['all' => 'All Messages', 'unread' => 'Unread', 'sent' => 'Sent'] as $key => $label)
                <button
                    wire:click="setFilter('{{ $key }}')"
                    class="py-2 px-1 border-b-2 font-medium text-sm transition-colors
                        {{ $filter === $key
                            ? 'border-primary text-foreground'
                            : 'border-transparent text-muted-foreground hover:text-foreground hover:border-border'
                        }}"
                >
                    {{ $label }}
                    @if ($key === 'unread' && $unreadCount > 0)
                        <span class="ml-1.5 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-medium rounded-full {{ $filter === 'unread' ? 'bg-primary/10 text-primary' : 'bg-muted text-muted-foreground' }}">
                            {{ $unreadCount }}
                        </span>
                    @endif
                </button>
            @endforeach
        </nav>
    </div>

    {{-- Search --}}
    <div class="mb-6">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search messages..."
            class="input-field w-full md:w-96"
        >
    </div>

    {{-- Messages List --}}
    @if ($messageList->count() > 0)
        <div class="card overflow-hidden">
            <table class="min-w-full divide-y divide-border">
                <thead>
                    <tr class="bg-muted/50">
                        <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                            {{ $filter === 'sent' ? 'To' : 'From' }}
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Preview</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-muted-foreground uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($messageList as $message)
                        @php
                            $isUnread = $filter !== 'sent' && ! $message->is_read;
                        @endphp
                        <tr class="hover:bg-muted/30 {{ $isUnread ? 'bg-primary/5' : '' }}">
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-foreground">
                                    {{ $filter === 'sent' ? $message->receiver->name : $message->sender->name }}
                                </div>
                                <div class="text-xs text-muted-foreground">
                                    {{ ucfirst($filter === 'sent' ? $message->receiver->role : $message->sender->role) }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm text-muted-foreground line-clamp-1 max-w-xs">
                                    {{ Str::limit($message->body, 60) }}
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-muted-foreground">
                                {{ $message->created_at->diffForHumans() }}
                            </td>
                            <td class="px-4 py-3">
                                @if ($isUnread)
                                    <span class="badge badge-primary">Unread</span>
                                @else
                                    <span class="badge badge-success">Read</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <button
                                        wire:click="viewMessage({{ $message->id }})"
                                        class="btn-ghost btn-icon-sm"
                                        title="View message"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </button>
                                    <button
                                        wire:click="deleteMessage({{ $message->id }})"
                                        class="btn-ghost btn-icon-sm text-destructive hover:text-destructive"
                                        title="Delete message"
                                        onclick="return confirm('Delete this message?')"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="px-4 py-3 border-t border-border">
                {{ $messageList->links() }}
            </div>
        </div>
    @else
        {{-- Empty State --}}
        <div class="card p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <h3 class="mt-3 text-sm font-medium text-foreground">No messages found</h3>
            <p class="mt-1 text-sm text-muted-foreground">Start a conversation by composing a new message.</p>
            <button wire:click="compose" class="mt-4 btn-primary">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Compose Message
            </button>
        </div>
    @endif

    {{-- Compose Modal --}}
    @if ($showComposeModal)
        <div class="dialog-overlay" wire:click="$set('showComposeModal', false)">
            <div class="dialog-content max-w-lg" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between p-4 border-b border-border">
                    <h3 class="text-lg font-semibold text-foreground">Compose Message</h3>
                    <button wire:click="$set('showComposeModal', false)" class="btn-ghost btn-icon-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <form wire:submit.prevent="sendMessage" class="p-4 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">To *</label>
                        <select wire:model="recipientId" class="input-field w-full">
                            <option value="">Select recipient</option>
                            @foreach ($recipients as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        @error('recipientId') <p class="mt-1 text-xs text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-foreground mb-1">Message *</label>
                        <textarea wire:model="messageBody" rows="5" class="input-field w-full" placeholder="Type your message..."></textarea>
                        @error('messageBody') <p class="mt-1 text-xs text-destructive">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end gap-3 border-t border-border pt-4">
                        <button type="button" wire:click="$set('showComposeModal', false)" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">Send</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- View Message Modal --}}
    @if ($showMessageModal && $selectedMessage)
        <div class="dialog-overlay" wire:click="$set('showMessageModal', false)">
            <div class="dialog-content max-w-lg" onclick="event.stopPropagation()">
                <div class="flex items-center justify-between p-4 border-b border-border">
                    <h3 class="text-lg font-semibold text-foreground">
                        @if ($selectedMessage->receiver_id === auth()->id())
                            From: {{ $selectedMessage->sender->name }}
                        @else
                            To: {{ $selectedMessage->receiver->name }}
                        @endif
                    </h3>
                    <button wire:click="$set('showMessageModal', false)" class="btn-ghost btn-icon-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="p-4 space-y-4">
                    <div class="bg-muted/50 p-4 rounded-lg">
                        <p class="text-sm text-foreground whitespace-pre-wrap">{{ $selectedMessage->body }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-muted-foreground">
                            {{ $selectedMessage->created_at->format('M d, Y h:i A') }}
                        </span>
                        @if ($selectedMessage->receiver_id === auth()->id())
                            <button wire:click="reply" class="btn-ghost text-sm text-primary hover:text-primary/80 font-medium">
                                Reply
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
