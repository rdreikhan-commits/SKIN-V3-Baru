<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Penerbitan Surat Peringatan (SP) Satgas PPKS') }}
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Penindakan pelanggaran norma etika kemahasiswaan dan pencegahan kekerasan seksual di ITG</p>
            </div>
            <a href="{{ route('satgas.sp.index') }}" class="text-xs font-semibold text-gray-600 hover:text-gray-900 border px-3 py-1.5 rounded-lg bg-white shadow-sm">
                &larr; Riwayat SP Satgas
            </a>
        </div>
    </x-slot>

    <div class="py-8" x-data="{
        tipe: '{{ old('tipe_sasaran', 'mahasiswa') }}',
        mahasiswas: {{ json_encode(old('target_mahasiswas', [['nim' => '', 'nama' => '', 'prodi' => '', 'kontak' => '']])) }},
        addMahasiswa() { this.mahasiswas.push({ nim: '', nama: '', prodi: '', kontak: '' }); },
        removeMahasiswa(i) { if (this.mahasiswas.length > 1) this.mahasiswas.splice(i, 1); }
    }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 sm:p-8 rounded-xl shadow-sm border border-gray-200">
                <div class="border-b pb-4 mb-6">
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-purple-100 text-purple-800 uppercase tracking-wide">Satgas PPKS</span>
                        <h3 class="font-bold text-gray-900 text-lg">Formulir Usulan Surat Peringatan</h3>
                    </div>
                    <p class="text-xs text-gray-500">Usulan Surat Peringatan ini akan diteruskan ke Wakil Rektor III untuk divalidasi dan disahkan secara resmi menggunakan Tanda Tangan Digital Kriptografis.</p>
                </div>

                <form method="POST" action="{{ route('satgas.sp.store') }}" class="space-y-6">
                    @csrf

                    @if($errors->any())
                        <div class="p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-xs">
                            <ul class="list-disc pl-4 space-y-0.5">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- 1. Pilihan Tipe Sasaran -->
                    <div>
                        <label class="block text-sm font-bold text-gray-800 mb-2">Tipe Sasaran Penerima SP <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-2 gap-4">
                            <label :class="tipe === 'mahasiswa' ? 'border-purple-600 bg-purple-50/50 text-purple-900 ring-2 ring-purple-500' : 'border-gray-200 bg-gray-50 text-gray-700'" class="border rounded-xl p-4 cursor-pointer flex items-center gap-3 transition">
                                <input type="radio" name="tipe_sasaran" value="mahasiswa" x-model="tipe" class="text-purple-600 focus:ring-purple-500">
                                <div>
                                    <div class="font-bold text-sm">Mahasiswa Perorangan (Individu)</div>
                                    <div class="text-[11px] text-gray-500">Pelanggaran kekerasan seksual / etika kemahasiswaan</div>
                                </div>
                            </label>
                            <label :class="tipe === 'ormawa' ? 'border-purple-600 bg-purple-50/50 text-purple-900 ring-2 ring-purple-500' : 'border-gray-200 bg-gray-50 text-gray-700'" class="border rounded-xl p-4 cursor-pointer flex items-center gap-3 transition">
                                <input type="radio" name="tipe_sasaran" value="ormawa" x-model="tipe" class="text-purple-600 focus:ring-purple-500">
                                <div>
                                    <div class="font-bold text-sm">Organisasi Mahasiswa (ORMAWA)</div>
                                    <div class="text-[11px] text-gray-500">Pelanggaran pakta integritas / kegiatan tanpa pengawasan</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- 2. Detail Sasaran -->
                    <!-- Bagian A: Ormawa -->
                    <div x-show="tipe === 'ormawa'" class="space-y-3 bg-gray-50/70 p-4 rounded-xl border border-gray-200">
                        <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">Pilih Organisasi Target <span class="text-red-500">*</span></label>
                        <select name="target_user_id" class="w-full border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">
                            <option value="">-- Pilih Organisasi Mahasiswa --</option>
                            @foreach($ormawas as $o)
                                <option value="{{ $o->id }}" {{ old('target_user_id') == $o->id ? 'selected' : '' }}>
                                    {{ $o->name }} ({{ $o->username }})
                                </option>
                            @endforeach
                        </select>
                        @error('target_user_id')<div class="text-xs text-red-600">{{ $message }}</div>@enderror
                    </div>

                    <!-- Bagian B: Mahasiswa Perorangan -->
                    <div x-show="tipe === 'mahasiswa'" class="space-y-4 bg-gray-50/70 p-4 rounded-xl border border-gray-200">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <label class="block text-xs font-bold uppercase tracking-wider text-gray-700">Daftar Mahasiswa Penerima SP <span class="text-red-500">*</span></label>
                                <p class="text-[11px] text-gray-500">Bisa menambahkan satu atau lebih mahasiswa terlapor/terbukti melanggar norma.</p>
                            </div>
                            <button type="button" @click="addMahasiswa()" class="inline-flex items-center gap-1 px-3 py-1 bg-purple-600 text-white rounded-lg text-xs font-semibold hover:bg-purple-700 shadow-xs">
                                <span>+ Tambah Mahasiswa</span>
                            </button>
                        </div>

                        <template x-for="(m, i) in mahasiswas" :key="i">
                            <div class="p-3 bg-white border border-gray-200 rounded-lg space-y-3 relative">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-gray-700" x-text="'Mahasiswa #' + (i + 1)"></span>
                                    <button type="button" x-show="mahasiswas.length > 1" @click="removeMahasiswa(i)" class="text-xs text-red-600 hover:text-red-800 font-semibold">
                                        Hapus
                                    </button>
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">NIM Mahasiswa *</label>
                                        <input type="text" :name="'target_mahasiswas[' + i + '][nim]'" x-model="m.nim" required class="w-full border-gray-300 rounded-lg text-xs font-mono" placeholder="2306001">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Nama Lengkap *</label>
                                        <input type="text" :name="'target_mahasiswas[' + i + '][nama]'" x-model="m.nama" required class="w-full border-gray-300 rounded-lg text-xs" placeholder="Nama Mahasiswa">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">Program Studi *</label>
                                        <select :name="'target_mahasiswas[' + i + '][prodi]'" x-model="m.prodi" required class="w-full border-gray-300 rounded-lg text-xs">
                                            <option value="">-- Pilih Prodi --</option>
                                            @foreach($prodis as $p)
                                                <option value="{{ $p }}">{{ $p }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-gray-700 mb-1">No. Kontak / WhatsApp (Opsional)</label>
                                        <input type="text" :name="'target_mahasiswas[' + i + '][kontak]'" x-model="m.kontak" class="w-full border-gray-300 rounded-lg text-xs" placeholder="08xxxxxxxxxx">
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- 3. Nomor, Tingkat, Tanggal -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Nomor Surat <span class="text-red-500">*</span></label>
                            <input type="text" name="nomor_surat" value="{{ old('nomor_surat', 'SP/PPKS/' . date('Y') . '/' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT)) }}" required class="w-full border-gray-300 rounded-lg text-sm font-mono focus:ring-purple-500 focus:border-purple-500">
                            @error('nomor_surat')<div class="text-xs text-red-600 mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Tingkat SP <span class="text-red-500">*</span></label>
                            <select name="tingkat" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">
                                <option value="SP-1" {{ old('tingkat') == 'SP-1' ? 'selected' : '' }}>SP-1 (Peringatan Pertama)</option>
                                <option value="SP-2" {{ old('tingkat') == 'SP-2' ? 'selected' : '' }}>SP-2 (Peringatan Kedua)</option>
                                <option value="SP-3" {{ old('tingkat') == 'SP-3' ? 'selected' : '' }}>SP-3 (Peringatan Keras Terakhir)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Tanggal Surat <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_surat" value="{{ old('tanggal_surat', date('Y-m-d')) }}" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>

                    <!-- 4. Perihal & Alasan Singkat -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Perihal Surat <span class="text-red-500">*</span></label>
                            <input type="text" name="perihal" value="{{ old('perihal', 'Peringatan Terkait Pelanggaran Kode Etik / Pencegahan dan Penanganan Kekerasan Seksual (PPKS)') }}" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Ringkasan Pelanggaran <span class="text-red-500">*</span></label>
                            <input type="text" name="alasan_singkat" value="{{ old('alasan_singkat') }}" required placeholder="Contoh: Terbukti melakukan pelecehan verbal pada kegiatan himpunan" class="w-full border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>

                    <!-- 5. Deskripsi & Sanksi -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Kronologi / Uraian Temuan Investigasi Satgas <span class="text-red-500">*</span></label>
                            <textarea name="deskripsi" rows="4" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500" placeholder="Jelaskan secara detail hasil investigasi Satgas PPKS, waktu, tempat, dan fakta-fakta pelanggaran yang terjadi...">{{ old('deskripsi') }}</textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-1">Rekomendasi Sanksi Disiplin <span class="text-red-500">*</span></label>
                            <textarea name="sanksi" rows="3" required class="w-full border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500" placeholder="Contoh: Teguran tertulis resmi, pencabutan hak mengikuti kepengurusan ormawa, dan kewajiban mengikuti sesi konseling pembinaan etika.">{{ old('sanksi') }}</textarea>
                        </div>
                    </div>

                    <!-- 6. Informasi Pengesahan Akhir -->
                    <div class="p-4 bg-purple-50/70 border border-purple-200 rounded-xl space-y-2 text-xs text-purple-900">
                        <div class="font-bold flex items-center gap-1.5 text-purple-950">
                            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>Alur Pengesahan Resmi Universitas</span>
                        </div>
                        <p class="leading-relaxed">
                            Sebagai dokumen resmi penindakan tingkat institut, Surat Peringatan yang diajukan oleh Satgas PPKS akan otomatis diteruskan ke <strong>Wakil Rektor III</strong> ({{ $wr3Info['nama'] }}) untuk ditinjau dan dibubuhi Tanda Tangan Digital Kriptografis serta QR Code verifikasi.
                        </p>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('satgas.sp.index') }}" class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-50 text-xs font-semibold">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-bold shadow-sm transition">
                            Terbitkan &amp; Teruskan ke WR3 &rarr;
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

