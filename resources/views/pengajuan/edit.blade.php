<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::user()->isHima() ? __('Edit / Revisi Pengajuan Dana Non-Akademik') : __('Edit / Revisi Pengajuan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 max-w-2xl mx-auto">
                    
                    @if($pengajuan->state->name === 'rejected')
                        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-md">
                            <h3 class="font-bold text-red-700">Catatan Revisi/Penolakan:</h3>
                            <p class="text-red-600 mt-1">
                                @php
                                    $rejectHistory = $pengajuan->histori()->whereHas('state', function($q) {
                                        $q->where('name', 'rejected');
                                    })->latest()->first();
                                @endphp
                                {{ $rejectHistory ? $rejectHistory->catatan : 'Silakan perbaiki proposal Anda dan upload ulang.' }}
                            </p>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('pengajuan.update', $pengajuan) }}" enctype="multipart/form-data"
                          x-data="{ 
                            jenisPengajuan: '{{ old('jenis_pengajuan', $pengajuan->jenis_pengajuan ?? 'kegiatan') }}',
                            perluSatgasPpks: {{ old('perlu_satgas_ppks', $pengajuan->perlu_satgas_ppks) ? 'true' : 'false' }}
                          }">
                        @csrf
                        @method('PUT')

                        <!-- Pilihan Jenis Pengajuan -->
                        <div class="mb-5">
                            <x-input-label :value="__('Kategori / Jenis Pengajuan')" class="mb-2" />
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <label class="relative flex flex-col p-3 rounded-xl border-2 cursor-pointer transition select-none"
                                       :class="jenisPengajuan === 'kegiatan' ? 'border-indigo-600 bg-indigo-50/70 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'">
                                    <input type="radio" name="jenis_pengajuan" value="kegiatan" x-model="jenisPengajuan" class="sr-only">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xl">🎪</span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-indigo-100 text-indigo-800">Kegiatan</span>
                                    </div>
                                    <div class="font-bold text-xs text-slate-900">Kegiatan Mahasiswa</div>
                                    <p class="text-[10px] text-slate-500 mt-0.5">Melalui Satgas PPKS</p>
                                </label>

                                <label class="relative flex flex-col p-3 rounded-xl border-2 cursor-pointer transition select-none"
                                       :class="jenisPengajuan === 'pengadaan_barang' ? 'border-emerald-600 bg-emerald-50/70 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'">
                                    <input type="radio" name="jenis_pengajuan" value="pengadaan_barang" x-model="jenisPengajuan" class="sr-only">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xl">📦</span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">Inventaris</span>
                                    </div>
                                    <div class="font-bold text-xs text-slate-900">Pengadaan Barang</div>
                                    <p class="text-[10px] text-emerald-700 font-semibold mt-0.5">Bypass Satgas PPKS</p>
                                </label>

                                <label class="relative flex flex-col p-3 rounded-xl border-2 cursor-pointer transition select-none"
                                       :class="jenisPengajuan === 'pendanaan_lomba' ? 'border-amber-600 bg-amber-50/70 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'">
                                    <input type="radio" name="jenis_pengajuan" value="pendanaan_lomba" x-model="jenisPengajuan" class="sr-only">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xl">🏆</span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-amber-100 text-amber-800">Delegasi</span>
                                    </div>
                                    <div class="font-bold text-xs text-slate-900">Pendanaan Lomba</div>
                                    <p class="text-[10px] text-emerald-700 font-semibold mt-0.5">Bypass Satgas PPKS</p>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('jenis_pengajuan')" class="mt-2" />
                        </div>

                        <!-- Nama Kegiatan -->
                        <div class="mb-4">
                            <label class="block font-medium text-sm text-gray-700 mb-1">
                                <span x-show="jenisPengajuan === 'kegiatan'">Nama Kegiatan</span>
                                <span x-show="jenisPengajuan === 'pengadaan_barang'" style="display: none;" x-cloak>Nama Pengadaan / Kebutuhan Inventaris</span>
                                <span x-show="jenisPengajuan === 'pendanaan_lomba'" style="display: none;" x-cloak>Nama Kompetisi &amp; Delegasi Lomba</span>
                                <span class="text-rose-500">*</span>
                            </label>
                            <x-text-input id="nama_kegiatan" class="block mt-1 w-full" type="text" name="nama_kegiatan" :value="old('nama_kegiatan', $pengajuan->nama_kegiatan)" required />
                            <x-input-error :messages="$errors->get('nama_kegiatan')" class="mt-2" />
                        </div>

                        <!-- Program Kerja Terkait -->
                        @if(!Auth::user()->isHima() || \App\Models\HimaFiturSetting::isAktif('proker'))
                        <div class="mb-4">
                            <x-input-label for="program_kerja_id" :value="__('Program Kerja Terkait (Opsional)')" />
                            <select id="program_kerja_id" name="program_kerja_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm">
                                <option value="">-- Tidak Terkait / Di Luar Program Kerja Tahunan --</option>
                                @foreach($prokers ?? [] as $proker)
                                    <option value="{{ $proker->id }}" {{ (string)old('program_kerja_id', $pengajuan->program_kerja_id) === (string)$proker->id ? 'selected' : '' }}>
                                        {{ $proker->nama_proker }} (Rencana: {{ \Carbon\Carbon::parse($proker->rencana_pelaksanaan)->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @if(count($prokers ?? []) === 0)
                                <p class="text-xs text-indigo-600 mt-1">💡 Belum memiliki program kerja tahunan? Daftarkan proker Anda di <a href="{{ route('proker.create') }}" class="underline font-semibold" target="_blank">Menu Program Kerja</a>.</p>
                            @else
                                <p class="text-xs text-gray-500 mt-1">Pilih proker tahunan ormawa Anda untuk memudahkan monitoring realisasi oleh BPM dan BKHM.</p>
                            @endif
                            <x-input-error :messages="$errors->get('program_kerja_id')" class="mt-2" />
                        </div>
                        @endif

                        <!-- Tanggal Pelaksanaan Kegiatan -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <x-input-label for="tanggal_mulai_kegiatan" :value="__('Tanggal Mulai Kegiatan (Opsional)')" />
                                <x-text-input id="tanggal_mulai_kegiatan" class="block mt-1 w-full" type="date" name="tanggal_mulai_kegiatan" :value="old('tanggal_mulai_kegiatan', $pengajuan->tanggal_mulai_kegiatan ? \Carbon\Carbon::parse($pengajuan->tanggal_mulai_kegiatan)->format('Y-m-d') : '')" onchange="document.getElementById('tanggal_selesai_kegiatan').min = this.value" />
                                <x-input-error :messages="$errors->get('tanggal_mulai_kegiatan')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="tanggal_selesai_kegiatan" :value="__('Tanggal Selesai Kegiatan (Opsional)')" />
                                <x-text-input id="tanggal_selesai_kegiatan" class="block mt-1 w-full" type="date" name="tanggal_selesai_kegiatan" :value="old('tanggal_selesai_kegiatan', $pengajuan->tanggal_selesai_kegiatan ? \Carbon\Carbon::parse($pengajuan->tanggal_selesai_kegiatan)->format('Y-m-d') : '')" :min="old('tanggal_mulai_kegiatan', $pengajuan->tanggal_mulai_kegiatan ? \Carbon\Carbon::parse($pengajuan->tanggal_mulai_kegiatan)->format('Y-m-d') : '')" />
                                <x-input-error :messages="$errors->get('tanggal_selesai_kegiatan')" class="mt-2" />
                            </div>
                            <div class="col-span-1 md:col-span-2 -mt-2">
                                <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded p-2">
                                    💡 <strong>Urgensi Pencairan:</strong> Mengisi tanggal pelaksanaan kegiatan akan menampilkan indikator hitung mundur (H-X) pada antrean verifikator sehingga proposal dapat diprioritaskan sebelum acara berlangsung.
                                </p>
                            </div>
                        </div>

                        <!-- Dana Diajukan -->
                        <div class="mb-4">
                            <x-input-label for="dana_diajukan" :value="__('Dana Diajukan (Rp)')" />
                            <x-text-input id="dana_diajukan" class="block mt-1 w-full [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" type="number" name="dana_diajukan" :value="old('dana_diajukan', $pengajuan->dana_diajukan)" required min="0" onwheel="this.blur()" oninput="document.getElementById('dana_diajukan_preview').innerText = this.value ? 'Terbilang: Rp ' + new Intl.NumberFormat('id-ID').format(this.value) : ''" />
                            <p id="dana_diajukan_preview" class="text-xs text-indigo-600 mt-1 font-semibold">
                                Terbilang: Rp {{ number_format(old('dana_diajukan', $pengajuan->dana_diajukan), 0, ',', '.') }}
                            </p>
                            <x-input-error :messages="$errors->get('dana_diajukan')" class="mt-2" />
                        </div>

                        <!-- Tanggal Pengajuan -->
                        <div class="mb-4">
                            <x-input-label for="tanggal_pengajuan" :value="__('Tanggal Pengajuan Proposal')" />
                            <x-text-input id="tanggal_pengajuan" class="block mt-1 w-full" type="date" name="tanggal_pengajuan" :value="old('tanggal_pengajuan', \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('tanggal_pengajuan')" class="mt-2" />
                        </div>

                        <!-- File Proposal -->
                        <div class="mb-6">
                            <x-input-label for="file_proposal" :value="__('File Proposal Baru (PDF, maks 5MB)')" />
                            <p class="text-sm text-gray-500 mb-1">Kosongkan jika tidak ingin mengganti file proposal yang sudah ada.</p>
                            <input id="file_proposal" class="block mt-1 w-full border border-gray-300 rounded p-2" type="file" name="file_proposal" accept=".pdf" />
                            <x-input-error :messages="$errors->get('file_proposal')" class="mt-2" />
                            
                            <div class="mt-2 text-sm">
                                File saat ini: <a href="{{ route('dokumen.proposal', $pengajuan) }}" target="_blank" class="text-indigo-600 hover:underline">Lihat PDF</a>
                            </div>
                        </div>

                        <!-- Bagian Pengawasan & Kepatuhan Satgas PPKS (Khusus Kegiatan) -->
                        <div x-show="jenisPengajuan === 'kegiatan'" x-transition class="mb-6 space-y-4">
                            <!-- Opsi Checkbox Izin Satgas PPKS -->
                            <div class="p-4 bg-white border-2 rounded-xl transition cursor-pointer select-none"
                                 :class="perluSatgasPpks ? 'border-rose-500 bg-rose-50/40 shadow-xs' : 'border-slate-200 bg-slate-50/50'">
                                <label class="flex items-start gap-3 cursor-pointer">
                                    <input type="checkbox" 
                                           name="perlu_satgas_ppks" 
                                           value="1" 
                                           x-model="perluSatgasPpks"
                                           class="mt-1 w-5 h-5 text-rose-600 rounded border-gray-300 focus:ring-rose-500 transition">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span class="text-base">🛡️</span>
                                            <span class="text-sm font-bold text-slate-900">
                                                Kegiatan Memerlukan Rekomendasi &amp; Izin Satgas PPKS ITG
                                            </span>
                                        </div>
                                        <p class="text-xs text-slate-500 leading-relaxed">
                                            Beri tanda centang jika kegiatan melibatkan aktivitas fisik khusus, kegiatan di luar kampus, malam hari, perkemahan, atau malam keakraban.
                                        </p>
                                        <div class="pt-1 text-[11px] font-semibold">
                                            <span class="text-rose-700" x-show="perluSatgasPpks">
                                                &checkmark; Terpilih: Proposal akan diverifikasi Satgas PPKS setelah disetujui BPM sebelum diteruskan ke BKHM.
                                            </span>
                                            <span class="text-slate-600" x-show="!perluSatgasPpks">
                                                &bull; Tidak dicentang: Kegiatan umum/internal diteruskan langsung dari BPM ke BKHM (Bypass Satgas PPKS).
                                            </span>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <!-- Detail Instrumen Satgas PPKS (Tampil jika centang aktif) -->
                            <div x-show="perluSatgasPpks" x-transition class="p-4 bg-rose-50/70 border border-rose-200 rounded-xl space-y-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-rose-600 text-lg">📋</span>
                                    <div>
                                        <h4 class="font-bold text-sm text-slate-800">Instrumen Pengawasan &amp; Mitigasi Satgas PPKS</h4>
                                        <p class="text-xs text-slate-500">Kepatuhan mitigasi pencegahan kekerasan seksual &amp; perundungan (Permendikbudristek No. 30/2021).</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <x-input-label for="kategori_kegiatan" :value="__('Kategori Lingkup Kegiatan')" />
                                        <select id="kategori_kegiatan" name="kategori_kegiatan" class="block mt-1 w-full border-gray-300 focus:border-rose-500 focus:ring-rose-500 rounded-md shadow-sm text-sm">
                                            <option value="dalam_kampus" {{ old('kategori_kegiatan', $pengajuan->kategori_kegiatan) === 'dalam_kampus' ? 'selected' : '' }}>Di Lingkungan Kampus ITG</option>
                                            <option value="luar_kampus" {{ old('kategori_kegiatan', $pengajuan->kategori_kegiatan) === 'luar_kampus' ? 'selected' : '' }}>Luar Kampus (Studi Tur / Makrab / Baksos)</option>
                                            <option value="menginap" {{ old('kategori_kegiatan', $pengajuan->kategori_kegiatan) === 'menginap' ? 'selected' : '' }}>Kegiatan Menginap / Berkemah (Camp)</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('kategori_kegiatan')" class="mt-2" />
                                    </div>

                                    <div>
                                        <x-input-label for="kontak_pic_ppks" :value="__('Narahubung Darurat Panitia (WhatsApp 24 Jam)')" />
                                        <x-text-input id="kontak_pic_ppks" class="block mt-1 w-full text-sm" type="text" name="kontak_pic_ppks" :value="old('kontak_pic_ppks', $pengajuan->kontak_pic_ppks)" placeholder="Cth: Ahmad (081234567890)" />
                                        <x-input-error :messages="$errors->get('kontak_pic_ppks')" class="mt-2" />
                                    </div>
                                </div>

                                <div>
                                    <x-input-label for="file_pakta_ppks" :value="__('Pakta Integritas / Surat Komitmen Anti-Kekerasan Seksual (PDF maks 5MB)')" />
                                    <p class="text-sm text-gray-500 mb-1">Kosongkan jika tidak ingin mengganti berkas pakta integritas.</p>
                                    <input id="file_pakta_ppks" class="block mt-1 w-full border border-gray-300 rounded p-2 text-sm bg-white" type="file" name="file_pakta_ppks" accept=".pdf" />
                                    <x-input-error :messages="$errors->get('file_pakta_ppks')" class="mt-2" />
                                    @if($pengajuan->file_pakta_ppks)
                                        <div class="mt-2 text-sm">
                                            Berkas saat ini: <a href="{{ route('dokumen.pakta-ppks', $pengajuan) }}" target="_blank" class="text-rose-600 hover:underline">Lihat Pakta PPKS</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Banner Info Bypass Satgas PPKS untuk Permohonan Dana Non-Kegiatan -->
                        <div x-show="jenisPengajuan !== 'kegiatan'" x-transition style="display: none;" x-cloak
                             class="mb-6 p-4 bg-emerald-50/80 border border-emerald-200 rounded-xl text-emerald-950 flex items-start gap-3">
                            <span class="text-2xl shrink-0 mt-0.5">🚀</span>
                            <div class="space-y-1 text-xs">
                                <h4 class="font-bold text-sm text-emerald-900">Jalur Khusus Permohonan Dana (Bypass Satgas PPKS)</h4>
                                <p class="text-emerald-800 leading-relaxed">
                                    Pengajuan ini berkategori <strong x-text="jenisPengajuan === 'pengadaan_barang' ? 'Pengadaan Barang / Inventaris' : 'Pendanaan Lomba / Delegasi'"></strong> (non-kegiatan fisik).
                                    Alur persetujuan <strong>tidak melalui Satgas PPKS</strong> dan verifikasi dari <strong>BPM akan langsung diteruskan ke BKHM</strong>.
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4" href="{{ route('pengajuan.show', $pengajuan) }}">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>