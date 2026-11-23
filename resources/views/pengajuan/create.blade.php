<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ Auth::user()->isHima() ? __('Buat Pengajuan Dana Non-Akademik') : __('Buat Pengajuan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(isset($blocking) && $blocking)
                @php
                    $isDanaCair = $blocking->state->name === 'funds_disbursed';
                    $isLpjReview = in_array($blocking->state->name, ['lpj_submitted', 'lpj_wr3_review']);
                @endphp
                <div class="{{ $isDanaCair ? 'bg-amber-50 border-l-4 border-amber-500' : ($isLpjReview ? 'bg-indigo-50 border-l-4 border-indigo-500' : 'bg-yellow-50 border-l-4 border-yellow-400') }} p-6 rounded-lg shadow mb-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                            @if($isDanaCair)
                                <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @elseif($isLpjReview)
                                <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @else
                                <svg class="h-6 w-6 text-yellow-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                            @endif
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-base font-bold {{ $isDanaCair ? 'text-amber-900' : ($isLpjReview ? 'text-indigo-900' : 'text-yellow-800') }}">
                                @if($isDanaCair)
                                    Pengajuan Ditangguhkan: Menunggu Unggah Dokumen LPJ
                                @elseif($isLpjReview)
                                    Pengajuan Ditangguhkan: Dokumen LPJ Sedang Ditinjau
                                @else
                                    Pengajuan Ditangguhkan: Menunggu Penyelesaian Kegiatan Sebelumnya
                                @endif
                            </h3>
                            
                            <p class="text-sm {{ $isDanaCair ? 'text-amber-800' : ($isLpjReview ? 'text-indigo-800' : 'text-yellow-700') }} mt-1">
                                @if($isDanaCair)
                                    Dana kegiatan sebelumnya telah dicairkan oleh Bendahara. Sesuai ketentuan kepatuhan keuangan kemahasiswaan ITG, Anda <strong>wajib mengunggah Laporan Pertanggungjawaban (LPJ)</strong> kegiatan tersebut agar gembok pengajuan proposal baru dapat dibuka kembali.
                                @elseif($isLpjReview)
                                    Dokumen LPJ telah diunggah (status: <strong>{{ $blocking->state->label }}</strong>) dan saat ini sedang dalam proses evaluasi oleh tim verifikator (BKHM / WR3). Gembok pengajuan proposal baru akan otomatis terbuka setelah evaluasi LPJ disetujui penuh (Selesai/Completed).
                                @else
                                    Anda belum bisa mengajukan proposal baru karena masih ada proposal kegiatan yang sedang berjalan dalam tahapan verifikasi atau ditolak.
                                @endif
                            </p>

                            <div class="mt-3.5 text-sm bg-white p-4 rounded-lg border border-gray-200 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div>
                                    <div class="text-gray-500 text-xs uppercase font-semibold">Kegiatan yang Sedang Berjalan:</div>
                                    <div class="font-bold text-gray-900 text-sm mt-0.5">{{ $blocking->nama_kegiatan }}</div>
                                    <div class="text-xs text-gray-600 mt-0.5">Status Saat Ini: <span class="font-bold {{ $isDanaCair ? 'text-amber-700' : 'text-indigo-700' }}">{{ $blocking->state->label ?? $blocking->state->name }}</span></div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <a href="{{ route('pengajuan.show', $blocking) }}" class="inline-flex items-center px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-xs transition">
                                        Lihat Pengajuan
                                    </a>
                                    @if($isDanaCair)
                                        <a href="{{ route('lpj.create', $blocking) }}" class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg text-xs shadow transition">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                            Unggah LPJ Sekarang
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-4 flex items-center gap-3">
                                <a href="{{ route('pengajuan.show', $blocking) }}" class="text-xs font-semibold {{ $isDanaCair ? 'text-amber-900 underline' : 'text-indigo-700 underline' }}">
                                    Lihat Detail & Riwayat Pengajuan
                                </a>
                                <span class="text-gray-300">•</span>
                                <a href="{{ route('pengajuan.index') }}" class="text-xs text-gray-600 hover:underline">
                                    Ke Riwayat Proposal
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Form -->
                <div class="lg:col-span-2">
                    @if(isset($fromProposal) && $fromProposal)
                        <div class="mb-4 p-4 rounded-xl bg-blue-50 border border-blue-200 text-xs text-blue-900 flex items-center justify-between gap-3 shadow-2xs">
                            <div class="flex items-center gap-2">
                                <span class="text-lg">📋</span>
                                <div>
                                    <span class="font-bold">Data Terhubung dari Generator Proposal:</span>
                                    <span>"{{ $fromProposal->nama_kegiatan }}"</span>
                                    <span class="font-semibold text-blue-700">({{ $fromProposal->jenis_proposal_label }})</span>
                                </div>
                            </div>
                            <span class="px-2 py-0.5 rounded bg-blue-100 font-bold text-[11px] text-blue-800">RAB: Rp {{ number_format($fromProposal->rab->sum('total_harga'), 0, ',', '.') }}</span>
                        </div>
                    @endif

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 @if(isset($blocking) && $blocking) opacity-50 pointer-events-none @endif">
                        <form method="POST" action="{{ route('pengajuan.store') }}" enctype="multipart/form-data" 
                              x-data="{ 
                                submitting: false, 
                                jenisPengajuan: '{{ old('jenis_pengajuan', isset($fromProposal) && $fromProposal ? ($fromProposal->jenis_proposal ?? 'kegiatan') : 'kegiatan') }}',
                                perluSatgasPpks: {{ old('perlu_satgas_ppks') ? 'true' : 'false' }}
                              }" 
                              @submit="submitting = true">
                            @csrf
                            
                            <div class="space-y-6">
                                <!-- Pilihan Jenis Pengajuan -->
                                <div>
                                    <x-input-label :value="__('Kategori / Jenis Pengajuan')" class="mb-2" />
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        <label class="relative flex flex-col p-3.5 rounded-xl border-2 cursor-pointer transition select-none"
                                               :class="jenisPengajuan === 'kegiatan' ? 'border-indigo-600 bg-indigo-50/70 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'">
                                            <input type="radio" name="jenis_pengajuan" value="kegiatan" x-model="jenisPengajuan" class="sr-only" :disabled="isset($blocking) && $blocking">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xl">🎪</span>
                                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-indigo-100 text-indigo-800">Kegiatan</span>
                                            </div>
                                            <div class="font-bold text-xs sm:text-sm text-slate-900">Kegiatan Mahasiswa</div>
                                            <p class="text-[11px] text-slate-500 mt-1">Event, seminar, latihan dasar, atau workshop. <span class="text-rose-600 font-semibold">(Melalui Satgas PPKS)</span></p>
                                        </label>

                                        <label class="relative flex flex-col p-3.5 rounded-xl border-2 cursor-pointer transition select-none"
                                               :class="jenisPengajuan === 'pengadaan_barang' ? 'border-emerald-600 bg-emerald-50/70 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'">
                                            <input type="radio" name="jenis_pengajuan" value="pengadaan_barang" x-model="jenisPengajuan" class="sr-only" :disabled="isset($blocking) && $blocking">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xl">📦</span>
                                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">Inventaris</span>
                                            </div>
                                            <div class="font-bold text-xs sm:text-sm text-slate-900">Pengadaan Barang</div>
                                            <p class="text-[11px] text-slate-500 mt-1">Peralatan sekretariat &amp; inventaris ormawa. <span class="text-emerald-700 font-semibold">(Bypass Satgas PPKS)</span></p>
                                        </label>

                                        <label class="relative flex flex-col p-3.5 rounded-xl border-2 cursor-pointer transition select-none"
                                               :class="jenisPengajuan === 'pendanaan_lomba' ? 'border-amber-600 bg-amber-50/70 shadow-xs' : 'border-slate-200 bg-white hover:border-slate-300'">
                                            <input type="radio" name="jenis_pengajuan" value="pendanaan_lomba" x-model="jenisPengajuan" class="sr-only" :disabled="isset($blocking) && $blocking">
                                            <div class="flex items-center justify-between mb-1">
                                                <span class="text-xl">🏆</span>
                                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-amber-100 text-amber-800">Delegasi</span>
                                            </div>
                                            <div class="font-bold text-xs sm:text-sm text-slate-900">Pendanaan Lomba</div>
                                            <p class="text-[11px] text-slate-500 mt-1">Registrasi &amp; akomodasi delegasi kompetisi. <span class="text-emerald-700 font-semibold">(Bypass Satgas PPKS)</span></p>
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('jenis_pengajuan')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <label class="block font-medium text-sm text-gray-700 mb-1">
                                        <span x-show="jenisPengajuan === 'kegiatan'">Nama Kegiatan</span>
                                        <span x-show="jenisPengajuan === 'pengadaan_barang'" style="display: none;" x-cloak>Nama Pengadaan / Kebutuhan Inventaris</span>
                                        <span x-show="jenisPengajuan === 'pendanaan_lomba'" style="display: none;" x-cloak>Nama Kompetisi &amp; Delegasi Lomba</span>
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <x-text-input id="nama_kegiatan" class="block mt-1 w-full" type="text" name="nama_kegiatan" 
                                        :value="old('nama_kegiatan', isset($fromProposal) && $fromProposal ? $fromProposal->nama_kegiatan : '')" 
                                        required autofocus :disabled="isset($blocking) && $blocking"
                                        x-bind:placeholder="jenisPengajuan === 'kegiatan' ? 'Contoh: Lomba Coding Nasional ITG 2026' : (jenisPengajuan === 'pengadaan_barang' ? 'Contoh: Pengadaan Proyektor & Sound System Sekretariat' : 'Contoh: Delegasi GEMASTIK 2026 Tingkat Nasional')" />
                                    <x-input-error :messages="$errors->get('nama_kegiatan')" class="mt-2" />
                                </div>

                                @if(Auth::user()->isHima())
                                <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-800">
                                    ℹ️ <strong>Pengajuan Dana Non-Akademik Himpunan:</strong> Pengajuan dana kegiatan non-akademik ini diteruskan langsung ke BEM & Kemahasiswaan kampus ITG tanpa persetujuan prodi.
                                </div>
                                @else
                                <div class="mb-4">
                                    <x-input-label for="program_kerja_id" :value="__('Program Kerja Terkait (Opsional)')" />
                                    <select id="program_kerja_id" name="program_kerja_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm text-sm" @if(isset($blocking) && $blocking) disabled @endif>
                                        <option value="">-- Tidak Terkait / Di Luar Program Kerja Tahunan --</option>
                                        @foreach($prokers ?? [] as $proker)
                                            <option value="{{ $proker->id }}" {{ old('program_kerja_id') == $proker->id ? 'selected' : '' }}>
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

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <x-input-label for="tanggal_mulai_kegiatan" :value="__('Tanggal Mulai Kegiatan (Opsional)')" />
                                        <x-text-input id="tanggal_mulai_kegiatan" class="block mt-1 w-full" type="date" name="tanggal_mulai_kegiatan" :value="old('tanggal_mulai_kegiatan')" :disabled="isset($blocking) && $blocking" onchange="document.getElementById('tanggal_selesai_kegiatan').min = this.value" />
                                        <x-input-error :messages="$errors->get('tanggal_mulai_kegiatan')" class="mt-2" />
                                    </div>
                                    <div>
                                        <x-input-label for="tanggal_selesai_kegiatan" :value="__('Tanggal Selesai Kegiatan (Opsional)')" />
                                        <x-text-input id="tanggal_selesai_kegiatan" class="block mt-1 w-full" type="date" name="tanggal_selesai_kegiatan" :value="old('tanggal_selesai_kegiatan')" :min="old('tanggal_mulai_kegiatan')" :disabled="isset($blocking) && $blocking" />
                                        <x-input-error :messages="$errors->get('tanggal_selesai_kegiatan')" class="mt-2" />
                                    </div>
                                    <div class="col-span-1 md:col-span-2 -mt-2">
                                        <p class="text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded p-2">
                                            💡 <strong>Urgensi Pencairan:</strong> Mengisi tanggal pelaksanaan kegiatan akan menampilkan indikator hitung mundur (H-X) pada antrean verifikator sehingga proposal dapat diprioritaskan sebelum acara berlangsung.
                                        </p>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="dana_diajukan" :value="__('Dana Diajukan (Rp)')" />
                                    @php
                                        $defaultDana = old('dana_diajukan', isset($fromProposal) && $fromProposal ? $fromProposal->rab->sum('total_harga') : '');
                                    @endphp
                                    <x-text-input id="dana_diajukan" class="block mt-1 w-full [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none" type="number" name="dana_diajukan" :value="$defaultDana" required min="0" :disabled="isset($blocking) && $blocking" onwheel="this.blur()" oninput="document.getElementById('dana_diajukan_preview').innerText = this.value ? 'Terbilang: Rp ' + new Intl.NumberFormat('id-ID').format(this.value) : ''" />
                                    <p id="dana_diajukan_preview" class="text-xs text-indigo-600 mt-1 font-semibold">
                                        {{ $defaultDana ? 'Terbilang: Rp ' . number_format($defaultDana, 0, ',', '.') : '' }}
                                    </p>
                                    <x-input-error :messages="$errors->get('dana_diajukan')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <x-input-label for="tanggal_pengajuan" :value="__('Tanggal Pengajuan')" />
                                    <x-text-input id="tanggal_pengajuan" class="block mt-1 w-full" type="date" name="tanggal_pengajuan" :value="old('tanggal_pengajuan', date('Y-m-d'))" required :disabled="isset($blocking) && $blocking" />
                                    <x-input-error :messages="$errors->get('tanggal_pengajuan')" class="mt-2" />
                                </div>

                                <div class="mb-4">
                                    <label class="block font-medium text-sm text-gray-700 mb-1">
                                        <span x-show="jenisPengajuan === 'kegiatan'">File Proposal Kegiatan (PDF, maks 5MB)</span>
                                        <span x-show="jenisPengajuan === 'pengadaan_barang'" style="display: none;" x-cloak>File Proposal / Surat Permohonan Pengadaan Barang (PDF, maks 5MB)</span>
                                        <span x-show="jenisPengajuan === 'pendanaan_lomba'" style="display: none;" x-cloak>File Proposal Delegasi &amp; Undangan Lomba (PDF, maks 5MB)</span>
                                        <span class="text-rose-500">*</span>
                                    </label>
                                    <input id="file_proposal" class="block mt-1 w-full border border-gray-300 rounded p-2 text-sm" type="file" name="file_proposal" accept=".pdf" required @if(isset($blocking) && $blocking) disabled @endif />
                                    <x-input-error :messages="$errors->get('file_proposal')" class="mt-2" />
                                </div>

                                <!-- Bagian Pengawasan & Kepatuhan Satgas PPKS (Khusus Kegiatan Mahasiswa) -->
                                <div x-show="jenisPengajuan === 'kegiatan'" x-transition class="space-y-4">
                                    <!-- Opsi Checkbox Izin Satgas PPKS -->
                                    <div class="p-4 bg-white border-2 rounded-xl transition cursor-pointer select-none"
                                         :class="perluSatgasPpks ? 'border-rose-500 bg-rose-50/40 shadow-xs' : 'border-slate-200 bg-slate-50/50'">
                                        <label class="flex items-start gap-3 cursor-pointer">
                                            <input type="checkbox" 
                                                   name="perlu_satgas_ppks" 
                                                   value="1" 
                                                   x-model="perluSatgasPpks"
                                                   class="mt-1 w-5 h-5 text-rose-600 rounded border-gray-300 focus:ring-rose-500 transition"
                                                   @if(isset($blocking) && $blocking) disabled @endif>
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

                                    <!-- Instrumen Pengawasan PPKS Detail (Hanya Tampil Jika Opsi Dicentang) -->
                                    <div x-show="perluSatgasPpks" x-transition class="p-4 bg-rose-50/70 border border-rose-200 rounded-xl space-y-4">
                                        <div class="flex items-center gap-2">
                                            <span class="text-rose-600 text-lg">📋</span>
                                            <div>
                                                <h4 class="font-bold text-sm text-slate-800">Instrumen Mitigasi &amp; Narahubung Satgas PPKS</h4>
                                                <p class="text-xs text-slate-500">Kelengkapan dokumen pencegahan kekerasan seksual &amp; perundungan (Permendikbudristek No. 30/2021).</p>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <x-input-label for="kategori_kegiatan" :value="__('Kategori Lingkup Kegiatan')" />
                                                <select id="kategori_kegiatan" name="kategori_kegiatan" class="block mt-1 w-full border-gray-300 focus:border-rose-500 focus:ring-rose-500 rounded-md shadow-sm text-sm" @if(isset($blocking) && $blocking) disabled @endif>
                                                    <option value="dalam_kampus" {{ old('kategori_kegiatan') === 'dalam_kampus' ? 'selected' : '' }}>Di Lingkungan Kampus ITG</option>
                                                    <option value="luar_kampus" {{ old('kategori_kegiatan') === 'luar_kampus' ? 'selected' : '' }}>Luar Kampus (Studi Tur / Makrab / Baksos)</option>
                                                    <option value="menginap" {{ old('kategori_kegiatan') === 'menginap' ? 'selected' : '' }}>Kegiatan Menginap / Berkemah (Camp)</option>
                                                </select>
                                                <x-input-error :messages="$errors->get('kategori_kegiatan')" class="mt-2" />
                                            </div>

                                            <div>
                                                <x-input-label for="kontak_pic_ppks" :value="__('Narahubung Darurat Panitia (WhatsApp 24 Jam)')" />
                                                <x-text-input id="kontak_pic_ppks" class="block mt-1 w-full text-sm" type="text" name="kontak_pic_ppks" :value="old('kontak_pic_ppks')" placeholder="Cth: Ahmad (081234567890)" :disabled="isset($blocking) && $blocking" />
                                                <x-input-error :messages="$errors->get('kontak_pic_ppks')" class="mt-2" />
                                            </div>
                                        </div>

                                        <div>
                                            <x-input-label for="file_pakta_ppks" :value="__('Pakta Integritas / Surat Komitmen Anti-Kekerasan Seksual (Opsional, PDF maks 5MB)')" />
                                            <input id="file_pakta_ppks" class="block mt-1 w-full border border-gray-300 rounded p-2 text-sm bg-white" type="file" name="file_pakta_ppks" accept=".pdf" @if(isset($blocking) && $blocking) disabled @endif />
                                            <p class="text-[11px] text-slate-500 mt-1">Unggah surat pernyataan komitmen panitia bebas kekerasan seksual &amp; perundungan yang telah ditandatangani ketua panitia/ketua umum.</p>
                                            <x-input-error :messages="$errors->get('file_pakta_ppks')" class="mt-2" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Banner Info Bypass Satgas PPKS untuk Permohonan Dana Non-Kegiatan -->
                                <div x-show="jenisPengajuan !== 'kegiatan'" x-transition style="display: none;" x-cloak
                                     class="p-4 bg-emerald-50/80 border border-emerald-200 rounded-xl text-emerald-950 flex items-start gap-3">
                                    <span class="text-2xl shrink-0 mt-0.5">🚀</span>
                                    <div class="space-y-1 text-xs">
                                        <h4 class="font-bold text-sm text-emerald-900">Jalur Khusus Permohonan Dana (Bypass Satgas PPKS)</h4>
                                        <p class="text-emerald-800 leading-relaxed">
                                            Pengajuan ini berkategori <strong x-text="jenisPengajuan === 'pengadaan_barang' ? 'Pengadaan Barang / Inventaris' : 'Pendanaan Lomba / Delegasi'"></strong> (non-kegiatan fisik mahasiswa).
                                            Alur persetujuan <strong>tidak memerlukan instrumen Satgas PPKS</strong> dan verifikasi dari <strong>BPM akan langsung diteruskan ke BKHM</strong>.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-4" href="{{ route('pengajuan.index') }}">
                                    Batal
                                </a>
                                <x-primary-button :disabled="isset($blocking) && $blocking" x-bind:disabled="submitting">
                                    <span x-show="!submitting">{{ __('Simpan sebagai Draft') }}</span>
                                    <span x-show="submitting" class="inline-flex items-center gap-2" style="display: none;" x-cloak>
                                        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span>Mengunggah Dokumen...</span>
                                    </span>
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Right Column: Financial Info -->
                <div class="space-y-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                        <h3 class="text-sm font-medium text-gray-500 uppercase mb-2">Sisa Saldo Anda</h3>
                        <p class="text-3xl font-bold text-green-600">Rp {{ number_format(Auth::user()->saldo, 0, ',', '.') }}</p>
                        <p class="text-xs text-gray-400 mt-2 italic">*Pastikan dana yang diajukan tidak melebihi saldo tersedia.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
