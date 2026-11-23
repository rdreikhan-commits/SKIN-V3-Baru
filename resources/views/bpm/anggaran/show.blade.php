<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div class="flex items-center gap-3">
                <a href="{{ route('bpm.anggaran.index') }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 transition" title="Kembali ke Monitoring Anggaran">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h2 class="font-bold text-xl text-slate-900 leading-tight">
                        Detail Anggaran: {{ $user->name }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Laporan transparansi pagu, realisasi dana kegiatan, dan histori mutasi saldo ormawa.
                    </p>
                </div>
            </div>
            <a href="{{ route('bpm.anggaran.index') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold shadow-2xs transition">
                <span>&larr; Kembali ke Daftar</span>
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Profil Organisasi Mahasiswa -->
            <div class="bg-white p-6 rounded-2xl shadow-2xs border border-slate-200">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 border border-slate-200 flex items-center justify-center overflow-hidden flex-shrink-0">
                            @if(!empty($user->logo_ormawa))
                                <img src="{{ asset('storage/' . $user->logo_ormawa) }}" alt="{{ $user->name }}" class="w-full h-full object-contain p-1">
                            @elseif(!empty($user->foto_profil))
                                <img src="{{ asset('storage/' . $user->foto_profil) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                <span class="font-black text-xl text-slate-400">{{ substr($user->name, 0, 2) }}</span>
                            @endif
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h3 class="text-lg font-bold text-slate-900">{{ $user->name }}</h3>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-bold uppercase
                                    {{ $tipeLabel === 'HIMA' ? 'bg-indigo-100 text-indigo-700 border border-indigo-200' :
                                       ($tipeLabel === 'UKM' ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' :
                                       ($tipeLabel === 'BEM' ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-purple-100 text-purple-700 border border-purple-200')) }}">
                                    {{ $tipeLabel }}
                                </span>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-slate-500 mt-1">
                                @if(!empty($user->prodi))
                                    <div>Program Studi: <strong class="text-slate-700">{{ $user->prodi }}</strong></div>
                                @endif
                                <div>Ketua: <strong class="text-slate-700">{{ $user->nama_ketua ?: '-' }}</strong> (NIM: {{ $user->nim_ketua ?: '-' }})</div>
                                <div>Kontak: <strong class="text-slate-700">{{ $user->kontak_ketua ?? $user->email }}</strong></div>
                                @if(!empty($user->periode_kepengurusan))
                                    <div>Periode: <strong class="text-slate-700">{{ $user->periode_kepengurusan }}</strong></div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 text-xs font-semibold">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Status Ormawa Aktif
                        </span>
                    </div>
                </div>
            </div>

            <!-- 4 Kartu Metrik Anggaran Organisasi Ini -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-slate-200">
                    <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Pagu Anggaran Awal</span>
                    <div class="text-2xl font-black text-slate-900 mt-2 font-mono">Rp {{ number_format($saldoAwal, 0, ',', '.') }}</div>
                    <div class="text-[11px] text-slate-400 mt-1">Alokasi anggaran ITG</div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-amber-200/80 bg-gradient-to-br from-white to-amber-50/20">
                    <span class="text-xs font-semibold text-amber-800 uppercase tracking-wider">Total Dana Terpakai</span>
                    <div class="text-2xl font-black text-amber-700 mt-2 font-mono">Rp {{ number_format($terpakai, 0, ',', '.') }}</div>
                    <div class="text-[11px] text-amber-600 font-medium mt-1">Proposal disetujui / dicairkan</div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-emerald-200/80 bg-gradient-to-br from-white to-emerald-50/20">
                    <span class="text-xs font-semibold text-emerald-800 uppercase tracking-wider">Sisa Saldo Kas Tersedia</span>
                    <div class="text-2xl font-black text-emerald-700 mt-2 font-mono">Rp {{ number_format($saldoSisa, 0, ',', '.') }}</div>
                    <div class="text-[11px] text-emerald-600 font-medium mt-1">Dapat diajukan untuk kegiatan</div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-2xs border border-indigo-200/80 bg-gradient-to-br from-white to-indigo-50/20">
                    <span class="text-xs font-semibold text-indigo-800 uppercase tracking-wider">Persentase Serapan Dana</span>
                    <div class="text-2xl font-black text-indigo-700 mt-2 font-mono">{{ $persen }}%</div>
                    <div class="mt-2 w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                        <div class="h-1.5 rounded-full {{ $persen > 85 ? 'bg-rose-500' : ($persen > 50 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                             style="width: {{ min(100, $persen) }}%;"></div>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar Proposal & Riwayat Pencairan Dana -->
            <div class="bg-white rounded-2xl shadow-2xs border border-slate-200 overflow-hidden">
                <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm sm:text-base">Daftar Pengajuan Proposal &amp; Pencairan Dana</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Rincian proposal yang diajukan oleh {{ $user->name }} dan status dana.</p>
                    </div>
                    <span class="text-xs font-semibold text-slate-500">{{ $pengajuans->total() }} Total Pengajuan</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-xs text-left">
                        <thead class="bg-slate-50 font-bold text-slate-600 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3.5 text-center w-12">No</th>
                                <th class="px-4 py-3.5">Nama Kegiatan / Proposal</th>
                                <th class="px-4 py-3.5">Jenis</th>
                                <th class="px-4 py-3.5">Tgl Pengajuan</th>
                                <th class="px-4 py-3.5">Dana Diajukan</th>
                                <th class="px-4 py-3.5">Dana Dicairkan</th>
                                <th class="px-4 py-3.5 text-center">Status Alur</th>
                                <th class="px-4 py-3.5 text-center">Status LPJ</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($pengajuans as $idx => $p)
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 py-3.5 text-center font-bold text-slate-400">
                                        {{ $pengajuans->firstItem() + $idx }}
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <div class="font-bold text-slate-900 text-sm">{{ $p->nama_kegiatan }}</div>
                                        @if(!empty($p->nomor_surat))
                                            <div class="text-[11px] text-slate-400 font-mono mt-0.5">{{ $p->nomor_surat }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                            {{ $p->isKegiatan() ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' :
                                               ($p->isPengadaanBarang() ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-amber-50 text-amber-700 border border-amber-200') }}">
                                            {{ $p->jenis_pengajuan_label }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-slate-600 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($p->tanggal_pengajuan)->translatedFormat('d M Y') }}
                                    </td>
                                    <td class="px-4 py-3.5 font-mono text-slate-700 font-semibold whitespace-nowrap">
                                        Rp {{ number_format($p->dana_diajukan ?? 0, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3.5 font-mono font-bold text-emerald-600 whitespace-nowrap">
                                        @if($p->dana && $p->dana->nominal_cair > 0)
                                            Rp {{ number_format($p->dana->nominal_cair, 0, ',', '.') }}
                                        @else
                                            <span class="text-slate-400 font-normal">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold
                                            {{ $p->state?->name === 'COMPLETED' ? 'bg-emerald-100 text-emerald-800' :
                                               ($p->state?->name === 'REJECTED' ? 'bg-rose-100 text-rose-800' : 'bg-blue-100 text-blue-800') }}">
                                            {{ $p->state?->label ?? $p->state?->name ?? 'Proses' }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3.5 text-center whitespace-nowrap">
                                        @if(!empty($p->file_lpj))
                                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                                ✓ LPJ Diupload
                                            </span>
                                        @else
                                            <span class="text-[11px] text-slate-400">Belum LPJ</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-slate-400">
                                        Organisasi ini belum memiliki riwayat pengajuan proposal dana.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($pengajuans->hasPages())
                    <div class="p-4 border-t border-slate-100 bg-slate-50">
                        {{ $pengajuans->links() }}
                    </div>
                @endif
            </div>

            <!-- Tabel Log Audit Mutasi Saldo Kas Ormawa -->
            <div class="bg-white rounded-2xl shadow-2xs border border-slate-200 overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900 text-sm sm:text-base">Catatan Audit Mutasi Saldo Kas (Saldo Histori)</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Riwayat penyesuaian, pencairan dana, atau pengembalian sisa kas ke rekening ormawa.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-xs text-left">
                        <thead class="bg-slate-50 font-bold text-slate-600 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 text-center w-12">No</th>
                                <th class="px-4 py-3">Waktu Transaksi</th>
                                <th class="px-4 py-3">Tipe Mutasi</th>
                                <th class="px-4 py-3">Aktor / Eksekutor</th>
                                <th class="px-4 py-3">Saldo Sebelum</th>
                                <th class="px-4 py-3">Selisih</th>
                                <th class="px-4 py-3">Saldo Sesudah</th>
                                <th class="px-4 py-3">Catatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse($saldoHistori as $idx => $histori)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="px-4 py-3 text-center font-bold text-slate-400">{{ $idx + 1 }}</td>
                                    <td class="px-4 py-3 text-slate-600 whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($histori->created_at)->translatedFormat('d M Y, H:i') }}
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase
                                            {{ $histori->tipe === 'tambah' ? 'bg-emerald-100 text-emerald-800' :
                                               ($histori->tipe === 'kurang' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700') }}">
                                            {{ $histori->tipe }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-slate-700 font-medium whitespace-nowrap">
                                        {{ $histori->actor?->name ?? 'Sistem ITG' }}
                                    </td>
                                    <td class="px-4 py-3 font-mono text-slate-600 whitespace-nowrap">
                                        Rp {{ number_format($histori->nominal_sebelum, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 font-mono font-bold whitespace-nowrap {{ $histori->selisih >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                                        {{ $histori->selisih >= 0 ? '+' : '' }}Rp {{ number_format($histori->selisih, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 font-mono font-extrabold text-slate-900 whitespace-nowrap">
                                        Rp {{ number_format($histori->nominal_sesudah, 0, ',', '.') }}
                                    </td>
                                    <td class="px-4 py-3 text-slate-600">
                                        {{ $histori->catatan ?: '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-6 text-center text-slate-400">
                                        Belum ada riwayat mutasi saldo kas untuk organisasi ini.
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
