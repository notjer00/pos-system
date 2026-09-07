---
paths:
  - resources/views/dashboard.blade.php
---

# Views

## Dashboard data & stat-card pattern
The dashboard is driven by App\Http\Controllers\DashboardController (an invokable controller, not a Livewire component). It is the only page still using a Blade view; all other pages use Livewire Volt components. Role-based stats: admin sees Today's Sales/Transactions/Active Products/Active Discounts + stock alerts + recent transactions; cashier sees Sales/Transactions/Items Sold/Commission. Use the stat-card utility + icon tile pattern (colored rounded square with inline SVG) for consistency.
