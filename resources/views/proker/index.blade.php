<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h2 class="font-bold text-xl text-slate-900 leading-tight">
                    @hasrole('prodi')
                        {{ __('Monitoring Program Kerja Himpunan Mahasiswa') }}
                    @else
                        {{ __('Daftar Program Kerja Tahunan') }}
                    @endhasrole
                </h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                    @hasrole('prodi')
                        Pemantauan rencana kegiatan dan program kerja Himpunan Mahasiswa binaan Program Studi {{ auth()->user()->prodi ?? '' }}.
                    @else
                        Rencana program kerja organisasi mahasiswa dan monitoring pengawasan oleh Badan Perwakilan Mahasiswa (BPM).
                    @endhasrole
                </p>
            </div>
            @if(isset($prokers))
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-50 border border-blue-200/80 text-blue-700 text-xs font-bold tracking-wide self-start sm:self-auto shadow-xs">
                    <span class="w-2 h-2 rounded-full bg-blue-600"></span>
                    {{ $prokers->total() }} Program Kerja
                </span>
            @endif
        </div>
    </x-slot>

    <div class="py-8 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Toolbar: Tombol Tambah Proker --}}
            @hasanyrole('ormawa|bem')
                <div class="bg-white border border-slate-200/80 rounded-2xl p-4 sm:p-5 shadow-[0_4px_20px_rgba(0,0,0,0.02)] flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm sm:text-base">Pengajuan &amp; Realisasi Proker</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Daftarkan rencana kegiatan kepengurusan ormawa Anda untuk periode aktif saat ini.</p>
                    </div>
                    <a href="{{ route('proker.create') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-[#0B1528] hover:bg-[#1E3A8A] text-white font-bold text-xs sm:text-sm rounded-xl shadow-xs hover:shadow-md transition active:scale-95 shrink-0">
                        <svg class="w-4 h-4 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Tambah Proker Baru</span>
                    </a>
                </div>
            @endhasanyrole

            {{-- Main Table Card --}}
            {{-- Main Table Card --}}
            <div class="bg-white border border-slate-200/80 rounded-2xl shadow-[0_4px_20px_rgba(0,0,0,0.03)] p-6 space-y-4">
                <x-table>
                    <x-table.thead>
                        <x-table.tr>
                            <x-table.th>Ormawa Pengusul</x-table.th>
                            <x-table.th>Nama Program Kerja</x-table.th>
                            <x-table.th>Target Pelaksanaan</x-table.th>
                            <x-table.th align="center">Status Proker</x-table.th>
                            <x-table.th align="center">Persetujuan Prodi (ACC)</x-table.th>
                            <x-table.th>Catatan Evaluasi</x-table.th>
                            @hasrole('prodi')
                                <x-table.th align="center">Tindakan ACC Prodi</x-table.th>
                            @endhasrole
                            @hasanyrole('bpm|admin')
                                <x-table.th align="center">Tindakan BPM</x-table.th>
                            @endhasanyrole
                        </x-table.tr>
                    </x-table.thead>
                    <tbody class="divide-y divide-slate-100 bg-white text-sm">
                        @forelse ($prokers as $proker)
                            <x-table.tr>
                                {{-- Kolom Ormawa --}}
                                <x-table.td>
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-blue-50 text-[#1E40AF] border border-blue-200/60 flex items-center justify-center font-bold text-xs shrink-0 select-none">
                                            {{ strtoupper(substr($proker->user->name ?? 'O', 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 text-sm truncate max-w-[160px]" title="{{ $proker->user->name }}">
                                                {{ $proker->user->name }}
                                            </div>
                                            @if($proker->user?->prodi)
                                                <span class="text-[10px] text-slate-400 block">{{ $proker->user->prodi }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </x-table.td>

                                {{-- Kolom Nama Proker --}}
                                <x-table.td>
                                    <div class="font-bold text-slate-900 hover:text-blue-700 transition">
                                        {{ $proker->nama_proker }}
                                    </div>
                                    <div class="text-xs text-slate-500 mt-0.5 line-clamp-1">
                                        {{ $proker->deskripsi }}
                                    </div>
                                    <div class="mt-1.5 flex flex-wrap gap-1.5">
                                        @if(($proker->pengajuans_count ?? 0) > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <svg class="w-3 h-3 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                <span>{{ $proker->pengajuans_count }} Proposal Diajukan</span>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] text-slate-500 bg-slate-50 border border-slate-200/70">
                                                Belum ada proposal
                                            </span>
                                        @endif
                                    </div>
                                </x-table.td>

                                {{-- Kolom Target Pelaksanaan --}}
                                <x-table.td class="text-xs whitespace-nowrap">
                                    <div class="font-bold text-slate-800">
                                        {{ \Carbon\Carbon::parse($proker->rencana_pelaksanaan)->isoFormat('D MMMM Y') }}
                                    </div>
                                    <div class="text-[10px] text-slate-500 mt-0.5">Rencana Kegiatan</div>
                                </x-table.td>

                                {{-- Kolom Status Proker --}}
                                <x-table.td align="center" class="whitespace-nowrap">
                                    @php
                                        $chipClass = 'bg-slate-50 text-slate-700 border-slate-200';
                                        $dotClass = 'bg-slate-400';
                                        $labelStatus = 'Rencana';

                                        if ($proker->status == 'rencana') {
                                            $chipClass = 'bg-slate-50 text-slate-700 border-slate-200';
                                            $dotClass = 'bg-slate-400';
                                            $labelStatus = 'Rencana';
                                        } elseif ($proker->status == 'proses') {
                                            $chipClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                            $dotClass = 'bg-blue-600 animate-pulse';
                                            $labelStatus = 'Dalam Proses';
                                        } elseif ($proker->status == 'terlaksana') {
                                            $chipClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                                            $dotClass = 'bg-emerald-500';
                                            $labelStatus = 'Terlaksana';
                                        } else {
                                            $chipClass = 'bg-rose-50 text-rose-700 border-rose-200';
                                            $dotClass = 'bg-rose-500';
                                            $labelStatus = 'Kendala';
                                        }
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border shadow-2xs {{ $chipClass }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></span>
                                        {{ $labelStatus }}
                                    </span>
                                </x-table.td>

                                {{-- Kolom Persetujuan Prodi (ACC) --}}
                                <x-table.td align="center" class="whitespace-nowrap">
                                    @if($proker->isHimaProker())
                                        @if($proker->status_prodi === 'disetujui')
                                            <div class="inline-flex flex-col items-center">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                                                    <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                    <span>Di-ACC Prodi</span>
                                                </span>
                                                @if($proker->disetujui_prodi_at)
                                                    <span class="text-[10px] text-slate-400 mt-0.5">{{ $proker->disetujui_prodi_at->format('d/m/Y H:i') }}</span>
                                                @endif
                                            </div>
                                        @elseif($proker->status_prodi === 'ditolak')
                                            <div class="inline-flex flex-col items-center">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200 shadow-2xs">
                                                    <svg class="w-3.5 h-3.5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                    <span>Revisi / Ditolak</span>
                                                </span>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 shadow-2xs animate-pulse">
                                                <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                <span>Menunggu ACC</span>
                                            </span>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-medium bg-slate-100 text-slate-500 border border-slate-200">
                                            Non-HIMA
                                        </span>
                                    @endif
                                </x-table.td>

                                {{-- Kolom Catatan Evaluasi --}}
                                <x-table.td class="text-xs text-slate-600 max-w-xs space-y-1.5">
                                    @if($proker->catatan_prodi)
                                        <div class="bg-blue-50 text-blue-900 border border-blue-200/80 px-2.5 py-1.5 rounded-lg">
                                            <span class="font-bold text-[10px] text-blue-700 block uppercase tracking-wide">Arahan / Catatan Prodi:</span>
                                            <span class="italic text-[11px]">{{ $proker->catatan_prodi }}</span>
                                        </div>
                                    @endif
                                    @if($proker->catatan_bpm)
                                        <div class="bg-amber-50 text-amber-900 border border-amber-200/80 px-2.5 py-1.5 rounded-lg">
                                            <span class="font-bold text-[10px] text-amber-700 block uppercase tracking-wide">Catatan Evaluasi BPM:</span>
                                            <span class="italic text-[11px]">{{ $proker->catatan_bpm }}</span>
                                        </div>
                                    @endif
                                    @if(!$proker->catatan_prodi && !$proker->catatan_bpm)
                                        <span class="text-slate-400 italic">Belum ada catatan</span>
                                    @endif
                                </x-table.td>

                                {{-- Kolom Aksi ACC Prodi --}}
                                @hasrole('prodi')
                                    <x-table.td align="center" class="whitespace-nowrap">
                                        <div class="relative inline-block text-left" x-data="{ openNote: false }">
                                            <div class="inline-flex items-center gap-1.5">
                                                @if($proker->status_prodi !== 'disetujui')
                                                    <form action="{{ route('proker.acc-prodi', $proker) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin MENYETUJUI (ACC) program kerja ini?');">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status_prodi" value="disetujui">
                                                        <button type="submit" 
                                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition active:scale-95" 
                                                                title="Setujui (ACC) Program Kerja">
                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                            <span>ACC Proker</span>
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 rounded-lg border border-emerald-200">
                                                        ✓ Sudah Di-ACC
                                                    </span>
                                                @endif

                                                <button type="button" @click="openNote = !openNote" 
                                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl transition shadow-2xs" 
                                                        title="Beri Catatan / Revisi">
                                                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                    <span x-text="openNote ? 'Tutup' : 'Catatan'"></span>
                                                </button>
                                            </div>

                                            {{-- Dropdown Form Catatan / Revisi --}}
                                            <div x-show="openNote" x-cloak @click.away="openNote = false" 
                                                 class="absolute z-30 right-0 mt-2 w-80 p-3.5 bg-white border border-slate-200 rounded-2xl shadow-xl text-left">
                                                <form action="{{ route('proker.acc-prodi', $proker) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="flex items-center justify-between mb-2">
                                                        <span class="text-xs font-bold text-slate-800">Form Tindakan Prodi</span>
                                                        <span class="text-[10px] text-slate-500">{{ $proker->nama_proker }}</span>
                                                    </div>
                                                    <textarea name="catatan_prodi" rows="3" placeholder="Tuliskan catatan arahan, rekomendasi jadwal, atau alasan revisi..." 
                                                              class="block w-full text-xs border-slate-300 rounded-xl shadow-2xs mb-2.5 focus:border-indigo-500 focus:ring-indigo-500">{{ $proker->catatan_prodi }}</textarea>
                                                    
                                                    <div class="grid grid-cols-2 gap-2">
                                                        <button type="submit" name="status_prodi" value="ditolak" 
                                                                class="w-full py-1.5 px-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl transition text-center shadow-xs">
                                                            Minta Revisi
                                                        </button>
                                                        <button type="submit" name="status_prodi" value="disetujui" 
                                                                class="w-full py-1.5 px-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition text-center shadow-xs">
                                                            ACC + Catatan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </x-table.td>
                                @endhasrole

                                {{-- Kolom Aksi BPM --}}
                                @hasanyrole('bpm|admin')
                                    <x-table.td align="center" class="whitespace-nowrap">
                                        <form action="{{ route('proker.update', $proker) }}" method="POST" class="inline-flex items-center gap-1.5">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" class="text-xs bg-slate-50 border-slate-200 rounded-xl py-1.5 px-2.5 focus:ring-blue-100 focus:border-blue-500 shadow-2xs">
                                                <option value="rencana" {{ $proker->status == 'rencana' ? 'selected' : '' }}>Rencana</option>
                                                <option value="proses" {{ $proker->status == 'proses' ? 'selected' : '' }}>Proses</option>
                                                <option value="terlaksana" {{ $proker->status == 'terlaksana' ? 'selected' : '' }}>Terlaksana</option>
                                                <option value="kendala" {{ $proker->status == 'kendala' ? 'selected' : '' }}>Kendala</option>
                                            </select>
                                            <input type="text" name="catatan_bpm" placeholder="Catatan BPM..." value="{{ $proker->catatan_bpm }}" 
                                                   class="text-xs bg-slate-50 border-slate-200 rounded-xl w-32 py-1.5 px-2.5 focus:bg-white focus:ring-blue-100 focus:border-blue-500 shadow-2xs">
                                            <button type="submit" 
                                                    class="inline-flex items-center px-3 py-1.5 bg-[#0B1528] hover:bg-[#1E3A8A] text-white text-xs font-bold rounded-xl shadow-xs transition active:scale-95">
                                                Simpan
                                            </button>
                                        </form>
                                    </x-table.td>
                                @endhasanyrole
                            </x-table.tr>
                        @empty
                            <x-table.empty :colspan="auth()->user()->hasAnyRole(['bpm', 'admin', 'prodi']) ? 7 : 6" message="Belum ada program kerja terdaftar untuk periode aktif ini." />
                        @endforelse
                    </tbody>
                </x-table>

                {{-- Pagination Footer --}}
                @if($prokers->hasPages())
                    <div class="pt-2">
                        {{ $prokers->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
