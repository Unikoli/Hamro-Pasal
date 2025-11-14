@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">

    {{-- === TOP METRIC CARDS === --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <x-dashboard.card title="Total Products" icon="📦" :value="$totalProducts" />
        <x-dashboard.card title="Total Sales" icon="💰" :value="'Rs.'.number_format($totalSales, 2)" />
        <x-dashboard.card title="Total Purchases" icon="🛒" :value="'Rs.'.number_format($totalPurchases, 2)" />
        <x-dashboard.card title="Profit" icon="📈" :value="'Rs.'.number_format($totalProfit, 2)" />
        <x-dashboard.card title="Customers" icon="👥" :value="$totalCustomers" />
    </div>

    {{-- === SALES & PURCHASES CHART === --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="metallic-card p-6">
            <h3 class="text-xl font-bold mb-2 text-metallic-mid">Sales Trend (Last 30 Days)</h3>
            <canvas id="salesChart" height="150"></canvas>
        </div>

        <div class="metallic-card p-6">
            <h3 class="text-xl font-bold mb-2 text-metallic-mid">Purchases Trend (Last 30 Days)</h3>
            <canvas id="purchaseChart" height="150"></canvas>
        </div>
    </div>

    {{-- === STOCK MOVEMENT PIE === --}}
    <div class="metallic-card p-6 mb-8">
        <h3 class="text-xl font-bold text-metallic-mid mb-4">Stock Movements (IN vs OUT)</h3>
        <canvas id="stockPie" height="120"></canvas>
    </div>

    {{-- === TOP PRODUCTS / CUSTOMERS / SUPPLIERS === --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <x-dashboard.list title="Top Products" :items="$topProducts" nameKey="name" valueKey="total_revenue" unit="Rs." />
        <x-dashboard.list title="Top Customers" :items="$topCustomers" nameKey="name" valueKey="total_spent" unit="Rs." />
        <x-dashboard.list title="Top Suppliers" :items="$topSuppliers" nameKey="name" valueKey="total_spent" unit="Rs." />
    </div>

    {{-- === RECENT SALES & LOW STOCK === --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="metallic-card p-6">
            <h3 class="text-xl font-bold text-metallic-mid mb-4">Recent Sales</h3>
            @foreach($recentSales as $sale)
                <div class="flex justify-between bg-steel-800/30 p-3 rounded mb-2">
                    <span class="text-white">{{ $sale->customer->name ?? 'Walk-in' }}</span>
                    <span class="text-metallic-gold font-bold">Rs.{{ number_format($sale->total_amount,2) }}</span>
                </div>
            @endforeach
        </div>

        <div class="metallic-card p-6">
            <h3 class="text-xl font-bold text-metallic-mid mb-4">Low Stock Alerts</h3>
            @foreach($lowStockAlerts as $p)
                <div class="flex justify-between p-3 bg-red-500/10 border border-red-500/20 rounded mb-2">
                    <span class="text-white">{{ $p->name }}</span>
                    <span class="text-red-400 font-bold">{{ $p->quantity }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- === CHART.JS SCRIPTS === --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const salesCtx = document.getElementById('salesChart').getContext('2d');
new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: @json($salesChart['labels']),
        datasets: [{
            label: 'Sales (Rs)',
            data: @json($salesChart['totals']),
            borderColor: '#d4af37',
            fill: true,
            tension: 0.4
        }]
    },
    options: { plugins: { legend: { labels: { color: '#c5d1d5' } } } }
});

const purchaseCtx = document.getElementById('purchaseChart').getContext('2d');
new Chart(purchaseCtx, {
    type: 'bar',
    data: {
        labels: @json($purchaseChart['labels']),
        datasets: [{
            label: 'Purchases (Rs)',
            data: @json($purchaseChart['totals']),
            backgroundColor: 'rgba(96,165,250,0.6)',
        }]
    },
});

const stockCtx = document.getElementById('stockPie').getContext('2d');
new Chart(stockCtx, {
    type: 'doughnut',
    data: {
        labels: ['Stock IN', 'Stock OUT'],
        datasets: [{
            data: [{{ $stockMovement['IN'] ?? 0 }}, {{ $stockMovement['OUT'] ?? 0 }}],
            backgroundColor: ['#22c55e', '#ef4444'],
        }]
    },
});
</script>
@endsection
