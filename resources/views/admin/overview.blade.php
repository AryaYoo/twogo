@extends('layouts.admin', [
    'title' => 'Overview Dashboard',
    'pageHeader' => 'Ringkasan Sistem & Performa',
    'headerBadge' => 'Live Feed'
])

@section('content')
<div class="space-y-8">
    <!-- Top 4 Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: User Aktif -->
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-2xl p-5 hover:translate-y-[-2px] transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Total User Aktif</span>
                <span class="p-2 bg-[#FFE156] border-2 border-[#1A1A2E] rounded-xl text-lg shadow-[2px_2px_0px_#1A1A2E]">👤</span>
            </div>
            <div class="font-heading font-extrabold text-3xl text-[#1A1A2E]">{{ number_format($totalActiveUsers) }}</div>
            <div class="mt-2 text-xs font-bold text-slate-600 flex items-center gap-1">
                <span class="text-emerald-600 font-extrabold">● {{ number_format($totalUsers) }} Total Terdaftar</span>
            </div>
        </div>

        <!-- Card 2: User Baru (Hari/Minggu/Bulan) -->
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-2xl p-5 hover:translate-y-[-2px] transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Pertumbuhan User</span>
                <span class="p-2 bg-[#00D4AA] border-2 border-[#1A1A2E] rounded-xl text-lg shadow-[2px_2px_0px_#1A1A2E]">📈</span>
            </div>
            <div class="font-heading font-extrabold text-3xl text-[#1A1A2E]">+{{ number_format($newUsersMonthly) }} <span class="text-sm font-medium text-slate-500">/ bulan</span></div>
            <div class="mt-2 text-xs font-bold text-slate-600 flex gap-2">
                <span class="bg-emerald-100 text-emerald-800 px-1.5 py-0.5 rounded border border-emerald-300">Hari ini: +{{ $newUsersDaily }}</span>
                <span class="bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded border border-blue-300">Minggu ini: +{{ $newUsersWeekly }}</span>
            </div>
        </div>

        <!-- Card 3: Total Itinerary -->
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-2xl p-5 hover:translate-y-[-2px] transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Itinerary Dibuat</span>
                <span class="p-2 bg-[#FF6B9D] border-2 border-[#1A1A2E] text-white rounded-xl text-lg shadow-[2px_2px_0px_#1A1A2E]">🗺️</span>
            </div>
            <div class="font-heading font-extrabold text-3xl text-[#1A1A2E]">{{ number_format($totalItineraries) }}</div>
            <div class="mt-2 text-xs font-bold text-slate-600 flex gap-2">
                <span class="text-pink-600 font-extrabold">{{ number_format($publishedItineraries) }} Publik</span>
                <span>•</span>
                <span class="text-slate-500">{{ number_format($draftItineraries) }} Private</span>
            </div>
        </div>

        <!-- Card 4: XP Sisa / Total XP -->
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[4px_4px_0px_#1A1A2E] rounded-2xl p-5 hover:translate-y-[-2px] transition-all">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-extrabold uppercase tracking-wider text-slate-500">Total XP Beredar</span>
                <span class="p-2 bg-[#7B2FF7] border-2 border-[#1A1A2E] text-white rounded-xl text-lg shadow-[2px_2px_0px_#1A1A2E]">⭐</span>
            </div>
            <div class="font-heading font-extrabold text-3xl text-[#7B2FF7]">{{ number_format($totalXpCirculating) }} XP</div>
            <div class="mt-2 text-xs font-bold text-purple-700 flex items-center gap-1">
                <span>🏆 Distribusi dari Gamifikasi</span>
            </div>
        </div>
    </div>

    <!-- Main Chart & Recent Activities Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- User Growth Chart (2 Cols) -->
        <div class="lg:col-span-2 bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl p-6">
            <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-slate-200">
                <div>
                    <h2 class="font-heading font-bold text-xl text-[#1A1A2E]">Grafik Pertumbuhan User</h2>
                    <p class="text-xs text-slate-500 font-bold mt-0.5">Tren registrasi akun pengguna 14 hari terakhir</p>
                </div>
                <span class="px-3 py-1 bg-[#FFE156] border-2 border-[#1A1A2E] rounded-lg text-xs font-extrabold shadow-[2px_2px_0px_#1A1A2E]">
                    Harian
                </span>
            </div>

            <!-- User Growth Chart.js Canvas -->
            <div class="relative h-64 w-full">
                <canvas id="userGrowthChart"></canvas>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs font-bold text-slate-500 pt-3 border-t-2 border-slate-100">
                <span>14 Hari Lalu</span>
                <span class="text-[#4361EE] font-extrabold">Total Akun: {{ $totalUsers }} User</span>
                <span>Hari Ini</span>
            </div>
        </div>

        <!-- Recent Activity Feed (1 Col) -->
        <div class="bg-white border-[3px] border-[#1A1A2E] shadow-[6px_6px_0px_#1A1A2E] rounded-2xl p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4 pb-3 border-b-2 border-slate-200">
                <h2 class="font-heading font-bold text-xl text-[#1A1A2E]">Aktivitas Terbaru</h2>
                <span class="w-3 h-3 bg-emerald-500 rounded-full animate-ping"></span>
            </div>

            <div class="space-y-4 overflow-y-auto max-h-[380px] pr-1 flex-1">
                @forelse($activities as $act)
                    <div class="p-3 bg-[#FFFBEB] rounded-xl border-2 border-[#1A1A2E] flex items-start gap-3 hover:translate-x-1 transition-all">
                        <div class="p-2 rounded-lg border-2 border-[#1A1A2E] text-base shrink-0 shadow-[2px_2px_0px_#1A1A2E]" style="background-color: {{ $act['badge_bg'] }};">
                            {{ $act['icon'] }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center justify-between gap-1">
                                <span class="font-extrabold text-xs text-[#1A1A2E] truncate">{{ $act['title'] }}</span>
                                <span class="text-[10px] font-bold text-slate-400 shrink-0">{{ $act['timestamp']->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs text-slate-700 font-medium leading-snug mt-0.5 truncate">{{ $act['description'] }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-slate-400 font-bold text-sm">
                        Belum ada aktivitas tercatat.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('userGrowthChart');
        if (!ctx) return;

        const labels = {!! json_encode($chartLabels) !!};
        const dataValues = {!! json_encode($chartValues) !!};

        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Registrasi User',
                    data: dataValues,
                    borderColor: '#4361EE',
                    backgroundColor: 'rgba(67, 97, 238, 0.12)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#FFE156',
                    pointBorderColor: '#1A1A2E',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#FF6B9D',
                    pointHoverBorderColor: '#1A1A2E',
                    pointHoverBorderWidth: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1A1A2E',
                        titleFont: {
                            family: 'Plus Jakarta Sans',
                            size: 12,
                            weight: 'bold'
                        },
                        bodyFont: {
                            family: 'Plus Jakarta Sans',
                            size: 13,
                            weight: 'bold'
                        },
                        padding: 10,
                        cornerRadius: 8,
                        borderColor: '#FFE156',
                        borderWidth: 2,
                        callbacks: {
                            label: function(context) {
                                return ' ' + context.parsed.y + ' User Baru';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: true,
                            borderColor: '#1A1A2E',
                            borderWidth: 2
                        },
                        ticks: {
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 11,
                                weight: '600'
                            },
                            color: '#64748B'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grace: 1,
                        grid: {
                            color: '#E2E8F0',
                            drawBorder: true,
                            borderColor: '#1A1A2E',
                            borderWidth: 2
                        },
                        ticks: {
                            stepSize: 1,
                            precision: 0,
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 11,
                                weight: '600'
                            },
                            color: '#64748B'
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
