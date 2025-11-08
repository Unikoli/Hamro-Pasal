@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Total Products -->
        <div class="metallic-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-metallic-mid text-sm font-medium">Total Products</p>
                    <p class="text-3xl font-bold text-white">{{ number_format($totalProducts) }}</p>
                </div>
                <div class="bg-blue-500/20 p-3 rounded-full">
                    <span class="text-2xl">📦</span>
                </div>
            </div>
        </div>

        <!-- Total Sales Revenue -->
        <div class="metallic-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-metallic-mid text-sm font-medium">Total Sales</p>
                    <p class="text-3xl font-bold text-metallic-gold">Rs.{{ number_format($totalSales, 2) }}</p>
                </div>
                <div class="bg-metallic-gold/20 p-3 rounded-full">
                    <span class="text-2xl">💰</span>
                </div>
            </div>
        </div>

        <!-- Today's Sales -->
        <div class="metallic-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-metallic-mid text-sm font-medium">Today's Sales</p>
                    <p class="text-3xl font-bold text-green-400">Rs.{{ number_format($todaySales, 2) }}</p>
                </div>
                <div class="bg-green-500/20 p-3 rounded-full">
                    <span class="text-2xl">📈</span>
                </div>
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="metallic-card p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-metallic-mid text-sm font-medium">Low Stock Items</p>
                    <p class="text-3xl font-bold text-red-400">{{ $lowStockProducts }}</p>
                </div>
                <div class="bg-red-500/20 p-3 rounded-full">
                    <span class="text-2xl">⚠️</span>
                </div>
            </div>
        </div>

        <!-- Forecast Card (Weekly Totals) -->
        <div class="metallic-card p-6">
            <div class="flex flex-col space-y-3">
                <div>
                    <p class="text-metallic-mid text-sm font-medium">Forecasted Weekly Sales</p>
                    <p id="weeklySalesForecast" class="text-3xl font-bold text-blue-400">loading..</p>
                </div>
                <div>
                    <p class="text-metallic-mid text-sm font-medium">Forecasted Weekly Revenue</p>
                    <p id="weeklyRevenueForecast" class="text-3xl font-bold text-blue-400">loading..</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Performance Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        <div class="lg:col-span-2">
            <div class="metallic-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-xl font-bold text-metallic-mid mb-1">Sales Performance & Forecast</h3>
                        <p class="text-steel-100 text-sm">Past performance with 7-day forecast trend</p>
                    </div>
                    <div class="flex space-x-2">
                        <!-- <button id="monthlyViewBtn" class="chart-toggle-btn active px-4 py-2 text-sm rounded-lg">Monthly</button> -->
                        <button id="dailyViewBtn" class="chart-toggle-btn px-4 py-2 text-sm rounded-lg">Daily</button>
                    </div>
                </div>

                <div class="relative" style="height: 400px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Products -->
        <div class="metallic-card p-6">
            <h3 class="text-xl font-bold text-metallic-mid mb-4">Top Products</h3>
            <div class="space-y-4">
                @forelse($topProducts as $product)
                <div class="flex items-center justify-between p-3 bg-steel-800/30 rounded-lg">
                    <div>
                        <p class="text-white font-medium">{{ $product->name }}</p>
                        <p class="text-steel-100 text-sm">{{ $product->total_quantity }} sold</p>
                    </div>
                    <div class="text-right">
                        <p class="text-metallic-gold font-bold">Rs.{{ number_format($product->total_revenue, 2) }}</p>
                    </div>
                </div>
                @empty
                <p class="text-steel-100 text-center py-4">No sales data available</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Sales & Low Stock Alerts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Recent Sales -->
        <div class="metallic-card p-6">
            <h3 class="text-xl font-bold text-metallic-mid mb-4">Recent Sales</h3>
            <div class="space-y-3">
                @forelse($recentSales as $sale)
                <div class="flex items-center justify-between p-3 bg-steel-800/30 rounded-lg">
                    <div>
                        <p class="text-white font-medium">{{ $sale['product_name'] }}</p>
                        <p class="text-steel-100 text-sm">
                            {{ $sale['quantity'] }} × Rs.{{ number_format($sale['unit_price'], 2) }}
                            • {{ $sale['user_name'] }}
                        </p>
                        <p class="text-steel-100 text-xs">{{ $sale['created_at'] }}</p>
                    </div>
                    <div class="text-metallic-gold font-bold">
                        Rs.{{ number_format($sale['total_amount'], 2) }}
                    </div>
                </div>
                @empty
                <p class="text-steel-100 text-center py-4">No recent sales</p>
                @endforelse
            </div>
        </div>

        <!-- Low Stock Alerts -->
        <div class="metallic-card p-6">
            <h3 class="text-xl font-bold text-metallic-mid mb-4">Low Stock Alerts</h3>
            <div class="space-y-3">
                @forelse($lowStockAlerts as $product)
                <div class="flex items-center justify-between p-3 bg-red-500/10 border border-red-500/20 rounded-lg">
                    <div>
                        <p class="text-white font-medium">{{ $product['name'] }}</p>
                        <p class="text-steel-100 text-sm">
                            {{ $product['category'] }} • {{ $product['supplier'] }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-red-400 font-bold">{{ $product['current_stock'] }}</p>
                        <p class="text-steel-100 text-xs">Min: {{ $product['reorder_level'] }}</p>
                    </div>
                </div>
                @empty
                <p class="text-steel-100 text-center py-4">All products are well stocked</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Forecast + Chart Script -->
<script>
document.addEventListener('DOMContentLoaded', async function() {
    const ctx = document.getElementById('salesChart').getContext('2d');

    const monthlyData = {
        labels: @json($monthlySalesData['labels']),
        revenues: @json($monthlySalesData['revenues']),
        quantities: @json($monthlySalesData['quantities'])
    };

    const dailyData = {
        labels: @json($dailySalesData['labels']),
        revenues: @json($dailySalesData['revenues']),
        quantities: @json($dailySalesData['quantities'])
    };

    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: dailyData.labels.slice(),
            datasets: [
                {
                    label: 'Revenue (Rs.)',
                    data: dailyData.revenues.slice(),
                    borderColor: '#d4af37',
                    backgroundColor: 'rgba(212,175,55,0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#d4af37'
                },
                {
                    label: 'Forecasted Revenue (Next 7 Days)',
                    data: [], // populated after fetch
                    borderColor: '#60a5fa',
                    borderDash: [6,4],
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4,
                    pointBackgroundColor: '#60a5fa'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { labels: { color: '#c5d1d5' } }
            },
            scales: {
                x: { ticks: { color: '#c5d1d5' } },
                y: { ticks: { color: '#c5d1d5', callback: v => 'Rs.' + v.toLocaleString() } }
            }
        }
    });

    // Fetch forecast from FastAPI
    try {
        const formattedSales = dailyData.labels.map((label, i) => {
            const dateObj = new Date(`${label} ${new Date().getFullYear()}`);
            const isoDate = dateObj.toISOString().split("T")[0];
            return {
                date: isoDate,
                quantity: dailyData.quantities[i],
                price: dailyData.revenues[i] / Math.max(dailyData.quantities[i], 1)
            };
        });

        const response = await fetch("http://127.0.0.1:8001/forecast", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ sales: formattedSales })
        });

        const result = await response.json();

        if (result.DailyForecasts) {
            const forecastLabels = result.DailyForecasts.map(f => f.date);
            const forecastRevenue = result.DailyForecasts.map(f => f.predicted_revenue);

            salesChart.data.labels = [...dailyData.labels, ...forecastLabels];
            salesChart.data.datasets[1].data = [...new Array(dailyData.labels.length-1).fill(null), ...forecastRevenue];
            salesChart.update();

            document.getElementById('weeklySalesForecast').textContent = result.WeeklySummary?.total_sales.toLocaleString() ?? 'N/A';
            document.getElementById('weeklyRevenueForecast').textContent = 'Rs.' + (result.WeeklySummary?.total_revenue.toLocaleString() ?? 'N/A');
        }
    } catch (error) {
        console.error("Forecast fetch failed:", error);
        document.getElementById('weeklySalesForecast').textContent = "Error";
        document.getElementById('weeklyRevenueForecast').textContent = "Error";
    }

    // Toggle chart view
    // const monthlyBtn = document.getElementById('monthlyViewBtn');
    const dailyBtn = document.getElementById('dailyViewBtn');

    // monthlyBtn.addEventListener('click', () => updateChart(monthlyData, 'Monthly'));
    // dailyBtn.addEventListener('click', () => updateChart(dailyData, 'Daily'));

    function updateChart(data, period) {
        salesChart.data.labels = data.labels;
        salesChart.data.datasets[0].data = data.revenues;
        salesChart.data.datasets[0].label = 'Revenue (Rs.) - ' + period;
        salesChart.update('resize');
    }
});
</script>
@endsection
