<x-app-layout>
    <x-slot name="header"><h2 class="font-bold text-xl text-slate-900 leading-tight">Dashboard BPM (Badan Perwakilan Mahasiswa)</h2></x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Alert Messages -->
            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl text-sm flex items-center gap-2 shadow-sm">
                    <svg class="w-5 h-5 text-rose-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Banner Selamat Datang Pimpinan / BPM (Slate-Indigo) -->
            <div class="bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 rounded-2xl shadow-sm border border-slate-800">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-400/30">
                                Lembaga Legislatif &amp; Pengawasan Mahasiswa
                            </span>
                            <span class="text-xs text-slate-300 font-medium">{{ now()->translatedFormat('l, d F Y') }}</span>
                        </div>
                        <h3 class="text-xl font-bold tracking-tight text-white">Selamat Datang, Presidium &amp; Anggota BPM ITG</h3>
                        <p class="text-xs text-slate-300 max-w-2xl leading-relaxed">
                            Jalankan pengawasan proposal kegiatan ormawa, telaah aspirasi publik mahasiswa, serta terbitkan regulasi resmi demi ketertiban dinamika kemahasiswaan Institut Teknologi Garut.
                        </p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('bpm.sp.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-semibold shadow-sm transition min-h-[44px] focus:outline-none focus-visible:ring-2 focus-visible:ring-amber-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>Buat SP BPM</span>
                        </a>
                        <a href="{{ route('bpm.sp.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-xs font-semibold shadow-sm transition min-h-[44px] focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Riwayat SP</span>
                        </a>
                        <a href="{{ route('bpm.regulasi.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2.5 bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 rounded-xl text-xs font-semibold shadow-sm transition min-h-[44px] focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-400">
                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>Pusat Regulasi</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Kartu Metrik Ringkasan Legislatif & Saldo -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('verifikasi.index') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 hover:border-indigo-300 transition flex items-center justify-between group">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Antrean Verifikasi Proposal</p>
                        <h4 class="text-2xl font-extrabold text-indigo-700 mt-1 font-mono">{{ $counts['verifikasi_proposal'] }}</h4>
                        <span class="text-[11px] text-indigo-600 font-semibold group-hover:underline">Buka Lembar Telaah &rarr;</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-lg flex-shrink-0 border border-indigo-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                </a>

                <a href="{{ route('bpm.aspirasi.index') }}" class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 hover:border-amber-300 transition flex items-center justify-between group">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Aspirasi Mahasiswa Masuk</p>
                        <h4 class="text-2xl font-extrabold text-amber-700 mt-1 font-mono">{{ $counts['aspirasi_masuk'] ?? 0 }}</h4>
                        <span class="text-[11px] text-amber-600 font-semibold group-hover:underline">Eskalasi ke BKHM &rarr;</span>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg flex-shrink-0 border border-amber-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                    </div>
                </a>
            </div>

            <!-- Widget Status Dana BPM (3 Cards) -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-[0_4px_20px_rgba(0,0,0,0.02)]">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-bold text-slate-900 text-sm sm:text-base">Status Pagu Anggaran BPM</h3>
                    <span class="text-xs font-semibold px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-200">Kas Internal BPM</span>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="border border-slate-200/80 p-4 rounded-xl text-center bg-slate-50/50">
                        <div class="text-xs text-slate-500 font-medium">Total Pagu Diberikan</div>
                        <div class="text-xl font-extrabold text-slate-900 mt-1 font-mono">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</div>
                    </div>
                    <div class="border border-slate-200/80 p-4 rounded-xl text-center bg-slate-50/50">
                        <div class="text-xs text-slate-500 font-medium">Saldo Terpakai &amp; Diproses</div>
                        <div class="text-xl font-extrabold text-amber-600 mt-1 font-mono">Rp {{ number_format($terpakai, 0, ',', '.') }}</div>
                    </div>
                    <div class="border border-slate-200/80 p-4 rounded-xl text-center bg-slate-50/50">
                        <div class="text-xs text-slate-500 font-medium">Sisa Saldo Tersedia</div>
                        <div class="text-xl font-extrabold text-emerald-600 mt-1 font-mono">Rp {{ number_format(Auth::user()->saldo, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

            <!-- Widget Pengawasan: Monitoring Anggaran Seluruh Organisasi Mahasiswa ITG -->
            <div class="bg-white border border-slate-200/80 rounded-2xl p-6 shadow-[0_4px_20px_rgba(0,0,0,0.02)] space-y-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 pb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                            <h3 class="font-bold text-slate-900 text-base sm:text-lg">Pengawasan Anggaran Seluruh Organisasi Mahasiswa</h3>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Pemantauan transparansi pagu anggaran, serapan dana kegiatan, dan sisa kas seluruh HIMA, UKM, dan BEM ITG.</p>
                    </div>
                    <a href="{{ route('bpm.anggaran.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-xs transition self-start sm:self-auto">
                        <span>Buka Rekap Lengkap</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>

                <!-- 4 KPI Global Anggaran Ormawa -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                    <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl">
                        <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Pagu Kampus ITG</div>
                        <div class="text-lg sm:text-xl font-extrabold text-slate-900 mt-1 font-mono">Rp {{ number_format($totalPaguAll ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="p-4 bg-amber-50/70 border border-amber-200 rounded-xl">
                        <div class="text-[11px] font-semibold text-amber-800 uppercase tracking-wider">Total Dana Terealisasi</div>
                        <div class="text-lg sm:text-xl font-extrabold text-amber-700 mt-1 font-mono">Rp {{ number_format($totalTerpakaiAll ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="p-4 bg-emerald-50/70 border border-emerald-200 rounded-xl">
                        <div class="text-[11px] font-semibold text-emerald-800 uppercase tracking-wider">Total Sisa Kas Bersama</div>
                        <div class="text-lg sm:text-xl font-extrabold text-emerald-700 mt-1 font-mono">Rp {{ number_format($totalSisaAll ?? 0, 0, ',', '.') }}</div>
                    </div>
                    <div class="p-4 bg-indigo-50/70 border border-indigo-200 rounded-xl">
                        <div class="text-[11px] font-semibold text-indigo-800 uppercase tracking-wider">Tingkat Serapan Total</div>
                        <div class="text-lg sm:text-xl font-extrabold text-indigo-700 mt-1 font-mono">{{ $persenSerapanAll ?? 0 }}%</div>
                    </div>
                </div>

                <!-- Preview Tabel Anggaran Ormawa -->
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="min-w-full divide-y divide-slate-200 text-xs text-left">
                        <thead class="bg-slate-50 font-bold text-slate-600 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 text-center w-12">No</th>
                                <th class="px-4 py-3">Organisasi Mahasiswa</th>
                                <th class="px-4 py-3">Pagu Awal</th>
                                <th class="px-4 py-3">Terpakai</th>
                                <th class="px-4 py-3">Sisa Kas</th>
                                <th class="px-4 py-3 w-44">Serapan Anggaran</th>
                                <th class="px-4 py-3 text-center w-28">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse(($allOrmawaWithSaldo ?? []) as $i => $ormawa)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-4 py-3 text-center font-bold text-slate-400">{{ $i + 1 }}</td>
                                    <td class="px-4 py-3">
                                        <div class="font-bold text-slate-900">{{ $ormawa['name'] }}</div>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <span class="px-1.5 py-0.2 rounded text-[10px] font-bold uppercase
                                                {{ $ormawa['tipe'] === 'HIMA' ? 'bg-indigo-100 text-indigo-700' : ($ormawa['tipe'] === 'UKM' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-800') }}">
                                                {{ $ormawa['tipe'] }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 font-mono text-slate-600 whitespace-nowrap">Rp {{ number_format($ormawa['saldo_awal'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 font-mono font-bold text-amber-600 whitespace-nowrap">Rp {{ number_format($ormawa['terpakai'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 font-mono font-extrabold text-emerald-600 whitespace-nowrap">Rp {{ number_format($ormawa['saldo'], 0, ',', '.') }}</td>
                                    <td class="px-4 py-3">
                                        <div class="flex items-center gap-2">
                                            <div class="flex-1 bg-slate-200 rounded-full h-2 overflow-hidden">
                                                <div class="h-2 rounded-full {{ $ormawa['persen'] > 80 ? 'bg-rose-500' : ($ormawa['persen'] > 50 ? 'bg-amber-500' : 'bg-emerald-500') }}" style="width: {{ min(100, $ormawa['persen']) }}%;"></div>
                                            </div>
                                            <span class="font-mono font-bold text-slate-700 text-[11px] w-10 text-right">{{ $ormawa['persen'] }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        <a href="{{ route('bpm.anggaran.show', $ormawa['id']) }}" class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 hover:bg-indigo-50 text-slate-700 hover:text-indigo-700 font-semibold rounded-lg text-[11px] border border-slate-200 hover:border-indigo-200 transition">
                                            <span>Detail</span>
                                            <span>&rarr;</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-8 text-center text-slate-400">Belum ada data anggaran organisasi mahasiswa.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Widget Antrean Verifikasi Proposal Legislatif --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm sm:text-base">Antrean Verifikasi Proposal Legislatif</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Proposal kegiatan yang telah disetujui BEM dan membutuhkan pertimbangan serta persetujuan BPM.</p>
                    </div>
                    <a href="{{ route('verifikasi.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-700 hover:text-blue-900 transition">
                        <span>Buka Semua Arsip</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                <x-table>
                    <x-table.thead>
                        <x-table.tr>
                            <x-table.th align="center" class="w-12">No</x-table.th>
                            <x-table.th>Nama Kegiatan</x-table.th>
                            <x-table.th>Organisasi Pengaju</x-table.th>
                            <x-table.th>Tanggal Diajukan</x-table.th>
                            <x-table.th align="right">Dana Diajukan</x-table.th>
                            <x-table.th align="center">Tindakan</x-table.th>
                        </x-table.tr>
                    </x-table.thead>
                    <tbody class="divide-y divide-slate-100 text-sm bg-white">
                        @forelse($proposalQueue as $i => $p)
                            <x-table.tr>
                                <x-table.td align="center" class="text-xs font-bold text-slate-500">{{ $i + 1 }}</x-table.td>
                                <x-table.td class="font-bold text-slate-900 text-sm">{{ $p->nama_kegiatan }}</x-table.td>
                                <x-table.td>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-bold">
                                        <svg class="w-3.5 h-3.5 text-blue-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        <span>{{ $p->user->name }}</span>
                                    </span>
                                </x-table.td>
                                <x-table.td class="text-xs text-slate-600 whitespace-nowrap">
                                    {{ $p->tanggal_pengajuan ?? $p->created_at->format('d/m/Y') }}
                                </x-table.td>
                                <x-table.td align="right" class="font-mono font-extrabold text-slate-900 text-xs whitespace-nowrap">
                                    Rp {{ number_format($p->dana_diajukan, 0, ',', '.') }}
                                </x-table.td>
                                <x-table.td align="center" class="whitespace-nowrap">
                                    <a href="{{ route('verifikasi.show', $p) }}" 
                                       class="inline-flex items-center justify-center gap-1.5 px-3.5 py-2 min-h-[36px] bg-[#0B1528] hover:bg-[#1E3A8A] text-white rounded-xl text-xs font-bold shadow-2xs transition active:scale-95 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2">
                                        <span>Telaah Proposal</span>
                                        <svg class="w-3.5 h-3.5 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </a>
                                </x-table.td>
                            </x-table.tr>
                        @empty
                            <x-table.empty colspan="6" message="Tidak ada proposal yang menunggu verifikasi BPM saat ini." />
                        @endforelse
                    </tbody>
                </x-table>
            </div>

            {{-- Widget Agenda Rapat & Koordinasi Parlemen --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.02)] p-6 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm sm:text-base">Agenda Rapat &amp; Koordinasi Parlemen</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Jadwal sidang pleno, dengar pendapat, dan koordinasi dengan ormawa kampus.</p>
                    </div>
                    <a href="{{ route('rapat.index') }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-700 hover:text-blue-900 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded">
                        <span>Kelola Agenda</span>
                        <span>&rarr;</span>
                    </a>
                </div>

                <x-table>
                    <x-table.thead>
                        <x-table.tr>
                            <x-table.th>Waktu Pelaksanaan</x-table.th>
                            <x-table.th>Judul Agenda Rapat</x-table.th>
                            <x-table.th>Lokasi / Ruang</x-table.th>
                            <x-table.th>Penyelenggara</x-table.th>
                            <x-table.th align="center">Tautan Meeting</x-table.th>
                        </x-table.tr>
                    </x-table.thead>
                    <tbody class="divide-y divide-slate-100 text-sm bg-white">
                        @forelse($rapats as $r)
                            <x-table.tr>
                                <x-table.td class="whitespace-nowrap">
                                    <div class="font-bold text-slate-800 text-xs">{{ \Carbon\Carbon::parse($r->tanggal_rapat)->isoFormat('D MMM Y') }}</div>
                                    <div class="text-[11px] text-slate-500 mt-0.5">{{ $r->jam_rapat ?? '' }} WIB</div>
                                </x-table.td>
                                <x-table.td>
                                    <div class="font-bold text-slate-900 text-sm">{{ $r->judul_rapat }}</div>
                                </x-table.td>
                                <x-table.td class="text-xs text-slate-600">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-medium">
                                        <svg class="w-3.5 h-3.5 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        <span>{{ $r->lokasi }}</span>
                                    </span>
                                </x-table.td>
                                <x-table.td class="text-xs font-semibold text-slate-700">
                                    {{ $r->penyelenggara->name ?? '-' }}
                                </x-table.td>
                                <x-table.td align="center" class="whitespace-nowrap">
                                    @if($r->link_meeting)
                                        <a href="{{ $r->link_meeting }}" target="_blank" 
                                           class="inline-flex items-center gap-1 px-3 py-1.5 min-h-[32px] bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-bold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                                            <span>Buka Tautan</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-500 italic">Offline</span>
                                    @endif
                                </x-table.td>
                            </x-table.tr>
                        @empty
                            <x-table.empty colspan="5" message="Belum ada agenda rapat atau sidang yang terdaftar." />
                        @endforelse
                    </tbody>
                </x-table>
            </div>

            <!-- Kalender Terpadu Peminjaman Tempat & Fasilitas -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 space-y-3">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between border-b border-slate-100 pb-3 mb-4">
                    <div>
                        <h3 class="font-bold text-slate-900 text-base">Jadwal Terpadu Fasilitas &amp; Barang Kampus</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Pemantauan okupansi ruangan dan sarana inventaris kampus untuk kelancaran kegiatan ormawa.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-4 text-xs mt-2 sm:mt-0">
                        <span class="inline-flex items-center gap-1.5 font-medium text-slate-600">
                            <span class="w-3 h-3 rounded-full bg-indigo-500 shadow-sm ring-2 ring-indigo-100"></span> 
                            Fasilitas
                        </span>
                        <span class="inline-flex items-center gap-1.5 font-medium text-slate-600">
                            <span class="w-3 h-3 rounded-full bg-amber-500 shadow-sm ring-2 ring-amber-100"></span> 
                            Barang
                        </span>
                    </div>
                </div>
                <x-calendar-style />
                <div class="skin-calendar-wrapper">
                    <div id="calendar"></div>
                </div>
            </div>

        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function(){
        const el = document.getElementById('calendar');
        if(!el) return;
        const cal = new FullCalendar.Calendar(el, {
            locale: 'id',
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            },
            events: [
                @foreach($calendarTempat as $c)
                {
                    title: 'Tempat: {{ $c->ruangan->nama_ruangan ?? $c->nama_kegiatan }}',
                    start: '{{ $c->tgl_mulai }}',
                    end: '{{ \Carbon\Carbon::parse($c->tgl_selesai)->addDay()->format('Y-m-d') }}',
                    color: '#4f46e5'
                },
                @endforeach
                @foreach($calendarBarang as $c)
                {
                    title: 'Barang: {{ $c->nama_kegiatan }}',
                    start: '{{ $c->tgl_mulai }}',
                    end: '{{ \Carbon\Carbon::parse($c->tgl_selesai)->addDay()->format('Y-m-d') }}',
                    color: '#f59e0b'
                },
                @endforeach
            ]
        });
        cal.render();
    });
    </script>
</x-app-layout>
