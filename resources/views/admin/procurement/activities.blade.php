<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Premium Header Section -->
        <div class="bg-gradient-to-br from-indigo-900 via-indigo-800 to-brand-900 rounded-3xl p-8 sm:p-10 mb-10 shadow-premium relative overflow-hidden">
            <!-- Decorative blur elements -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-72 h-72 rounded-full bg-brand-500/20 blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-72 h-72 rounded-full bg-indigo-500/20 blur-3xl"></div>
            
            <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/20 backdrop-blur-md mb-4">
                        <span class="h-2 w-2 rounded-full bg-brand-400 animate-pulse"></span>
                        <span class="text-[10px] font-extrabold uppercase tracking-widest text-white">Log Aktivitas Rantai Pasok</span>
                    </div>
                    <h1 class="text-3xl sm:text-4xl font-heading font-black text-white mb-2 tracking-tight">Riwayat Estimasi Panen</h1>
                    <p class="text-indigo-100/80 text-sm max-w-xl font-medium">Lacak semua pergerakan dan status estimasi panen dari mitra petani secara real-time. Memastikan transparansi dan kualitas dari kebun hingga ke gudang.</p>
                </div>
                <div class="shrink-0 flex items-center gap-3">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-xl font-bold text-sm transition-all duration-300 backdrop-blur-md hover:scale-105 active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="bg-white/80 backdrop-blur-xl border border-gray-150 rounded-3xl shadow-glass overflow-hidden relative">
            
            <div class="px-8 py-6 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-2xl bg-gradient-to-tr from-brand-500 to-indigo-500 flex items-center justify-center text-white shadow-lg shadow-brand-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <h2 class="font-heading font-black text-gray-800 text-lg">Timeline Aktivitas Terbaru</h2>
                        <p class="text-xs text-gray-400 font-medium">Diurutkan dari yang paling baru.</p>
                    </div>
                </div>
            </div>

            <div class="p-8">
                @if($activities->isEmpty())
                    <div class="py-16 text-center flex flex-col items-center justify-center">
                        <div class="h-24 w-24 rounded-full bg-gray-50 flex items-center justify-center mb-4">
                            <span class="text-4xl">📭</span>
                        </div>
                        <h3 class="text-lg font-bold text-gray-700">Belum Ada Aktivitas</h3>
                        <p class="text-sm text-gray-400 mt-1">Sistem belum mencatat adanya estimasi panen yang masuk.</p>
                    </div>
                @else
                    <div class="relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">
                        @foreach($activities as $index => $est)
                        <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active mb-8 last:mb-0">
                            <!-- Icon -->
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border-4 border-white bg-{{ $est->status_color }}-100 text-{{ $est->status_color }}-600 shadow-md shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10 transition-transform duration-300 group-hover:scale-110">
                                @if($est->status === 'pending')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" /></svg>
                                @elseif($est->status === 'approved')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                @elseif($est->status === 'rejected')
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" /><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd" /></svg>
                                @endif
                            </div>
                            
                            <!-- Card -->
                            <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-5 rounded-2xl shadow-sm border border-gray-100 hover:shadow-premium-hover transition-all duration-300 group-hover:-translate-y-1 group-hover:border-{{ $est->status_color }}-200">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="px-3 py-1 text-[9px] font-extrabold rounded-full uppercase bg-{{ $est->status_color }}-50 text-{{ $est->status_color }}-700 border border-{{ $est->status_color }}-100 tracking-wider">
                                        {{ $est->status_label }}
                                    </span>
                                    <time class="text-[10px] font-bold text-gray-400">{{ $est->created_at->format('d M Y, H:i') }}</time>
                                </div>
                                <h3 class="font-heading font-extrabold text-gray-800 text-lg mb-1">{{ $est->fruit_type }}</h3>
                                <div class="flex items-center gap-4 mt-3 pt-3 border-t border-gray-50">
                                    <div class="flex items-center gap-1.5">
                                        <div class="h-6 w-6 rounded-lg bg-gray-100 flex items-center justify-center text-[10px]">🧑‍🌾</div>
                                        <span class="text-xs font-semibold text-gray-600">{{ $est->user->name }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5">
                                        <div class="h-6 w-6 rounded-lg bg-emerald-50 flex items-center justify-center text-[10px]">⚖️</div>
                                        <span class="text-xs font-bold text-emerald-600">{{ number_format($est->estimated_weight_kg, 2) }} Kg</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
            
            @if($activities->hasPages())
            <div class="px-8 py-5 border-t border-gray-100 bg-gray-50/50">
                {{ $activities->links() }}
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
