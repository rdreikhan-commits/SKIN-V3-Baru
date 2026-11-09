<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Dokumen Proposal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            

            <div class="flex flex-wrap items-center gap-3 mb-6">
                <a href="{{ route('generator.index') }}" class="text-indigo-600 hover:underline">&larr; Kembali ke daftar</a>
                <div class="ml-auto flex items-center gap-2">
                    <a href="{{ route('pengajuan.create', ['proposal_id' => $proposal->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow text-sm flex items-center gap-1.5">
                        <span>🚀 Ajukan Dana Resmi</span>
                    </a>
                    <a href="{{ route('generator.pdf', $proposal) }}" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded shadow text-sm">
                        ⬇️ Unduh PDF
                    </a>
                    <a href="{{ route('generator.print', $proposal) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded shadow text-sm">
                        🖨️ Cetak
                    </a>
                </div>
            </div>

            <!-- Preview Data (Not full layout) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8">
                    @php $badge = $proposal->jenis_proposal_badge; @endphp
                    <div class="text-center mb-8 border-b pb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs {{ $badge['class'] }} mb-2">
                            <span>{{ $badge['icon'] }}</span>
                            <span>{{ $badge['label'] }}</span>
                        </span>
                        <h1 class="text-2xl font-bold uppercase">{{ $proposal->judul_dokumen }}</h1>
                        <h2 class="text-xl font-bold text-slate-800">{{ $proposal->nama_kegiatan }}</h2>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <h3 class="font-bold">A. Latar Belakang</h3>
                            <p class="text-justify whitespace-pre-wrap">{{ $proposal->latar_belakang }}</p>
                        </div>
                        
                        <div>
                            <h3 class="font-bold">B. Tujuan Kegiatan</h3>
                            <p class="text-justify whitespace-pre-wrap">{{ $proposal->tujuan }}</p>
                        </div>
                        
                        <div>
                            <h3 class="font-bold">C. Sasaran Peserta</h3>
                            <p class="text-justify whitespace-pre-wrap">{{ $proposal->sasaran }}</p>
                        </div>

                        @if($proposal->indikator || $proposal->luaran || $proposal->dampak)
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <h3 class="font-bold">Indikator Keberhasilan</h3>
                                <p class="text-justify whitespace-pre-wrap">{{ $proposal->indikator ?: '-' }}</p>
                            </div>
                            <div>
                                <h3 class="font-bold">Luaran (Output)</h3>
                                <p class="text-justify whitespace-pre-wrap">{{ $proposal->luaran ?: '-' }}</p>
                            </div>
                            <div>
                                <h3 class="font-bold">Dampak (Outcome)</h3>
                                <p class="text-justify whitespace-pre-wrap">{{ $proposal->dampak ?: '-' }}</p>
                            </div>
                        </div>
                        @endif

                        <div>
                            <h3 class="font-bold">D. Susunan Kepanitiaan</h3>
                            <ul class="list-disc pl-5 mt-2">
                                @foreach($proposal->panitia as $p)
                                    <li><strong>{{ $p->jabatan }}</strong>: {{ $p->nama_mahasiswa }} {{ $p->nim ? '('.$p->nim.')' : '' }}</li>
                                @endforeach
                            </ul>
                        </div>

                        <div>
                            <h3 class="font-bold mb-2">E. Rencana Anggaran Biaya (RAB)</h3>
                            <table class="w-full border-collapse border border-gray-300">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="border border-gray-300 px-2 py-1 text-left">No</th>
                                        <th class="border border-gray-300 px-2 py-1 text-left">Uraian Kebutuhan</th>
                                        <th class="border border-gray-300 px-2 py-1 text-center">Vol</th>
                                        <th class="border border-gray-300 px-2 py-1 text-center">Sat</th>
                                        <th class="border border-gray-300 px-2 py-1 text-right">Harga Satuan</th>
                                        <th class="border border-gray-300 px-2 py-1 text-right">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = 0; @endphp
                                    @foreach($proposal->rab as $i => $r)
                                        @php $total += $r->total_harga; @endphp
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 text-center">{{ $i+1 }}</td>
                                            <td class="border border-gray-300 px-2 py-1">{{ $r->rincian }}</td>
                                            <td class="border border-gray-300 px-2 py-1 text-center">{{ $r->volume }}</td>
                                            <td class="border border-gray-300 px-2 py-1 text-center">{{ $r->satuan }}</td>
                                            <td class="border border-gray-300 px-2 py-1 text-right">Rp {{ number_format($r->harga_satuan, 0, ',', '.') }}</td>
                                            <td class="border border-gray-300 px-2 py-1 text-right font-semibold">Rp {{ number_format($r->total_harga, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-50 font-bold">
                                        <td colspan="5" class="border border-gray-300 px-2 py-2 text-center">TOTAL ANGGARAN</td>
                                        <td class="border border-gray-300 px-2 py-2 text-right">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div>
                            <h3 class="font-bold">F. Penutup</h3>
                            <p class="text-justify whitespace-pre-wrap">{{ $proposal->penutup }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>