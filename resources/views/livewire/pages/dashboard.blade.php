{{-- resources/views/livewire/pages/dashboard.blade.php --}}
<div class="p-4 sm:p-6 lg:p-8 text-gray-900 dark:text-gray-100">

    {{-- Header & Tombol Aksi Cepat --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white">Selamat Datang Kembali 👋</h2>
            <p class="mt-2 text-md text-gray-500 dark:text-gray-400">Berikut adalah ringkasan kondisi keuangan Anda.</p>
        </div>
        <div class="flex items-center gap-x-2 mt-4 md:mt-0">
            <a href="{{ route('transfer') }}" wire:navigate
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-gray-600 rounded-lg shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-offset-gray-900">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                </svg>
                Transfer
            </a>
            <a href="{{ route('transactions.create') }}" wire:navigate
                class="inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-indigo-600 rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-900">
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd" />
                </svg>
                Transaksi Baru
            </a>
        </div>
    </div>

    {{-- Konten Utama Dasbor --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">

        {{-- Kolom Kiri (Konten Utama) --}}
        <div class="lg:col-span-8 space-y-6 lg:space-y-8">

            {{-- Card: Grafik Tren Keuangan --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md">
                <h3 class="text-lg font-semibold mb-4">Tren 6 Bulan Terakhir</h3>
                <div id="financial-trend-chart" wire:ignore></div>
            </div>

            {{-- Card: Aktivitas Terbaru --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md">
                <h3 class="text-lg font-semibold mb-4">Aktivitas Terbaru</h3>
                <div class="space-y-4">
                    @forelse ($latestActivities as $activity)
                        @if ($activity->activity_type == 'transaction')
                            <div class="flex items-center space-x-4">
                                <div
                                    class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full {{ $activity->data->type === 'income' ? 'bg-green-100 dark:bg-green-800/50' : 'bg-red-100 dark:bg-red-800/50' }}">
                                    <svg class="h-6 w-6 {{ $activity->data->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        @if ($activity->data->type === 'income')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M18 12H6" />
                                        @endif
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $activity->data->category->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $activity->data->fundSource->name }}</p>
                                </div>
                                <div class="text-right">
                                    <p
                                        class="text-base font-semibold {{ $activity->data->type === 'income' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                        {{ $activity->data->type === 'income' ? '+' : '-' }} Rp
                                        {{ number_format($activity->data->amount, 0, ',', '.') }}
                                    </p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($activity->activity_date)->format('d M Y') }}</p>
                                </div>
                                @if ($activity->data->fund_source_transfer_id)
                                    <a href="{{ route('transfers.edit', $activity->data->fund_source_transfer_id) }}"
                                        wire:navigate
                                        class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400"
                                        title="Edit Biaya di Transfer Induk">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                            <path
                                                d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                        </svg>
                                    </a>
                                @else
                                    <a href="{{ route('transactions.edit', $activity->data) }}" wire:navigate
                                        class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400"
                                        title="Edit Transaksi">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path
                                                d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                            <path
                                                d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="flex items-center space-x-4">
                                <div
                                    class="flex-shrink-0 w-10 h-10 flex items-center justify-center rounded-full bg-blue-100 dark:bg-blue-800/50">
                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-400"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-gray-100">Transfer Dana</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Dari <span
                                            class="font-medium">{{ $activity->data->fromFundSource->name }}</span> ke
                                        <span class="font-medium">{{ $activity->data->toFundSource->name }}</span></p>
                                </div>
                                <div class="text-right">
                                    <p class="text-base font-semibold text-gray-700 dark:text-gray-300">Rp
                                        {{ number_format($activity->data->amount, 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($activity->activity_date)->format('d M Y') }}</p>
                                </div>
                                <a href="{{ route('transfers.edit', $activity->data) }}" wire:navigate
                                    class="text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400"
                                    title="Edit Transfer">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                        <path
                                            d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                    </svg>
                                </a>
                            </div>
                        @endif
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">Belum ada aktivitas tercatat.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kolom Kanan (Sidebar Informasi) --}}
        <div class="lg:col-span-4 space-y-6 lg:space-y-8">

            {{-- Card: Total Saldo --}}
            <div class="bg-indigo-600 dark:bg-indigo-700 p-6 rounded-2xl shadow-lg text-white">
                <h3 class="text-lg font-medium text-indigo-200">Total Saldo Anda</h3>
                <p class="text-4xl font-bold tracking-tight mt-2">Rp {{ number_format($totalBalance, 0, ',', '.') }}
                </p>
                <p class="text-sm text-indigo-200 mt-1">Saldo dari semua sumber dana.</p>
            </div>

            {{-- Grid untuk Pemasukan & Pengeluaran --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 lg:gap-8">
                {{-- Card: Pemasukan Bulanan --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md">
                    <h3 class="font-semibold text-gray-500 dark:text-gray-400">Pemasukan Bulan Ini</h3>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">Rp
                        {{ number_format($monthlyIncome, 0, ',', '.') }}</p>
                </div>
                {{-- Card: Pengeluaran Bulanan --}}
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md">
                    <h3 class="font-semibold text-gray-500 dark:text-gray-400">Pengeluaran Bulan Ini</h3>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-2">Rp
                        {{ number_format($monthlyExpense, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Card: Ringkasan Sumber Dana --}}
            <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-md">
                <h3 class="text-lg font-semibold mb-4">Ringkasan Sumber Dana</h3>
                <div class="space-y-4">
                    @forelse ($fundSources as $fundSource)
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span
                                    class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $fundSource->name }}</span>
                                <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Rp
                                    {{ number_format($fundSource->balance, 0, ',', '.') }}</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                @php
                                    $percentage = $totalBalance > 0 ? ($fundSource->balance / $totalBalance) * 100 : 0;
                                @endphp
                                <div class="bg-indigo-500 dark:bg-indigo-600 h-2 rounded-full"
                                    style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-gray-500 dark:text-gray-400 py-8">Belum ada sumber dana.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:navigated', () => {
            const chartData = @json($financialTrendData);

            const options = {
                series: [{
                    name: 'Pemasukan',
                    data: chartData.income,
                    color: '#10B981'
                }, {
                    name: 'Pengeluaran',
                    data: chartData.expense,
                    color: '#EF4444'
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: false
                    },
                    background: 'transparent'
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '60%',
                        borderRadius: 8
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 4,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: chartData.labels,
                    labels: {
                        style: {
                            colors: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280'
                        }
                    },
                    axisBorder: {
                        show: false
                    },
                    axisTicks: {
                        show: false
                    }
                },
                yaxis: {
                    labels: {
                        style: {
                            colors: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280'
                        },
                        formatter: (val) => {
                            return new Intl.NumberFormat('id-ID', {
                                notation: 'compact'
                            }).format(val);
                        }
                    }
                },
                fill: {
                    opacity: 1
                },
                tooltip: {
                    theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                    y: {
                        formatter: (val) => {
                            return "Rp " + new Intl.NumberFormat('id-ID').format(val)
                        }
                    }
                },
                legend: {
                    labels: {
                        colors: document.documentElement.classList.contains('dark') ? '#9CA3AF' : '#6B7280'
                    }
                },
                grid: {
                    borderColor: document.documentElement.classList.contains('dark') ? '#374151' : '#E5E7EB',
                    strokeDashArray: 4
                }
            };

            const chart = new ApexCharts(document.querySelector("#financial-trend-chart"), options);
            chart.render();
        });
    </script>
@endpush
