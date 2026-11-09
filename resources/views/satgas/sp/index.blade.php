<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2">
            <div>
                <h2 class="font-bold text-xl text-slate-900 leading-tight">
                    Surat Peringatan Satgas PPKS
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Daftar arsip dokumen Surat Peringatan (SP) untuk penegakan etika dan pencegahan kekerasan seksual di lingkungan kampus ITG</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('satgas.sp.create') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-xs font-semibold shadow-sm transition min-h-[40px] focus:outline-none focus-visible:ring-2 focus-visible:ring-purple-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Terbitkan SP Baru</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- KPI Ringkasan Surat Peringatan Satgas PPKS -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Surat Diajukan</p>
                        <h4 class="text-2xl font-extrabold text-slate-900 mt-1">{{ $counts['total'] }}</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Arsip usulan SP Satgas PPKS</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center font-bold text-lg flex-shrink-0 border border-purple-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Menunggu WR3</p>
                        <h4 class="text-2xl font-extrabold text-amber-600 mt-1">{{ $counts['proses'] }}</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Proses validasi &amp; TTD Digital</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center font-bold text-lg flex-shrink-0 border border-amber-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Disahkan &amp; Berlaku</p>
                        <h4 class="text-2xl font-extrabold text-emerald-700 mt-1">{{ $counts['disetujui'] }}</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Memiliki QR &amp; TTD sah WR3</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold text-lg flex-shrink-0 border border-emerald-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-slate-500 uppercase tracking-wider">Ditolak / Dikembalikan</p>
                        <h4 class="text-2xl font-extrabold text-rose-700 mt-1">{{ $counts['ditolak'] }}</h4>
                        <p class="text-[11px] text-slate-500 mt-0.5">Perlu perbaikan bukti/sanksi</p>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-lg flex-shrink-0 border border-rose-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </div>
                </div>
            </div>

            <!-- Tabel Daftar SP -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="p-5 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Daftar Surat Peringatan Terbitan Satgas PPKS</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Riwayat lengkap penindakan etika kemahasiswaan dan pencegahan kekerasan seksual</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead>
                            <tr class="bg-slate-50 text-slate-600 uppercase tracking-wider border-b border-slate-200 font-semibold">
                                <th class="p-4">Nomor &amp; Tanggal</th>
                                <th class="p-4">Penerima Sanksi</th>
                                <th class="p-4">Tingkat &amp; Perihal</th>
                                <th class="p-4 text-center">Status Validasi</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($suratPeringatans as $sp)
                                <tr class="hover:bg-slate-50/70 transition">
                                    <td class="p-4">
                                        <div class="font-bold text-slate-900 font-mono text-xs">{{ $sp->nomor_surat }}</div>
                                        <div class="text-[11px] text-slate-500 mt-0.5">
                                            {{ \Carbon\Carbon::parse($sp->tanggal_surat)->isoFormat('D MMMM Y') }}
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <div class="font-bold text-slate-900">{{ $sp->nama_penerima }}</div>
                                        <div class="text-[11px] text-slate-500 mt-0.5">{{ $sp->identitas_penerima }}</div>
                                    </td>
                                    <td class="p-4 max-w-xs">
                                        <div class="flex items-center gap-1.5 mb-1">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold 
                                                {{ $sp->tingkat === 'SP-1' ? 'bg-amber-100 text-amber-800' : ($sp->tingkat === 'SP-2' ? 'bg-orange-100 text-orange-800' : 'bg-rose-100 text-rose-800') }}">
                                                {{ $sp->tingkat }}
                                            </span>
                                        </div>
                                        <div class="font-medium text-slate-800 line-clamp-1" title="{{ $sp->perihal }}">{{ $sp->perihal }}</div>
                                        <div class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ $sp->alasan_singkat }}</div>
                                    </td>
                                    <td class="p-4 text-center">
                                        @if($sp->status === 'disetujui')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Disahkan WR3
                                            </span>
                                        @elseif($sp->status === 'menunggu_validasi')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Menunggu WR3
                                            </span>
                                        @elseif($sp->status === 'ditolak')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak WR3
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-slate-100 text-slate-700">
                                                {{ ucfirst($sp->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="p-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <a href="{{ route('satgas.sp.show', $sp) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-semibold transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                <span>Detail</span>
                                            </a>
                                            @if($sp->isDisetujui())
                                                <a href="{{ route('bkhm.sp.pdf', $sp) }}" target="_blank" class="inline-flex items-center gap-1 px-3 py-1.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    <span>PDF</span>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-400">
                                        Belum ada Surat Peringatan yang diterbitkan oleh Satgas PPKS.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($suratPeringatans->hasPages())
                    <div class="p-4 border-t border-slate-200">
                        {{ $suratPeringatans->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>

