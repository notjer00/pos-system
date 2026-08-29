<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (auth()->user()->isAdmin())
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <a href="{{ route('admin.products') }}" class="bg-white overflow-hidden shadow-sm rounded-lg p-6 hover:shadow-md transition-shadow">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Product Management</h3>
                        <p class="text-gray-600">Manage products and variants</p>
                    </a>
                    <a href="{{ route('admin.discounts') }}" class="bg-white overflow-hidden shadow-sm rounded-lg p-6 hover:shadow-md transition-shadow">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Discount Management</h3>
                        <p class="text-gray-600">Configure store discounts</p>
                    </a>
                    <a href="{{ route('admin.reports') }}" class="bg-white overflow-hidden shadow-sm rounded-lg p-6 hover:shadow-md transition-shadow">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Reports</h3>
                        <p class="text-gray-600">View sales and commission reports</p>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <a href="{{ route('admin.users') }}" class="bg-white overflow-hidden shadow-sm rounded-lg p-6 hover:shadow-md transition-shadow">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">User Management</h3>
                        <p class="text-gray-600">Manage users and roles</p>
                    </a>
                    <a href="{{ route('admin.transactions') }}" class="bg-white overflow-hidden shadow-sm rounded-lg p-6 hover:shadow-md transition-shadow">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Transaction History</h3>
                        <p class="text-gray-600">View all transactions</p>
                    </a>
                    <a href="{{ route('admin.inbox') }}" class="bg-white overflow-hidden shadow-sm rounded-lg p-6 hover:shadow-md transition-shadow relative">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Messages
                            @if (auth()->user()->unreadMessagesCount() > 0)
                                <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                    {{ auth()->user()->unreadMessagesCount() }}
                                </span>
                            @endif
                        </h3>
                        <p class="text-gray-600">Communicate with cashiers</p>
                    </a>
                </div>
            @elseif (auth()->user()->isCashier())
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <a href="{{ route('cashier.checkout') }}" class="bg-white overflow-hidden shadow-sm rounded-lg p-6 hover:shadow-md transition-shadow">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Checkout</h3>
                        <p class="text-gray-600">Process customer purchases</p>
                    </a>
                    <a href="{{ route('cashier.transactions') }}" class="bg-white overflow-hidden shadow-sm rounded-lg p-6 hover:shadow-md transition-shadow">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Today's Transactions</h3>
                        <p class="text-gray-600">View and void recent sales</p>
                    </a>
                    <a href="{{ route('cashier.inbox') }}" class="bg-white overflow-hidden shadow-sm rounded-lg p-6 hover:shadow-md transition-shadow relative">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">Messages
                            @if (auth()->user()->unreadMessagesCount() > 0)
                                <span class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                                    {{ auth()->user()->unreadMessagesCount() }}
                                </span>
                            @endif
                        </h3>
                        <p class="text-gray-600">Contact administrators</p>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm rounded-lg p-6">
                        <h3 class="font-semibold text-lg text-gray-800 mb-2">My Stats</h3>
                        <p class="text-gray-600">Commission Rate: {{ auth()->user()->commission_rate }}%</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>