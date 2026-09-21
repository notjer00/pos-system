---
paths:
  - resources/views/dashboard.blade.php
  - 'resources/views/**/*.blade.php'
---

# Views

## Dashboard data & stat-card pattern
The dashboard is driven by App\Http\Controllers\DashboardController (an invokable controller, not a Livewire component). It is the only page still using a Blade view; all other pages use Livewire Volt components. Role-based stats: admin sees Today's Sales/Transactions/Active Products/Active Discounts + stock alerts + recent transactions; cashier sees Sales/Transactions/Items Sold/Commission. Use the stat-card utility + icon tile pattern (colored rounded square with inline SVG) for consistency.

## Never use UTF-8 BOM encoding in blade files
UTF-8 BOM (byte order mark, EF BB BF) in blade files causes Livewire's server-side renderer to skip the first HTML element when adding wire:id/wire:snapshot/wire:effects attributes, placing them on the wrong element. This breaks client-side initialization causing "Snapshot missing" and "Could not find Livewire component in DOM tree" errors. Always save blade files as UTF-8 without BOM.
