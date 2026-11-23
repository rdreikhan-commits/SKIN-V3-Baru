<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-bold text-xl text-slate-900 leading-tight">
                    Monitoring &amp; Transparansi Anggaran Organisasi Mahasiswa
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Pengawasan legislatif BPM ITG terhadap alokasi pagu, realisasi dana kegiatan, dan sisa saldo seluruh HIMA, UKM, BEM, dan BPM.
                </p>
            </div>
            <a href="{{ route('bpm.dashboard') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold shadow-2xs transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Dashboard BPM</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm flex items-center gap-2 shadow-2xs">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm flex items-center gap-2 shadow-2xs">
                    <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- 4 Ringkasan KPI Konsolidasi Anggaran -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-slate-200">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Pagu Kampus ITG</span>
                        <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-slate-900 mt-2 font-mono">Rp {{ number_format($totalPaguAll, 0, ',', '.') }}</div>
                    <div class="text-[11px] text-slate-400 mt-1">Konsolidasi pagu seluruh organisasi</div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-amber-200/80 bg-gradient-to-br from-white to-amber-50/30">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-amber-800 uppercase tracking-wider">Total Dana Terealisasi</span>
                        <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-amber-700 mt-2 font-mono">Rp {{ number_format($totalTerpakaiAll, 0, ',', '.') }}</div>
                    <div class="text-[11px] text-amber-600 font-medium mt-1">Sudah terpakai &amp; diproses</div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-emerald-200/80 bg-gradient-to-br from-white to-emerald-50/30">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-emerald-800 uppercase tracking-wider">Total Sisa Kas Bersama</span>
                        <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-emerald-700 mt-2 font-mono">Rp {{ number_format($totalSisaAll, 0, ',', '.') }}</div>
                    <div class="text-[11px] text-emerald-600 font-medium mt-1">Sisa saldo aktif tersedia</div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-indigo-200/80 bg-gradient-to-br from-white to-indigo-50/30">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-indigo-800 uppercase tracking-wider">Rata-rata Serapan ITG</span>
                        <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                    </div>
                    <div class="text-2xl font-black text-indigo-700 mt-2 font-mono">{{ $persenSerapanAll }}%</div>
                    <div class="text-[11px] text-indigo-600 font-medium mt-1">Efisiensi serapan anggaran</div>
                </div>
            </div>

            <!-- Kontrol Filter Kategori & Pencarian -->
            <div class="bg-white p-5 rounded-2xl shadow-2xs border border-slate-200 space-y-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <!-- Tab Kategori -->
                    <div class="flex flex-wrap items-center gap-1.5 p-1 bg-slate-100 rounded-xl">
                        <a href="{{ route('bpm.anggaran.index', ['kategori' => 'all', 'q' => $search]) }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $kategori === 'all' ? 'bg-white text-indigo-700 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                            Semua ({{ $counts['total'] }})
                        </a>
                        <a href="{{ route('bpm.anggaran.index', ['kategori' => 'hima', 'q' => $search]) }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $kategori === 'hima' ? 'bg-white text-indigo-700 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                            Himpunan Mahasiswa ({{ $counts['hima'] }})
                        </a>
                        <a href="{{ route('bpm.anggaran.index', ['kategori' => 'ukm', 'q' => $search]) }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $kategori === 'ukm' ? 'bg-white text-indigo-700 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                            UKM ({{ $counts['ukm'] }})
                        </a>
                        <a href="{{ route('bpm.anggaran.index', ['kategori' => 'bem', 'q' => $search]) }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $kategori === 'bem' ? 'bg-white text-indigo-700 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                            BEM ({{ $counts['bem'] }})
                        </a>
                        <a href="{{ route('bpm.anggaran.index', ['kategori' => 'bpm', 'q' => $search]) }}"
                           class="px-3 py-1.5 rounded-lg text-xs font-semibold transition {{ $kategori === 'bpm' ? 'bg-white text-indigo-700 shadow-xs' : 'text-slate-600 hover:text-slate-900' }}">
                            BPM ({{ $counts['bpm'] }})
                        </a>
                    </div>

                    <!-- Search Form -->
                    <form method="GET" action="{{ route('bpm.anggaran.index') }}" class="flex items-center gap-2">
                        <input type="hidden" name="kategori" value="{{ $kategori }}">
                        <div class="relative w-full sm:w-64">
                            <input type="text"
                                   name="q"
                                   value="{{ $search }}"
                                   placeholder="Cari organisasi / ketua..."
                                   class="w-full pl-9 pr-3 py-2 text-xs border-slate-300 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 shadow-2xs">
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <button type="submit" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-2xs transition">
                            Cari
                        </button>
                        @if($search !== '')
                            <a href="{{ route('bpm.anggaran.index', ['kategori' => $kategori]) }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition">
                                Reset
                            </a>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Tabel Daftar Anggaran Ormawa -->
            <div class="bg-white rounded-2xl shadow-2xs border border-slate-200 overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm sm:text-base">Daftar Anggaran Organisasi Mahasiswa</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Menampilkan seluruh entitas organisasi kemahasiswaan di lingkungan ITG</p>
                    </div>
                    <span class="text-xs font-semibold text-slate-500">{{ $organisasiList->count() }} Organisasi Ditemukan</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-xs text-left">
                        <thead class="bg-slate-50 font-bold text-slate-600 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3.5 text-center w-12">No</th>
                                <th class="px-4 py-3.5">Organisasi &amp; Jurusan</th>
                                <th class="px-4 py-3.5">Ketua &amp; Kontak</th>
                                <th class="px-4 py-3.5">Pagu Anggaran</th>
                                <th class="px-4 py-3.5">Terealisasi / Terpakai</th>
                                <th class="px-4 py-3.5">Sisa Saldo Kas</th>
                                <th class="px-4 py-3.5 w-44">Serapan (%)</th>
                                <th class="px-4 py-3.5 text-center">Status</th>
                                <th class="px-4 py-3.5 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($organisasiList as $i => $row)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-3.5 text-center font-bold text-slate-400">{{ $i + 1 }}</td>
                                    <td class="px-4 py-3.5">
                                        <div class="font-bold text-slate-900 text-sm">{{ $row['name'] }}</div>
                                        <div class="flex items-center gap-1.5 mt-1">
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                                {{ $row['tipe'] === 'HIMA' ? 'bg-indigo-100 text-indigo-700 border border-indigo-200' :
                                                   ($row['tipe'] === 'UKM' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' :
                                                   ($row['tipe'] === 'BEM' ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-purple-100 text-purple-700 border border-purple-200')) }}">
                                                {{ $row['tipe'] }}
                                            </span>
                                            @if(!empty($row['prodi']))
                                                <span class="text-[11px] text-slate-500 font-medium">{{ $row['prodi'] }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <div class="font-semibold text-slate-800">{{ $row['nama_ketua'] ?: '-' }}</div>
                                        <div class="text-[11px] text-slate-500 mt-0.5">{{ $row['kontak_ketua'] }}</div>
                                    </td>
                                    <td class="px-4 py-3.5 font-mono text-slate-700 font-semibold whitespace-nowrap">
                                        Rp {{ number_format($row['saldo_awal'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3.5 font-mono font-bold text-amber-600 whitespace-nowrap">
                                        Rp {{ number_format($row['terpakai'], 0, ',', '.') }}
                                        <div class="text-[10px] text-slate-400 font-normal font-sans">{{ $row['total_pengajuan'] }} proposal diajukan</div>
                                    </td>
                                    <td class="px-4 py-3.5 font-mono font-extrabold text-emerald-600 whitespace-nowrap">
                                        Rp {{ number_format($row['saldo'], 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 bg-slate-200 rounded-full h-2 overflow-hidden">
                                                <div class="h-2 rounded-full {{ $row['persen'] > 85 ? 'bg-rose-500' : ($row['persen'] > 50 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                                                     style="width: {{ min(100, $row['persen']) }}%;"></div>
                                            </div>
                                            <span class="font-mono font-bold text-slate-700 text-[11px] w-12 text-right">{{ $row['persen'] }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                        @if($row['terpakai'] <= 0)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600 text-[10px] font-semibold">
                                                Belum Ada Serapan
                                            </span>
                                        @elseif($row['persen'] >= 85)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 text-[10px] font-bold border border-rose-200">
                                                Serapan Tinggi
                                            </span>
                                        @elseif($row['persen'] >= 40)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200">
                                                Aktif &amp; Lancar
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-200">
                                                Serapan Rendah
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                        <a href="{{ route('bpm.anggaran.show', $row['id']) }}"
                                           class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-semibold rounded-lg text-xs transition border border-indigo-200">
                                            <span>Rincian</span>
                                            <span>&rarr;</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="px-4 py-12 text-center text-slate-400">
                                        <div class="max-w-xs mx-auto space-y-1">
                                            <svg class="w-8 h-8 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <div class="text-sm font-semibold text-slate-600">Tidak ada data organisasi</div>
                                            <div class="text-xs text-slate-400">Tidak ada organisasi mahasiswa yang cocok dengan kriteria filter saat ini.</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
