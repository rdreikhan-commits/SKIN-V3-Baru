<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-extrabold text-xl text-slate-900 tracking-tight leading-tight flex items-center gap-2">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    <span>Pengaturan Fitur Himpunan Mahasiswa (HIMA)</span>
                </h2>
                <p class="text-xs text-slate-500 mt-1">
                    Atur fitur secara global untuk semua Himpunan atau tentukan fitur spesifik per-Himpunan sesuai kebijakan BKHM.
                </p>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Otoritas Penuh BKHM
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Message --}}
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-800 text-sm shadow-xs animate-in fade-in duration-300">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0 text-emerald-600 font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <div>
                        <strong class="font-bold">Berhasil!</strong> {{ session('success') }}
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-center gap-3 text-amber-800 text-sm shadow-xs">
                    <div class="w-8 h-8 rounded-xl bg-amber-100 flex items-center justify-center shrink-0 text-amber-600 font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        {{ session('warning') }}
                    </div>
                </div>
            @endif

            {{-- Target Himpunan Selector Card --}}
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 space-y-4">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 uppercase tracking-wider flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            Pilih Target Pengaturan Fitur
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Pilih apakah ingin mengatur <strong>Default Global (semua HIMA)</strong> atau <strong>Himpunan spesifik</strong> di bawah ini.
                        </p>
                    </div>

                    {{-- Dropdown selector untuk layar kecil atau cepat --}}
                    <div class="sm:w-64">
                        <select onchange="window.location.href=this.value" class="w-full text-xs font-semibold rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 py-2">
                            <option value="{{ route('bkhm.hima-fitur.index', ['target' => 'all']) }}" {{ $target === 'all' ? 'selected' : '' }}>
                                🌐 Default Semua Himpunan
                            </option>
                            @foreach($himaList as $h)
                                <option value="{{ route('bkhm.hima-fitur.index', ['target' => $h->id]) }}" {{ $target == $h->id ? 'selected' : '' }}>
                                    🎓 {{ $h->name }} {{ $h->prodi ? "({$h->prodi})" : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tab List / Pill Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 pt-2">
                    {{-- Tab 1: Default Global --}}
                    <a href="{{ route('bkhm.hima-fitur.index', ['target' => 'all']) }}"
                       class="p-4 rounded-2xl border transition-all text-left flex flex-col justify-between gap-3 {{ $target === 'all' ? 'bg-indigo-600 text-white border-indigo-600 shadow-md ring-2 ring-indigo-600/30' : 'bg-slate-50 hover:bg-slate-100 text-slate-800 border-slate-200' }}">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-base">🌐</span>
                                    <h4 class="font-extrabold text-sm {{ $target === 'all' ? 'text-white' : 'text-slate-900' }}">
                                        Default Semua HIMA
                                    </h4>
                                </div>
                                <p class="text-[11px] mt-1 {{ $target === 'all' ? 'text-indigo-100' : 'text-slate-500' }}">
                                    Berlaku untuk seluruh himpunan yang belum dikustomisasi.
                                </p>
                            </div>
                        </div>
                        <div>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold {{ $target === 'all' ? 'bg-indigo-500 text-white' : 'bg-slate-200 text-slate-700' }}">
                                Pengaturan Global
                            </span>
                        </div>
                    </a>

                    {{-- Tab per HIMA --}}
                    @foreach($himaSummary as $summary)
                        @php
                            $hUser = $summary['user'];
                            $isThisSelected = ($target == $hUser->id);
                        @endphp
                        <a href="{{ route('bkhm.hima-fitur.index', ['target' => $hUser->id]) }}"
                           class="p-4 rounded-2xl border transition-all text-left flex flex-col justify-between gap-3 {{ $isThisSelected ? 'bg-indigo-600 text-white border-indigo-600 shadow-md ring-2 ring-indigo-600/30' : 'bg-slate-50 hover:bg-slate-100 text-slate-800 border-slate-200' }}">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-base">🎓</span>
                                        <h4 class="font-extrabold text-sm line-clamp-1 {{ $isThisSelected ? 'text-white' : 'text-slate-900' }}">
                                            {{ $hUser->name }}
                                        </h4>
                                    </div>
                                    <p class="text-[11px] mt-1 {{ $isThisSelected ? 'text-indigo-100' : 'text-slate-500' }}">
                                        Prodi: {{ $hUser->prodi ?? 'Program Studi' }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 flex-wrap">
                                @if($summary['has_custom'])
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold {{ $isThisSelected ? 'bg-emerald-400 text-slate-950 font-extrabold' : 'bg-emerald-100 text-emerald-800 border border-emerald-200' }}">
                                        ⚡ Khusus ({{ $summary['active_count'] }} Fitur Aktif)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[10px] font-bold {{ $isThisSelected ? 'bg-indigo-500 text-indigo-100' : 'bg-slate-200 text-slate-600' }}">
                                        🌐 Mengikuti Default
                                    </span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Target Information Banner --}}
            @if($selectedHima)
                <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full pointer-events-none"></div>
                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="max-w-2xl">
                            <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg bg-amber-400 text-slate-950 font-extrabold text-[11px] uppercase tracking-wider mb-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                Mode Pengaturan Khusus Himpunan
                            </div>
                            <h3 class="text-xl font-extrabold text-white">
                                {{ $selectedHima->name }}
                            </h3>
                            <p class="text-xs text-indigo-200 mt-1">
                                Program Studi: <span class="font-semibold text-white">{{ $selectedHima->prodi ?? '-' }}</span> | Email: <span class="font-mono text-slate-300">{{ $selectedHima->email }}</span>
                            </p>
                            <p class="text-xs text-slate-300 mt-3 leading-relaxed">
                                Ceklis fitur di bawah ini <strong>hanya berlaku khusus untuk {{ $selectedHima->name }}</strong>. Anda dapat memilih fitur apa saja yang diizinkan untuk himpunan ini secara mandiri tanpa memengaruhi himpunan lain.
                            </p>
                        </div>

                        <div class="flex flex-col sm:flex-row md:flex-col items-start md:items-end gap-2 shrink-0">
                            @if($hasCustom)
                                <div class="px-3 py-1.5 rounded-xl bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-xs font-bold flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                    Status: Pengaturan Khusus Aktif
                                </div>
                                <form action="{{ route('bkhm.hima-fitur.reset', $selectedHima) }}" method="POST"
                                      onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan pengaturan fitur {{ $selectedHima->name }} ke pengaturan default global?')">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition border border-white/20 flex items-center gap-1.5">
                                        <svg class="w-3.5 h-3.5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                        Kembalikan ke Default Global
                                    </button>
                                </form>
                            @else
                                <div class="px-3 py-1.5 rounded-xl bg-amber-500/20 text-amber-300 border border-amber-400/30 text-xs font-bold flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                    Status: Mengikuti Default Global
                                </div>
                                <p class="text-[11px] text-slate-300">
                                    Menyimpan form di bawah akan membuat pengaturan khusus untuk himpunan ini.
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-white/5 rounded-full pointer-events-none"></div>
                    <div class="relative z-10 max-w-3xl">
                        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg bg-amber-400 text-slate-950 font-extrabold text-[11px] uppercase tracking-wider mb-3">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Pengaturan Default Global (Semua HIMA)
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2">Pengaturan Fitur Dasar Seluruh Himpunan Mahasiswa</h3>
                        <p class="text-xs text-slate-300 leading-relaxed mb-4">
                            Konfigurasi ini menjadi acuan default bagi seluruh HIMA di kampus ITG. Jika sebuah HIMA belum disetel khusus, maka fitur yang muncul di panel navigasi mereka akan mengikuti ceklis default ini.
                        </p>
                        <div class="flex flex-wrap gap-4 text-xs text-slate-200">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Perubahan berlaku real-time di sidebar HIMA</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <span>Rute fitur yang dinonaktifkan akan diproteksi otomatis</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Form Ceklis Fitur --}}
            <form action="{{ route('bkhm.hima-fitur.update') }}" method="POST" x-data="{
                semuaDipilih: false,
                setAll(val) {
                    const checkboxes = document.querySelectorAll('input[name=\'aktif_fitur[]\']');
                    checkboxes.forEach(cb => cb.checked = val);
                    this.semuaDipilih = val;
                }
            }">
                @csrf
                <input type="hidden" name="target" value="{{ $target }}">

                <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                    {{-- Header Toolbar Form --}}
                    <div class="p-6 border-b border-slate-200/80 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-base font-extrabold text-slate-900">
                                    Daftar Modul & Fitur
                                </h3>
                                @if($selectedHima)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                        Khusus: {{ $selectedHima->name }}
                                    </span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-slate-100 text-slate-800 border border-slate-200">
                                        Default Global
                                    </span>
                                @endif
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">
                                Centang kotak untuk mengaktifkan fitur pada {{ $selectedHima ? $selectedHima->name : 'organisasi Himpunan (HIMA)' }}.
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button type="button" @click="setAll(true)" class="px-3 py-1.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition border border-indigo-200">
                                Centang Semua
                            </button>
                            <button type="button" @click="setAll(false)" class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition border border-slate-200">
                                Hapus Semua Centang
                            </button>
                        </div>
                    </div>

                    {{-- List Fitur --}}
                    <div class="divide-y divide-slate-100">
                        @foreach($fiturList as $fitur)
                            <div class="p-5 sm:p-6 hover:bg-slate-50/80 transition flex flex-col sm:flex-row sm:items-start justify-between gap-4 group"
                                 x-data="{ aktif: {{ $fitur->is_aktif ? 'true' : 'false' }} }">
                                <div class="flex items-start gap-4">
                                    {{-- Custom Checkbox / Toggle --}}
                                    <div class="pt-0.5">
                                        <label class="relative flex items-center justify-center cursor-pointer select-none">
                                            <input type="checkbox"
                                                   name="aktif_fitur[]"
                                                   value="{{ $fitur->fitur_key }}"
                                                   x-model="aktif"
                                                   class="w-5 h-5 rounded-lg border-slate-300 text-indigo-600 focus:ring-indigo-500 focus:ring-offset-1 transition cursor-pointer">
                                        </label>
                                    </div>

                                    {{-- Info Fitur --}}
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2">
                                            <h4 class="text-sm font-extrabold text-slate-900 group-hover:text-indigo-900 transition">
                                                {{ $fitur->fitur_label }}
                                            </h4>
                                            <span class="px-2 py-0.5 rounded-md font-mono text-[10px] bg-slate-100 text-slate-600 border border-slate-200">
                                                key: {{ $fitur->fitur_key }}
                                            </span>
                                            <template x-if="aktif">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                                    Aktif
                                                </span>
                                            </template>
                                            <template x-if="!aktif">
                                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-200 text-slate-700 border border-slate-300 flex items-center gap-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-500"></span>
                                                    Dinonaktifkan
                                                </span>
                                            </template>
                                        </div>

                                        <p class="text-xs text-slate-600 mt-1 max-w-2xl leading-relaxed">
                                            {{ $fitur->deskripsi }}
                                        </p>

                                        {{-- Cakupan Menu --}}
                                        <div class="mt-2.5 flex flex-wrap items-center gap-1.5">
                                            <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mr-1">Cakupan menu:</span>
                                            @if($fitur->fitur_key === 'pengajuan')
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px]">Buat Pengajuan</span>
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px]">Riwayat Pengajuan Dana</span>
                                            @elseif($fitur->fitur_key === 'proker')
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px]">Input Program Kerja</span>
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px]">Monitoring & Rekap Proker</span>
                                            @elseif($fitur->fitur_key === 'sarpras')
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px]">Peminjaman Tempat / Ruangan</span>
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px]">Peminjaman Sarana / Barang</span>
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px]">Kalender Pemakaian Fasilitas</span>
                                            @elseif($fitur->fitur_key === 'persuratan')
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px]">Generator Proposal Digital</span>
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px]">Generator Surat Dinas/Lainnya</span>
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px]">Generator LPJ Digital</span>
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px]">Arsip Digital Dokumen</span>
                                            @elseif($fitur->fitur_key === 'lpj')
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px]">Monitoring Laporan Pertanggungjawaban (LPJ)</span>
                                            @elseif($fitur->fitur_key === 'prestasi')
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px]">Pelaporan Prestasi & Kompetisi</span>
                                            @elseif($fitur->fitur_key === 'sp')
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px]">Surat Peringatan (SP) Saya</span>
                                            @elseif($fitur->fitur_key === 'informasi')
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px]">Pusat Informasi Publik</span>
                                                <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-600 text-[11px]">Jadwal Rapat & Koordinasi</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Quick Badge Status --}}
                                <div class="shrink-0 self-center sm:self-start">
                                    <template x-if="aktif">
                                        <button type="button" @click="aktif = false" class="text-xs font-semibold text-rose-600 hover:text-rose-800 transition">
                                            Sembunyikan
                                        </button>
                                    </template>
                                    <template x-if="!aktif">
                                        <button type="button" @click="aktif = true" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
                                            Tampilkan
                                        </button>
                                    </template>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Footer Action Bar --}}
                    <div class="p-6 bg-slate-50 border-t border-slate-200/80 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div class="text-xs text-slate-500">
                            @if($selectedHima)
                                Menyimpan form ini akan memperbarui fitur khusus untuk <strong>{{ $selectedHima->name }}</strong>.
                            @else
                                Menyimpan form ini akan memperbarui konfigurasi default seluruh Himpunan.
                            @endif
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('dashboard') }}" class="px-4 py-2.5 rounded-xl border border-slate-300 text-slate-700 text-xs font-bold hover:bg-slate-100 transition">
                                Batal
                            </a>
                            <button type="submit" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold shadow-md shadow-indigo-600/20 transition flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                @if($selectedHima)
                                    <span>Simpan Fitur Khusus {{ $selectedHima->name }}</span>
                                @else
                                    <span>Simpan Pengaturan Default Global</span>
                                @endif
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Matriks Ringkasan Status Fitur Seluruh HIMA --}}
            <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-slate-200/80 bg-slate-50/50">
                    <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        Matriks Status Fitur Setiap Himpunan
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Ringkasan perbandingan hak akses dan fitur yang aktif pada masing-masing Himpunan Mahasiswa.
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50 text-[11px] font-extrabold text-slate-600 uppercase tracking-wider">
                                <th class="p-4 pl-6">Himpunan / Ormawa</th>
                                <th class="p-4">Status Aturan</th>
                                @foreach($masterFitur as $f)
                                    <th class="p-4 text-center">{{ $f->fitur_label }}</th>
                                @endforeach
                                <th class="p-4 pr-6 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs">
                            {{-- Baris Default Global --}}
                            <tr class="bg-indigo-50/40">
                                <td class="p-4 pl-6 font-bold text-slate-900 flex items-center gap-2">
                                    <span>🌐</span>
                                    <div>
                                        <div>Default Global (Semua HIMA)</div>
                                        <div class="text-[10px] text-slate-500 font-normal">Aturan standar sistem</div>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 text-indigo-800 border border-indigo-200">
                                        Standar Global
                                    </span>
                                </td>
                                @foreach($masterFitur as $f)
                                    <td class="p-4 text-center">
                                        @if($f->is_aktif)
                                            <span class="inline-flex w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 items-center justify-center font-bold text-xs">✓</span>
                                        @else
                                            <span class="inline-flex w-6 h-6 rounded-full bg-slate-100 text-slate-400 items-center justify-center font-bold text-xs">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="p-4 pr-6 text-right">
                                    <a href="{{ route('bkhm.hima-fitur.index', ['target' => 'all']) }}" class="px-3 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition">
                                        Atur Default
                                    </a>
                                </td>
                            </tr>

                            {{-- Baris masing-masing HIMA --}}
                            @foreach($himaList as $h)
                                @php
                                    $isCustom = \App\Models\HimaFiturSetting::hasCustomSettings($h->id);
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="p-4 pl-6 font-bold text-slate-900">
                                        <div class="flex items-center gap-2">
                                            <span>🎓</span>
                                            <div>
                                                <div>{{ $h->name }}</div>
                                                <div class="text-[10px] text-slate-500 font-normal">{{ $h->prodi ?? 'Program Studi' }} ({{ $h->email }})</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        @if($isCustom)
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200 flex items-center gap-1 w-max">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                                Pengaturan Khusus
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 w-max">
                                                Mengikuti Default
                                            </span>
                                        @endif
                                    </td>
                                    @foreach($masterFitur as $f)
                                        @php
                                            $aktifDiHima = \App\Models\HimaFiturSetting::isAktif($f->fitur_key, $h);
                                        @endphp
                                        <td class="p-4 text-center">
                                            @if($aktifDiHima)
                                                <span class="inline-flex w-6 h-6 rounded-full bg-emerald-100 text-emerald-700 items-center justify-center font-bold text-xs" title="Aktif">✓</span>
                                            @else
                                                <span class="inline-flex w-6 h-6 rounded-full bg-slate-100 text-slate-400 items-center justify-center font-bold text-xs" title="Nonaktif">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td class="p-4 pr-6 text-right">
                                        <a href="{{ route('bkhm.hima-fitur.index', ['target' => $h->id]) }}" class="px-3 py-1 rounded-lg bg-indigo-50 hover:bg-indigo-100 text-indigo-700 text-xs font-bold transition">
                                            Atur HIMA Ini
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
