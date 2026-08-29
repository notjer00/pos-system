<div class="p-6">
    <div class="mb-6 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Messages
            @if ($unreadCount > 0)
                <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                    {{ $unreadCount }}
                </span>
            @endif
        </h1>
        <button wire:click="compose" class="btn-primary">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
            </svg>
            Compose
        </button>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-4 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            @foreach (['all' => 'All Messages', 'unread' => 'Unread', 'sent' => 'Sent'] as $key => $label)
                <button
                    wire:click="setFilter('{{ $key }}')"
                    class="py-2 px-1 border-b-2 font-medium text-sm
                        @if ($filter === $key)
                            border-indigo-500 text-indigo-600
                        @else
                            border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300
                        @endif
                    ">
                    {{ $label }}
                </button>
            @endforeach
        </nav>
    </div>

    <!-- Search -->
    <div class="mb-6">
        <input
            type="text"
            wire:model.live.debounce.300ms="search"
            placeholder="Search messages..."
            class="input-field w-full md:w-96"
        >
    </div>

    <!-- Messages List -->
    @if ($messageList->count() > 0)
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            @if ($filter === 'sent') To @else From @endif
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Preview</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($messageList as $message)
                        <tr class="hover:bg-gray-50 @if ($filter !== 'sent' && !$message->is_read) bg-blue-50 @endif">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $filter === 'sent' ? $message->receiver->name : $message->sender->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ ucfirst($filter === 'sent' ? $message->receiver->role : $message->sender->role) }}
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 line-clamp-1 max-w-xs">
                                    {{ Str::limit($message->body, 60) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $message->created_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($filter !== 'sent' && !$message->is_read)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Unread
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Read
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium">
                                <button wire:click="viewMessage({{ $message->id }})" class="text-indigo-600 hover:text-indigo-900 mr-3">View</button>
                                <button wire:click="deleteMessage({{ $message->id }})" class="text-red-600 hover:text-red-900" onclick="return confirm('Delete this message?')">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="px-6 py-4 border-t">
                {{ $messageList->links() }}
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No messages found</h3>
            <p class="mt-1 text-sm text-gray-500">Start a conversation by composing a new message.</p>
            <button wire:click="compose" class="mt-4 btn-primary">Compose Message</button>
        </div>
    @endif

    <!-- Compose Modal -->
    @if ($showComposeModal)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('showComposeModal', false)"></div>
                <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                    <div class="flex items-center justify-between p-4 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Compose Message</h3>
                        <button wire:click="$set('showComposeModal', false)" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form wire:submit.prevent="sendMessage" class="p-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">To *</label>
                            <select wire:model="recipientId" class="input-field w-full" required>
                                <option value="">Select recipient</option>
                                @foreach ($recipients as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('recipientId') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Message *</label>
                            <textarea wire:model="messageBody" rows="5" class="input-field w-full" required placeholder="Type your message..."></textarea>
                            @error('messageBody') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex justify-end space-x-3 border-t pt-4">
                            <button type="button" wire:click="$set('showComposeModal', false)" class="btn-secondary">Cancel</button>
                            <button type="submit" class="btn-primary">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- View Message Modal -->
    @if ($showMessageModal && $selectedMessage)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="$set('showMessageModal', false)"></div>
                <div class="relative bg-white rounded-lg shadow-xl max-w-lg w-full">
                    <div class="flex items-center justify-between p-4 border-b">
                        <h3 class="text-lg font-medium text-gray-900">
                            @if ($selectedMessage->receiver_id === auth()->id())
                                From: {{ $selectedMessage->sender->name }}
                            @else
                                To: {{ $selectedMessage->receiver->name }}
                            @endif
                        </h3>
                        <button wire:click="$set('showMessageModal', false)" class="text-gray-400 hover:text-gray-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="p-4 space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $selectedMessage->body }}</p>
                        </div>
                        <div class="text-sm text-gray-500">
                            Sent: {{ $selectedMessage->created_at->format('M d, Y h:i A') }}
                        </div>

                        @if ($selectedMessage->receiver_id === auth()->id())
                            <button wire:click="reply" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Reply</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>