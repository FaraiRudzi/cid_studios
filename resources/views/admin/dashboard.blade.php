@extends('layouts.app')
@section('title', 'Admin Dashboard Overview')

@section('content')
<div class="max-w-7xl mx-auto">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">

        <div class="bg-white dark:bg-gray-800 rounded-3xl p-7 shadow-sm border-l-8 border-zrp-blue relative overflow-hidden group">
            <div class="relative z-10 flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Stations</p>
                    <h3 class="text-4xl font-black text-zrp-blue dark:text-white mt-2">{{ $totalStations }}</h3>
                </div>
                <div class="h-14 w-14 bg-blue-50 dark:bg-blue-900/20 rounded-2xl flex items-center justify-center text-zrp-blue group-hover:scale-110 transition-transform">
                    <i data-feather="shield" class="w-7 h-7"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-3xl p-7 shadow-sm border-l-8 border-zrp-gold group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Photographers</p>
                    <h3 class="text-4xl font-black text-zrp-blue dark:text-white mt-2">{{ $totalPhotographers }}</h3>
                </div>
                <div class="h-14 w-14 bg-yellow-50 dark:bg-yellow-900/20 rounded-2xl flex items-center justify-center text-zrp-gold-hover group-hover:scale-110 transition-transform">
                    <i data-feather="camera" class="w-7 h-7"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-3xl p-7 shadow-sm border-l-8 border-orange-500 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Pending Cases</p>
                    <h3 class="text-4xl font-black text-zrp-blue dark:text-white mt-2">{{ $pendingCases }}</h3>
                </div>
                <div class="h-14 w-14 bg-orange-50 dark:bg-orange-900/20 rounded-2xl flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
                    <i data-feather="alert-circle" class="w-7 h-7"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-3xl p-7 shadow-sm border-l-8 border-green-500 group">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-black text-gray-400 uppercase tracking-widest">Finalised</p>
                    <h3 class="text-4xl font-black text-zrp-blue dark:text-white mt-2">{{ $finalisedCases }}</h3>
                </div>
                <div class="h-14 w-14 bg-green-50 dark:bg-green-900/20 rounded-2xl flex items-center justify-center text-green-500 group-hover:scale-110 transition-transform">
                    <i data-feather="check-square" class="w-7 h-7"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm">
            <div class="flex items-center justify-between mb-8">
                <h4 class="font-black text-zrp-blue dark:text-white uppercase tracking-tight">Investigation Status</h4>
                <i data-feather="pie-chart" class="text-gray-300"></i>
            </div>
            <div class="relative h-64">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-3 bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm">
            <h4 class="font-black text-zrp-blue dark:text-white uppercase tracking-tight mb-8">Case Processing History</h4>
            <div class="h-64 flex flex-col items-center justify-center border-2 border-dashed border-gray-100 dark:border-gray-700 rounded-3xl">
                <i data-feather="trending-up" class="w-12 h-12 text-gray-200 mb-2"></i>
                <p class="text-gray-400 font-medium">Monthly Analytics will appear here</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Finalised'],
            datasets: [{
                data: [@json($pendingCases), @json($finalisedCases)],
                backgroundColor: ['#FFD700', '#002147'],
                hoverOffset: 10,
                borderWidth: 0
            }]
        },
        options: {
            cutout: '82%',
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { usePointStyle: true, font: { weight: 'bold' }, padding: 20 } }
            }
        }
    });
</script>
@endpush
