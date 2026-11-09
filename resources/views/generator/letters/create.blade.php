<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Generator Surat Otomatis') }}</h2></x-slot>
    <div class="py-12" x-data="{ type: '{{ old('type','undangan') }}' }">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Informasi Konfigurasi KOP Profil -->
            <div class="mb-6 p-4 rounded-2xl bg-indigo-50/70 border border-indigo-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                <div class="flex items-start gap-2.5">
                    <svg class="w-5 h-5 text-indigo-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <div class="text-slate-700 leading-relaxed">
                        <span class="font-bold text-indigo-950">KOP Surat Otomatis:</span> Surat ini akan otomatis mencantumkan KOP resmi organisasi Anda (Nama Ormawa, Logo, Alamat Kampus, Website, dan Email). Format KOP dapat disesuaikan kapan saja di <strong class="text-indigo-900">Halaman Profil</strong>.
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" target="_blank" class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-indigo-300 hover:bg-indigo-50 text-indigo-700 font-semibold rounded-lg shadow-sm transition">
                    <span>Atur KOP di Profil</span> &rarr;
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                        <div class="flex items-center gap-2 font-bold mb-2">
                            <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            <span>Mohon periksa dan lengkapi isian formulir berikut:</span>
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('generator.letters.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <h3 class="font-bold text-indigo-600 border-b-2 border-indigo-500 pb-2 mb-4">Informasi Dasar Surat</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <x-input-label for="type" :value="__('Jenis Surat')" />
                            <select id="type" name="type" x-model="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm font-semibold text-slate-800 text-sm" required>
                                <optgroup label="Persuratan Utama">
                                    <option value="undangan">Surat Undangan</option>
                                    <option value="tugas">Surat Tugas / Mandat</option>
                                    <option value="permohonan">Surat Permohonan (Alat / Bantuan)</option>
                                    <option value="keterangan_aktif">Surat Keterangan Aktif Organisasi</option>
                                </optgroup>
                                <optgroup label="Izin, Fasilitas &amp; Akademik">
                                    <option value="izin_kegiatan">Surat Izin Pelaksanaan Kegiatan</option>
                                    <option value="peminjaman_tempat">Surat Peminjaman Tempat / Fasilitas Kampus</option>
                                    <option value="dispensasi">Surat Permohonan Dispensasi Kuliah / Praktikum</option>
                                    <option value="rekomendasi">Surat Rekomendasi (Beasiswa / Lomba / Magang)</option>
                                </optgroup>
                                <optgroup label="Administrasi &amp; Kemitraan">
                                    <option value="pemberitahuan">Surat Pemberitahuan Kegiatan</option>
                                    <option value="pengantar">Surat Pengantar Dokumen / Berkas</option>
                                    <option value="ucapan_terima_kasih">Surat Ucapan Terima Kasih &amp; Apresiasi</option>
                                    <option value="kerjasama">Surat Permohonan Kerja Sama / Sponsorship</option>
                                </optgroup>
                                <optgroup label="Disiplin &amp; Tata Tertib">
                                    <option value="peringatan">Surat Peringatan / Teguran Disiplin Internal Ormawa</option>
                                </optgroup>
                            </select>
                        </div>
                        <div>
                            <x-input-label for="nomor_surat" :value="__('Nomor Surat (Opsional)')" />
                            <x-text-input id="nomor_surat" name="nomor_surat" type="text" class="mt-1 block w-full" placeholder="Contoh: 001/HIMATIF/ITG/V/2026" :value="old('nomor_surat')" />
                        </div>
                        <div>
                            <x-input-label for="lampiran" :value="__('Lampiran (Opsional)')" />
                            <x-text-input id="lampiran" name="lampiran" type="text" class="mt-1 block w-full" placeholder="Contoh: 1 (satu) Berkas atau -" :value="old('lampiran')" />
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="perihal" :value="__('Perihal')" />
                            <x-text-input id="perihal" name="perihal" type="text" class="mt-1 block w-full" required placeholder="Contoh: Undangan Pemateri Seminar Nasional" :value="old('perihal')" />
                            <x-input-error :messages="$errors->get('perihal')" class="mt-2" />
                        </div>
                        <div class="sm:col-span-2">
                            <x-input-label for="tujuan" :value="__('Tujuan Surat (Yth.)')" />
                            <x-text-input id="tujuan" name="tujuan" type="text" class="mt-1 block w-full" required placeholder="Contoh: Bapak Ir. Budi Santoso, M.T. di Tempat" :value="old('tujuan')" />
                            <x-input-error :messages="$errors->get('tujuan')" class="mt-2" />
                        </div>
                        <div class="sm:col-span-2 p-3 bg-indigo-50/50 rounded-lg border border-dashed border-indigo-200">
                            <x-input-label for="file_lampiran_pdf" :value="__('Unggah Lampiran Berkas PDF Tambahan (Opsional - Otomatis di-merge)')" class="text-indigo-900 font-semibold text-xs" />
                            <input id="file_lampiran_pdf" name="file_lampiran_pdf" type="file" accept="application/pdf" class="mt-1 block w-full text-xs text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700" />
                            <p class="mt-1 text-[11px] text-slate-500">Jika diunggah, lembar lampiran PDF ini akan digabungkan otomatis di akhir dokumen surat resmi saat diunduh.</p>
                        </div>
                    </div>

                    <!-- Detail Undangan -->
                    <div x-show="type==='undangan'" x-cloak class="mt-6">
                        <h3 class="font-bold text-indigo-600 border-b-2 border-indigo-500 pb-2 mb-4">Detail Undangan</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <x-input-label for="kalimat_pembuka_undangan" :value="__('Kalimat Pembuka')" />
                                <textarea id="kalimat_pembuka_undangan" name="kalimat_pembuka" rows="3" :disabled="type !== 'undangan'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Sehubungan dengan akan dilaksanakannya kegiatan...">{{ old('kalimat_pembuka', 'Dengan hormat, sehubungan dengan akan dilaksanakannya kegiatan kami, bersama ini kami bermaksud mengundang Bapak/Ibu untuk dapat hadir pada kegiatan yang diselenggarakan:') }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2"><x-input-label for="nama_acara" :value="__('Nama Acara')" /><x-text-input id="nama_acara" name="nama_acara" type="text" class="mt-1 block w-full" ::disabled="type !== 'undangan'" placeholder="Seminar Nasional Teknologi Informasi" :value="old('nama_acara')" /></div>
                                <div><x-input-label for="hari_tanggal" :value="__('Hari / Tanggal')" /><x-text-input id="hari_tanggal" name="hari_tanggal" type="text" class="mt-1 block w-full" ::disabled="type !== 'undangan'" placeholder="Senin, 20 Mei 2026" :value="old('hari_tanggal')" /></div>
                                <div><x-input-label for="waktu" :value="__('Waktu')" /><x-text-input id="waktu" name="waktu" type="text" class="mt-1 block w-full" ::disabled="type !== 'undangan'" placeholder="08.00 WIB s.d Selesai" :value="old('waktu')" /></div>
                                <div class="sm:col-span-2"><x-input-label for="tempat" :value="__('Tempat')" /><x-text-input id="tempat" name="tempat" type="text" class="mt-1 block w-full" ::disabled="type !== 'undangan'" placeholder="Aula Utama Institut Teknologi Garut" :value="old('tempat')" /></div>
                            </div>
                            <div>
                                <x-input-label for="kalimat_penutup_undangan" :value="__('Kalimat Penutup')" />
                                <textarea id="kalimat_penutup_undangan" name="kalimat_penutup" rows="2" :disabled="type !== 'undangan'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_penutup', 'Demikian surat undangan ini kami sampaikan. Atas perhatian, kehadiran, dan kerja sama Bapak/Ibu, kami ucapkan terima kasih.') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Tugas -->
                    <div x-show="type==='tugas'" x-cloak class="mt-6"
                         x-data="{
                             tambahanPetugas: [
                                 @php
                                     $oldTambahanNama = old('petugas_tambahan_nama', []);
                                     $oldTambahanNim  = old('petugas_tambahan_nim', []);
                                 @endphp
                                 @if(is_array($oldTambahanNama) && count($oldTambahanNama) > 0)
                                     @foreach($oldTambahanNama as $idx => $n)
                                         { nama: '{{ addslashes($n) }}', nim: '{{ addslashes($oldTambahanNim[$idx] ?? '') }}' },
                                     @endforeach
                                 @endif
                             ],
                             tambahPetugas() {
                                 this.tambahanPetugas.push({ nama: '', nim: '' });
                             },
                             hapusPetugas(index) {
                                 this.tambahanPetugas.splice(index, 1);
                             }
                         }">
                        <h3 class="font-bold text-indigo-600 border-b-2 border-indigo-500 pb-2 mb-4">Detail Surat Tugas</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <x-input-label for="kalimat_pembuka_tugas" :value="__('Kalimat Pembuka')" />
                                <textarea id="kalimat_pembuka_tugas" name="kalimat_pembuka_tugas" rows="2" :disabled="type !== 'tugas'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_pembuka_tugas', 'Yang bertanda tangan di bawah ini Pengurus Organisasi Kemahasiswaan Institut Teknologi Garut, dengan ini memberikan tugas / mandat kepada:') }}</textarea>
                            </div>

                            <!-- Daftar Petugas / Penerima Mandat -->
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-4">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-200 pb-2">
                                    <div>
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800">Daftar Penerima Tugas / Mandat</h4>
                                        <p class="text-[11px] text-slate-500">Dapat menambahkan lebih dari 1 orang jika tugas/mandat diberikan secara kolektif (delegasi/tim).</p>
                                    </div>
                                    <button type="button" @click="tambahPetugas()" class="self-start sm:self-auto inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        <span>+ Tambah Petugas</span>
                                    </button>
                                </div>

                                <!-- Petugas Utama (Item #1) -->
                                <div class="p-3 bg-white border border-slate-200 rounded-lg shadow-2xs">
                                    <div class="text-[11px] font-bold text-indigo-900 mb-2">Penerima Mandat #1 (Utama)</div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <x-input-label for="nama_petugas" :value="__('Nama Petugas')" />
                                            <x-text-input id="nama_petugas" name="nama_petugas" type="text" class="mt-1 block w-full text-sm" ::disabled="type !== 'tugas'" placeholder="Contoh: Muhammad Rasyid" :value="old('nama_petugas')" />
                                        </div>
                                        <div>
                                            <x-input-label for="nim_tugas" :value="__('NIM')" />
                                            <x-text-input id="nim_tugas" name="nim" type="text" class="mt-1 block w-full text-sm font-mono" placeholder="Contoh: 2206001" ::disabled="type !== 'tugas'" :value="old('nim')" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Petugas Tambahan Dinamis (Item #2, #3, dst.) -->
                                <template x-for="(petugas, idx) in tambahanPetugas" :key="idx">
                                    <div class="p-3 bg-white border border-indigo-100 rounded-lg shadow-2xs">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="text-[11px] font-bold text-slate-800" x-text="'Penerima Mandat #' + (idx + 2)"></div>
                                            <button type="button" @click="hapusPetugas(idx)" class="inline-flex items-center gap-1 text-[11px] text-rose-600 hover:text-rose-800 font-semibold transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span>Hapus</span>
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium text-slate-700 mb-1">Nama Petugas</label>
                                                <input type="text" name="petugas_tambahan_nama[]" x-model="petugas.nama" placeholder="Contoh: Ahmad Fauzan" :disabled="type !== 'tugas'" class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-slate-700 mb-1">NIM</label>
                                                <input type="text" name="petugas_tambahan_nim[]" x-model="petugas.nim" placeholder="Contoh: 2206002" :disabled="type !== 'tugas'" class="mt-1 block w-full text-sm font-mono border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <x-input-label for="uraian_tugas" :value="__('Uraian Tugas')" />
                                    <textarea id="uraian_tugas" name="uraian_tugas" rows="3" :disabled="type !== 'tugas'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: Menjadi delegasi resmi dalam kegiatan Musyawarah Nasional...">{{ old('uraian_tugas') }}</textarea>
                                </div>
                                <div>
                                    <x-input-label for="tanggal_pelaksanaan" :value="__('Tanggal Pelaksanaan')" />
                                    <x-text-input id="tanggal_pelaksanaan" name="tanggal_pelaksanaan" type="text" class="mt-1 block w-full" ::disabled="type !== 'tugas'" placeholder="20 - 25 Mei 2026" :value="old('tanggal_pelaksanaan')" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="kalimat_penutup_tugas" :value="__('Kalimat Penutup')" />
                                <textarea id="kalimat_penutup_tugas" name="kalimat_penutup_tugas" rows="2" :disabled="type !== 'tugas'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_penutup_tugas', 'Untuk melaksanakan tugas tersebut dengan penuh tanggung jawab. Surat tugas ini dibuat untuk dapat dipergunakan sebagaimana mestinya.') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Permohonan -->
                    <div x-show="type==='permohonan'" x-cloak class="mt-6">
                        <h3 class="font-bold text-indigo-600 border-b-2 border-indigo-500 pb-2 mb-4">Detail Permohonan</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <x-input-label for="kalimat_pembuka_permohonan" :value="__('Kalimat Pembuka')" />
                                <textarea id="kalimat_pembuka_permohonan" name="kalimat_pembuka_permohonan" rows="2" :disabled="type !== 'permohonan'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_pembuka_permohonan', 'Dengan hormat, sehubungan dengan agenda kegiatan yang akan kami selenggarakan, bersama ini kami mengajukan permohonan peminjaman:') }}</textarea>
                            </div>
                            <div><x-input-label for="nama_alat_tempat" :value="__('Nama Alat / Ruangan yang Dipinjam')" /><x-text-input id="nama_alat_tempat" name="nama_alat_tempat" type="text" class="mt-1 block w-full" ::disabled="type !== 'permohonan'" placeholder="Aula Gedung D / Sound System Portable" :value="old('nama_alat_tempat')" /></div>
                            <div><x-input-label for="waktu_penggunaan" :value="__('Waktu Penggunaan')" /><x-text-input id="waktu_penggunaan" name="waktu_penggunaan" type="text" class="mt-1 block w-full" ::disabled="type !== 'permohonan'" placeholder="Rabu, 22 Mei 2026 Jam 08.00 - 15.00 WIB" :value="old('waktu_penggunaan')" /></div>
                            <div><x-input-label for="alasan_tujuan" :value="__('Alasan / Tujuan Peminjaman')" /><textarea id="alasan_tujuan" name="alasan_tujuan" rows="3" :disabled="type !== 'permohonan'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Keperluan pelaksanaan workshop pembekalan pengurus...">{{ old('alasan_tujuan') }}</textarea></div>
                            <div>
                                <x-input-label for="kalimat_penutup_permohonan" :value="__('Kalimat Penutup')" />
                                <textarea id="kalimat_penutup_permohonan" name="kalimat_penutup_permohonan" rows="2" :disabled="type !== 'permohonan'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_penutup_permohonan', 'Demikian permohonan ini kami ajukan. Atas perhatian, izin, dan bantuan Bapak/Ibu, kami ucapkan terima kasih.') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Keterangan Aktif -->
                    <div x-show="type==='keterangan_aktif'" x-cloak class="mt-6"
                         x-data="{
                             tambahanMahasiswa: [
                                 @php
                                     $oldTambahanMhsNama    = old('mahasiswa_tambahan_nama', []);
                                     $oldTambahanMhsNim     = old('mahasiswa_tambahan_nim', []);
                                     $oldTambahanMhsJabatan = old('mahasiswa_tambahan_jabatan', []);
                                 @endphp
                                 @if(is_array($oldTambahanMhsNama) && count($oldTambahanMhsNama) > 0)
                                     @foreach($oldTambahanMhsNama as $idx => $n)
                                         {
                                             nama: '{{ addslashes($n) }}',
                                             nim: '{{ addslashes($oldTambahanMhsNim[$idx] ?? '') }}',
                                             jabatan: '{{ addslashes($oldTambahanMhsJabatan[$idx] ?? '') }}'
                                         },
                                     @endforeach
                                 @endif
                             ],
                             tambahMahasiswa() {
                                 this.tambahanMahasiswa.push({ nama: '', nim: '', jabatan: '' });
                             },
                             hapusMahasiswa(index) {
                                 this.tambahanMahasiswa.splice(index, 1);
                             }
                         }">
                        <h3 class="font-bold text-indigo-600 border-b-2 border-indigo-500 pb-2 mb-4">Detail Surat Keterangan</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <x-input-label for="kalimat_pembuka_ket" :value="__('Kalimat Pembuka')" />
                                <textarea id="kalimat_pembuka_ket" name="kalimat_pembuka_ket" rows="2" :disabled="type !== 'keterangan_aktif'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_pembuka_ket', 'Yang bertanda tangan di bawah ini Pengurus Organisasi Kemahasiswaan Institut Teknologi Garut, dengan ini menerangkan bahwa:') }}</textarea>
                            </div>

                            <!-- Daftar Mahasiswa yang Diterangkan -->
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-4">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-200 pb-2">
                                    <div>
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800">Daftar Mahasiswa yang Diterangkan</h4>
                                        <p class="text-[11px] text-slate-500">Dapat menambahkan lebih dari 1 mahasiswa jika surat keterangan ditujukan secara kolektif.</p>
                                    </div>
                                    <button type="button" @click="tambahMahasiswa()" class="self-start sm:self-auto inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        <span>+ Tambah Mahasiswa</span>
                                    </button>
                                </div>

                                <!-- Mahasiswa Utama (#1) -->
                                <div class="p-3 bg-white border border-slate-200 rounded-lg shadow-2xs">
                                    <div class="text-[11px] font-bold text-indigo-900 mb-2">Mahasiswa #1 (Utama)</div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div>
                                            <x-input-label for="nama_mahasiswa" :value="__('Nama Mahasiswa')" />
                                            <x-text-input id="nama_mahasiswa" name="nama_mahasiswa" type="text" class="mt-1 block w-full text-sm" ::disabled="type !== 'keterangan_aktif'" placeholder="Contoh: Deni Firmansyah" :value="old('nama_mahasiswa')" />
                                        </div>
                                        <div>
                                            <x-input-label for="nim_ket" :value="__('NIM')" />
                                            <x-text-input id="nim_ket" name="nim" type="text" class="mt-1 block w-full text-sm font-mono" placeholder="Contoh: 2206001" ::disabled="type !== 'keterangan_aktif'" :value="old('nim')" />
                                        </div>
                                        <div class="sm:col-span-2">
                                            <x-input-label for="jabatan" :value="__('Jabatan di Organisasi')" />
                                            <x-text-input id="jabatan" name="jabatan" type="text" class="mt-1 block w-full text-sm" ::disabled="type !== 'keterangan_aktif'" placeholder="Contoh: Anggota Bidang Minat dan Bakat" :value="old('jabatan')" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Mahasiswa Tambahan Dinamis (#2, #3, dst.) -->
                                <template x-for="(mhs, idx) in tambahanMahasiswa" :key="idx">
                                    <div class="p-3 bg-white border border-indigo-100 rounded-lg shadow-2xs">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="text-[11px] font-bold text-slate-800" x-text="'Mahasiswa #' + (idx + 2)"></div>
                                            <button type="button" @click="hapusMahasiswa(idx)" class="inline-flex items-center gap-1 text-[11px] text-rose-600 hover:text-rose-800 font-semibold transition">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                <span>Hapus</span>
                                            </button>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-xs font-medium text-slate-700 mb-1">Nama Mahasiswa</label>
                                                <input type="text" name="mahasiswa_tambahan_nama[]" x-model="mhs.nama" placeholder="Contoh: Siti Aisyah" :disabled="type !== 'keterangan_aktif'" class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-slate-700 mb-1">NIM</label>
                                                <input type="text" name="mahasiswa_tambahan_nim[]" x-model="mhs.nim" placeholder="Contoh: 2206002" :disabled="type !== 'keterangan_aktif'" class="mt-1 block w-full text-sm font-mono border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label class="block text-xs font-medium text-slate-700 mb-1">Jabatan di Organisasi</label>
                                                <input type="text" name="mahasiswa_tambahan_jabatan[]" x-model="mhs.jabatan" placeholder="Contoh: Anggota Divisi Humas" :disabled="type !== 'keterangan_aktif'" class="mt-1 block w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <x-input-label for="keperluan" :value="__('Tujuan / Keperluan Surat')" />
                                    <textarea id="keperluan" name="keperluan" rows="2" :disabled="type !== 'keterangan_aktif'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: Persyaratan Beasiswa Prestasi Kemendikbud...">{{ old('keperluan') }}</textarea>
                                </div>
                            </div>
                            <div>
                                <x-input-label for="kalimat_penutup_ket" :value="__('Kalimat Penutup')" />
                                <textarea id="kalimat_penutup_ket" name="kalimat_penutup_ket" rows="2" :disabled="type !== 'keterangan_aktif'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_penutup_ket', 'Adalah benar mahasiswa/anggota aktif pada organisasi kami. Surat keterangan ini kami buat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Izin Pelaksanaan Kegiatan -->
                    <div x-show="type==='izin_kegiatan'" x-cloak class="mt-6">
                        <h3 class="font-bold text-indigo-600 border-b-2 border-indigo-500 pb-2 mb-4">Detail Izin Pelaksanaan Kegiatan</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <x-input-label for="kalimat_pembuka_izin" :value="__('Kalimat Pembuka')" />
                                <textarea id="kalimat_pembuka_izin" name="kalimat_pembuka" rows="3" :disabled="type !== 'izin_kegiatan'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_pembuka', 'Dengan hormat, sehubungan dengan rencana pelaksanaan agenda kegiatan organisasi mahasiswa kami, bersama ini kami mengajukan permohonan izin penyelenggaraan kegiatan sebagai berikut:') }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <x-input-label for="nama_kegiatan_izin" :value="__('Nama Kegiatan *')" />
                                    <x-text-input id="nama_kegiatan_izin" name="nama_kegiatan" type="text" class="mt-1 block w-full" ::disabled="type !== 'izin_kegiatan'" placeholder="Contoh: Latihan Dasar Kepemimpinan Mahasiswa (LDKM)" :value="old('nama_kegiatan')" />
                                </div>
                                <div>
                                    <x-input-label for="hari_tanggal_izin" :value="__('Hari / Tanggal Pelaksanaan *')" />
                                    <x-text-input id="hari_tanggal_izin" name="hari_tanggal" type="text" class="mt-1 block w-full" ::disabled="type !== 'izin_kegiatan'" placeholder="Contoh: Sabtu - Minggu, 12 - 13 Juni 2026" :value="old('hari_tanggal')" />
                                </div>
                                <div>
                                    <x-input-label for="waktu_izin" :value="__('Waktu Kegiatan *')" />
                                    <x-text-input id="waktu_izin" name="waktu" type="text" class="mt-1 block w-full" ::disabled="type !== 'izin_kegiatan'" placeholder="Contoh: 08.00 WIB s.d Selesai" :value="old('waktu')" />
                                </div>
                                <div>
                                    <x-input-label for="tempat_izin" :value="__('Tempat / Lokasi Kegiatan *')" />
                                    <x-text-input id="tempat_izin" name="tempat" type="text" class="mt-1 block w-full" ::disabled="type !== 'izin_kegiatan'" placeholder="Contoh: Gedung Aula Utama Kampus ITG" :value="old('tempat')" />
                                </div>
                                <div>
                                    <x-input-label for="jumlah_peserta_izin" :value="__('Estimasi Jumlah Peserta')" />
                                    <x-text-input id="jumlah_peserta_izin" name="jumlah_peserta" type="text" class="mt-1 block w-full" ::disabled="type !== 'izin_kegiatan'" placeholder="Contoh: 80 Orang Mahasiswa" :value="old('jumlah_peserta')" />
                                </div>
                                <div class="sm:col-span-2">
                                    <x-input-label for="penanggung_jawab_izin" :value="__('Penanggung Jawab Kegiatan (Nama & Kontak)')" />
                                    <x-text-input id="penanggung_jawab_izin" name="penanggung_jawab" type="text" class="mt-1 block w-full" ::disabled="type !== 'izin_kegiatan'" placeholder="Contoh: Muhammad Rizky (081234567890)" :value="old('penanggung_jawab')" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="kalimat_penutup_izin" :value="__('Kalimat Penutup')" />
                                <textarea id="kalimat_penutup_izin" name="kalimat_penutup" rows="2" :disabled="type !== 'izin_kegiatan'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_penutup', 'Demikian permohonan izin kegiatan ini kami sampaikan. Atas perhatian, dukungan, dan izin yang diberikan, kami ucapkan terima kasih.') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Peminjaman Fasilitas & Tempat -->
                    <div x-show="type==='peminjaman_tempat'" x-cloak class="mt-6">
                        <h3 class="font-bold text-indigo-600 border-b-2 border-indigo-500 pb-2 mb-4">Detail Peminjaman Fasilitas &amp; Tempat</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <x-input-label for="kalimat_pembuka_tempat" :value="__('Kalimat Pembuka')" />
                                <textarea id="kalimat_pembuka_tempat" name="kalimat_pembuka" rows="3" :disabled="type !== 'peminjaman_tempat'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_pembuka', 'Dengan hormat, sehubungan dengan akan diselenggarakannya agenda kegiatan mahasiswa, bersama ini kami mengajukan permohonan peminjaman fasilitas kampus sebagai berikut:') }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <x-input-label for="nama_fasilitas" :value="__('Nama Ruangan / Fasilitas yang Dipinjam *')" />
                                    <x-text-input id="nama_fasilitas" name="nama_fasilitas" type="text" class="mt-1 block w-full" ::disabled="type !== 'peminjaman_tempat'" placeholder="Contoh: Gedung Auditorium / Lab Komputer 3" :value="old('nama_fasilitas')" />
                                </div>
                                <div>
                                    <x-input-label for="hari_tanggal_tempat" :value="__('Hari / Tanggal Peminjaman *')" />
                                    <x-text-input id="hari_tanggal_tempat" name="hari_tanggal" type="text" class="mt-1 block w-full" ::disabled="type !== 'peminjaman_tempat'" placeholder="Contoh: Jumat, 25 Mei 2026" :value="old('hari_tanggal')" />
                                </div>
                                <div>
                                    <x-input-label for="waktu_mulai_selesai" :value="__('Waktu Penggunaan *')" />
                                    <x-text-input id="waktu_mulai_selesai" name="waktu_mulai_selesai" type="text" class="mt-1 block w-full" ::disabled="type !== 'peminjaman_tempat'" placeholder="Contoh: 08.00 s.d 16.00 WIB" :value="old('waktu_mulai_selesai')" />
                                </div>
                                <div class="sm:col-span-2">
                                    <x-input-label for="tujuan_penggunaan" :value="__('Agenda / Tujuan Penggunaan Fasilitas *')" />
                                    <x-text-input id="tujuan_penggunaan" name="tujuan_penggunaan" type="text" class="mt-1 block w-full" ::disabled="type !== 'peminjaman_tempat'" placeholder="Contoh: Workshop Pemrograman Web & Cloud Computing" :value="old('tujuan_penggunaan')" />
                                </div>
                                <div class="sm:col-span-2">
                                    <x-input-label for="penanggung_jawab_tempat" :value="__('Penanggung Jawab Lapangan (Nama & Kontak)')" />
                                    <x-text-input id="penanggung_jawab_tempat" name="penanggung_jawab" type="text" class="mt-1 block w-full" ::disabled="type !== 'peminjaman_tempat'" placeholder="Contoh: Fajar Pratama (089876543210)" :value="old('penanggung_jawab')" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="kalimat_penutup_tempat" :value="__('Kalimat Penutup')" />
                                <textarea id="kalimat_penutup_tempat" name="kalimat_penutup" rows="2" :disabled="type !== 'peminjaman_tempat'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_penutup', 'Besar harapan kami agar permohonan peminjaman ini dapat disetujui. Kami berkomitmen menjaga ketertiban, kebersihan, dan keselamatan fasilitas kampus yang digunakan. Atas kerja sama Bapak/Ibu, kami ucapkan terima kasih.') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Permohonan Dispensasi Kuliah -->
                    <div x-show="type==='dispensasi'" x-cloak class="mt-6"
                         x-data="{
                             tambahanDispensasi: [],
                             tambahMhs() { this.tambahanDispensasi.push({ nama: '', nim: '', prodi: '' }); },
                             hapusMhs(idx) { this.tambahanDispensasi.splice(idx, 1); }
                         }">
                        <h3 class="font-bold text-indigo-600 border-b-2 border-indigo-500 pb-2 mb-4">Detail Permohonan Dispensasi Kuliah</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <x-input-label for="kalimat_pembuka_dispensasi" :value="__('Kalimat Pembuka')" />
                                <textarea id="kalimat_pembuka_dispensasi" name="kalimat_pembuka" rows="3" :disabled="type !== 'dispensasi'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_pembuka', 'Dengan hormat, sehubungan dengan partisipasi dan penugasan mahasiswa pada agenda kegiatan institusi, dengan ini kami memohon dispensasi ketidakhadiran perkuliahan/praktikum bagi mahasiswa berikut:') }}</textarea>
                            </div>
                            
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="text-xs font-bold uppercase tracking-wider text-slate-800">Daftar Mahasiswa yang Mengajukan Dispensasi</h4>
                                        <p class="text-[11px] text-slate-500">Bisa menambahkan lebih dari satu mahasiswa jika delegasi terdiri dari tim.</p>
                                    </div>
                                    <button type="button" @click="tambahMhs()" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-semibold shadow-xs transition">
                                        <span>+ Tambah Mahasiswa</span>
                                    </button>
                                </div>

                                <div class="p-3 bg-white border border-slate-200 rounded-lg">
                                    <div class="text-[11px] font-bold text-indigo-900 mb-2">Mahasiswa #1 (Utama)</div>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        <div>
                                            <x-input-label for="nama_mahasiswa_disp" :value="__('Nama Lengkap *')" />
                                            <x-text-input id="nama_mahasiswa_disp" name="nama_mahasiswa" type="text" class="mt-1 block w-full text-sm" ::disabled="type !== 'dispensasi'" placeholder="Contoh: Rian Saputra" :value="old('nama_mahasiswa')" />
                                        </div>
                                        <div>
                                            <x-input-label for="nim_disp" :value="__('NIM *')" />
                                            <x-text-input id="nim_disp" name="nim" type="text" class="mt-1 block w-full text-sm font-mono" placeholder="Contoh: 2206015" ::disabled="type !== 'dispensasi'" :value="old('nim')" />
                                        </div>
                                        <div>
                                            <x-input-label for="kelas_prodi_disp" :value="__('Program Studi / Kelas')" />
                                            <x-text-input id="kelas_prodi_disp" name="kelas_prodi" type="text" class="mt-1 block w-full text-sm" ::disabled="type !== 'dispensasi'" placeholder="Contoh: Teknik Informatika / IF-C" :value="old('kelas_prodi')" />
                                        </div>
                                    </div>
                                </div>

                                <template x-for="(mhs, idx) in tambahanDispensasi" :key="idx">
                                    <div class="p-3 bg-white border border-indigo-100 rounded-lg">
                                        <div class="flex items-center justify-between mb-2">
                                            <div class="text-[11px] font-bold text-slate-800" x-text="'Mahasiswa #' + (idx + 2)"></div>
                                            <button type="button" @click="hapusMhs(idx)" class="text-[11px] text-rose-600 hover:text-rose-800 font-semibold">Hapus</button>
                                        </div>
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                            <div>
                                                <input type="text" name="mahasiswa_tambahan_nama[]" x-model="mhs.nama" placeholder="Nama Mahasiswa" :disabled="type !== 'dispensasi'" class="block w-full text-sm border-gray-300 rounded-md shadow-sm">
                                            </div>
                                            <div>
                                                <input type="text" name="mahasiswa_tambahan_nim[]" x-model="mhs.nim" placeholder="NIM" :disabled="type !== 'dispensasi'" class="block w-full text-sm font-mono border-gray-300 rounded-md shadow-sm">
                                            </div>
                                            <div>
                                                <input type="text" name="mahasiswa_tambahan_prodi[]" x-model="mhs.prodi" placeholder="Prodi / Kelas" :disabled="type !== 'dispensasi'" class="block w-full text-sm border-gray-300 rounded-md shadow-sm">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <x-input-label for="nama_kegiatan_disp" :value="__('Nama Kegiatan / Kompetisi *')" />
                                    <x-text-input id="nama_kegiatan_disp" name="nama_kegiatan" type="text" class="mt-1 block w-full" ::disabled="type !== 'dispensasi'" placeholder="Contoh: Kompetisi Gemastik XIX Bidang Software Development" :value="old('nama_kegiatan')" />
                                </div>
                                <div>
                                    <x-input-label for="hari_tanggal_disp" :value="__('Hari / Tanggal Dispensasi *')" />
                                    <x-text-input id="hari_tanggal_disp" name="hari_tanggal" type="text" class="mt-1 block w-full" ::disabled="type !== 'dispensasi'" placeholder="Contoh: Rabu, 18 Oktober 2026" :value="old('hari_tanggal')" />
                                </div>
                                <div>
                                    <x-input-label for="waktu_disp" :value="__('Waktu / Jam Kuliah')" />
                                    <x-text-input id="waktu_disp" name="waktu" type="text" class="mt-1 block w-full" ::disabled="type !== 'dispensasi'" placeholder="Contoh: 08.00 - 15.00 WIB / Sepanjang Hari" :value="old('waktu')" />
                                </div>
                                <div class="sm:col-span-2">
                                    <x-input-label for="keterangan_tugas_disp" :value="__('Keterangan Keikutsertaan *')" />
                                    <x-text-input id="keterangan_tugas_disp" name="keterangan_tugas" type="text" class="mt-1 block w-full" ::disabled="type !== 'dispensasi'" placeholder="Contoh: Sebagai Delegasi Peserta Finalis Lomba Nasional" :value="old('keterangan_tugas')" />
                                </div>
                            </div>

                            <div>
                                <x-input-label for="kalimat_penutup_disp" :value="__('Kalimat Penutup')" />
                                <textarea id="kalimat_penutup_disp" name="kalimat_penutup" rows="2" :disabled="type !== 'dispensasi'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_penutup', 'Demikian permohonan dispensasi ini kami ajukan. Atas perhatian, dukungan, dan kebijaksanaan Bapak/Ibu Dosen Pengampu mata kuliah, kami ucapkan terima kasih.') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Surat Rekomendasi -->
                    <div x-show="type==='rekomendasi'" x-cloak class="mt-6">
                        <h3 class="font-bold text-indigo-600 border-b-2 border-indigo-500 pb-2 mb-4">Detail Surat Rekomendasi</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <x-input-label for="kalimat_pembuka_rek" :value="__('Kalimat Pembuka')" />
                                <textarea id="kalimat_pembuka_rek" name="kalimat_pembuka" rows="3" :disabled="type !== 'rekomendasi'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_pembuka', 'Yang bertanda tangan di bawah ini Pengurus Organisasi Kemahasiswaan Institut Teknologi Garut, dengan ini memberikan rekomendasi kepada:') }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="nama_mahasiswa_rek" :value="__('Nama Mahasiswa yang Direkomendasikan *')" />
                                    <x-text-input id="nama_mahasiswa_rek" name="nama_mahasiswa" type="text" class="mt-1 block w-full" ::disabled="type !== 'rekomendasi'" placeholder="Contoh: Aldi Taher" :value="old('nama_mahasiswa')" />
                                </div>
                                <div>
                                    <x-input-label for="nim_rek" :value="__('NIM *')" />
                                    <x-text-input id="nim_rek" name="nim" type="text" class="mt-1 block w-full font-mono" ::disabled="type !== 'rekomendasi'" placeholder="Contoh: 2106005" :value="old('nim')" />
                                </div>
                                <div class="sm:col-span-2">
                                    <x-input-label for="program_studi_rek" :value="__('Program Studi &amp; Semester *')" />
                                    <x-text-input id="program_studi_rek" name="program_studi" type="text" class="mt-1 block w-full" ::disabled="type !== 'rekomendasi'" placeholder="Contoh: S1 Teknik Informatika (Semester VI)" :value="old('program_studi')" />
                                </div>
                                <div class="sm:col-span-2">
                                    <x-input-label for="tujuan_rekomendasi" :value="__('Keperluan Rekomendasi *')" />
                                    <x-text-input id="tujuan_rekomendasi" name="tujuan_rekomendasi" type="text" class="mt-1 block w-full" ::disabled="type !== 'rekomendasi'" placeholder="Contoh: Pendaftaran Beasiswa Bank Indonesia Tahun 2026" :value="old('tujuan_rekomendasi')" />
                                </div>
                                <div class="sm:col-span-2">
                                    <x-input-label for="prestasi_rekam_jejak" :value="__('Rekam Jejak / Prestasi / Kualifikasi Mahasiswa *')" />
                                    <textarea id="prestasi_rekam_jejak" name="prestasi_rekam_jejak" rows="3" :disabled="type !== 'rekomendasi'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Contoh: Memiliki IPK 3.85, aktif sebagai Koordinator Riset Himpunan, dan memiliki integritas serta kedisiplinan yang sangat baik.">{{ old('prestasi_rekam_jejak') }}</textarea>
                                </div>
                            </div>
                            <div>
                                <x-input-label for="kalimat_penutup_rek" :value="__('Kalimat Penutup')" />
                                <textarea id="kalimat_penutup_rek" name="kalimat_penutup" rows="2" :disabled="type !== 'rekomendasi'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_penutup', 'Surat rekomendasi ini kami berikan dengan penuh pertimbangan atas dedikasi, integritas, dan kompetensi yang bersangkutan untuk dapat dipergunakan sebagaimana mestinya.') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Surat Pemberitahuan -->
                    <div x-show="type==='pemberitahuan'" x-cloak class="mt-6">
                        <h3 class="font-bold text-indigo-600 border-b-2 border-indigo-500 pb-2 mb-4">Detail Surat Pemberitahuan</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <x-input-label for="kalimat_pembuka_pem" :value="__('Kalimat Pembuka')" />
                                <textarea id="kalimat_pembuka_pem" name="kalimat_pembuka" rows="3" :disabled="type !== 'pemberitahuan'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_pembuka', 'Dengan hormat, melalui surat ini kami bermaksud memberitahukan bahwa Organisasi Mahasiswa kami akan menyelenggarakan kegiatan sebagai berikut:') }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <x-input-label for="nama_kegiatan_pem" :value="__('Nama Kegiatan *')" />
                                    <x-text-input id="nama_kegiatan_pem" name="nama_kegiatan" type="text" class="mt-1 block w-full" ::disabled="type !== 'pemberitahuan'" placeholder="Contoh: Bakti Sosial &amp; Donor Darah Kampus" :value="old('nama_kegiatan')" />
                                </div>
                                <div>
                                    <x-input-label for="hari_tanggal_pem" :value="__('Hari / Tanggal Pelaksanaan *')" />
                                    <x-text-input id="hari_tanggal_pem" name="hari_tanggal" type="text" class="mt-1 block w-full" ::disabled="type !== 'pemberitahuan'" placeholder="Contoh: Sabtu, 30 Mei 2026" :value="old('hari_tanggal')" />
                                </div>
                                <div>
                                    <x-input-label for="waktu_pem" :value="__('Waktu Kegiatan *')" />
                                    <x-text-input id="waktu_pem" name="waktu" type="text" class="mt-1 block w-full" ::disabled="type !== 'pemberitahuan'" placeholder="Contoh: 09.00 - 15.00 WIB" :value="old('waktu')" />
                                </div>
                                <div class="sm:col-span-2">
                                    <x-input-label for="tempat_pem" :value="__('Tempat / Lokasi *')" />
                                    <x-text-input id="tempat_pem" name="tempat" type="text" class="mt-1 block w-full" ::disabled="type !== 'pemberitahuan'" placeholder="Contoh: Lapangan Parkir Kampus ITG" :value="old('tempat')" />
                                </div>
                                <div class="sm:col-span-2">
                                    <x-input-label for="uraian_kegiatan_pem" :value="__('Uraian / Ringkasan Rangkaian Kegiatan *')" />
                                    <textarea id="uraian_kegiatan_pem" name="uraian_kegiatan" rows="3" :disabled="type !== 'pemberitahuan'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Jelaskan secara singkat tujuan dan teknis kegiatan...">{{ old('uraian_kegiatan') }}</textarea>
                                </div>
                            </div>
                            <div>
                                <x-input-label for="kalimat_penutup_pem" :value="__('Kalimat Penutup')" />
                                <textarea id="kalimat_penutup_pem" name="kalimat_penutup" rows="2" :disabled="type !== 'pemberitahuan'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_penutup', 'Demikian surat pemberitahuan ini kami sampaikan sebagai bentuk koordinasi dan keterbukaan informasi. Atas perhatian dan kerja sama yang baik, kami ucapkan terima kasih.') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Surat Pengantar -->
                    <div x-show="type==='pengantar'" x-cloak class="mt-6">
                        <h3 class="font-bold text-indigo-600 border-b-2 border-indigo-500 pb-2 mb-4">Detail Surat Pengantar Dokumen</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <x-input-label for="kalimat_pembuka_peng" :value="__('Kalimat Pembuka')" />
                                <textarea id="kalimat_pembuka_peng" name="kalimat_pembuka" rows="3" :disabled="type !== 'pengantar'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_pembuka', 'Dengan hormat, bersama surat ini kami sampaikan berkas dokumen resmi organisasi kemahasiswaan dengan rincian sebagai berikut:') }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <x-input-label for="berkas_dokumen" :value="__('Nama Berkas / Dokumen yang Diserahkan *')" />
                                    <x-text-input id="berkas_dokumen" name="berkas_dokumen" type="text" class="mt-1 block w-full" ::disabled="type !== 'pengantar'" placeholder="Contoh: Proposal Pengajuan Bantuan Dana Kegiatan Gebyar ITG 2026" :value="old('berkas_dokumen')" />
                                </div>
                                <div>
                                    <x-input-label for="jumlah_eksemplar" :value="__('Banyaknya / Jumlah Eksemplar *')" />
                                    <x-text-input id="jumlah_eksemplar" name="jumlah_eksemplar" type="text" class="mt-1 block w-full" ::disabled="type !== 'pengantar'" placeholder="Contoh: 1 (satu) Berkas Asli" :value="old('jumlah_eksemplar')" />
                                </div>
                                <div>
                                    <x-input-label for="keterangan_berkas" :value="__('Keterangan / Tindak Lanjut')" />
                                    <x-text-input id="keterangan_berkas" name="keterangan_berkas" type="text" class="mt-1 block w-full" ::disabled="type !== 'pengantar'" placeholder="Contoh: Disampaikan untuk ditinjau dan disetujui" :value="old('keterangan_berkas')" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="kalimat_penutup_peng" :value="__('Kalimat Penutup')" />
                                <textarea id="kalimat_penutup_peng" name="kalimat_penutup" rows="2" :disabled="type !== 'pengantar'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_penutup', 'Demikian surat pengantar ini kami sampaikan. Mohon perkenan Bapak/Ibu untuk memeriksa dan memproses dokumen tersebut lebih lanjut. Atas perhatiannya, kami ucapkan terima kasih.') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Surat Ucapan Terima Kasih -->
                    <div x-show="type==='ucapan_terima_kasih'" x-cloak class="mt-6">
                        <h3 class="font-bold text-indigo-600 border-b-2 border-indigo-500 pb-2 mb-4">Detail Surat Ucapan Terima Kasih &amp; Apresiasi</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <x-input-label for="kalimat_pembuka_tk" :value="__('Kalimat Pembuka')" />
                                <textarea id="kalimat_pembuka_tk" name="kalimat_pembuka" rows="3" :disabled="type !== 'ucapan_terima_kasih'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_pembuka', 'Dengan hormat, sehubungan dengan telah terselenggaranya kegiatan kami dengan sukses dan lancar, kami segenap Pengurus dan Panitia menyampaikan apresiasi dan ucapan terima kasih sebesar-besarnya kepada:') }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <x-input-label for="nama_pihak" :value="__('Nama Tokoh / Instansi Penerima Apresiasi *')" />
                                    <x-text-input id="nama_pihak" name="nama_pihak" type="text" class="mt-1 block w-full" ::disabled="type !== 'ucapan_terima_kasih'" placeholder="Contoh: Bapak Dr. Eng. Irwan Setiawan, M.T." :value="old('nama_pihak')" />
                                </div>
                                <div class="sm:col-span-2">
                                    <x-input-label for="peran_kontribusi" :value="__('Peran / Bentuk Kontribusi *')" />
                                    <x-text-input id="peran_kontribusi" name="peran_kontribusi" type="text" class="mt-1 block w-full" ::disabled="type !== 'ucapan_terima_kasih'" placeholder="Contoh: Narasumber Utama Seminar Teknologi Artificial Intelligence" :value="old('peran_kontribusi')" />
                                </div>
                                <div>
                                    <x-input-label for="nama_acara_tk" :value="__('Nama Kegiatan / Acara *')" />
                                    <x-text-input id="nama_acara_tk" name="nama_acara" type="text" class="mt-1 block w-full" ::disabled="type !== 'ucapan_terima_kasih'" placeholder="Contoh: National Tech Summit 2026" :value="old('nama_acara')" />
                                </div>
                                <div>
                                    <x-input-label for="hari_tanggal_tk" :value="__('Tanggal Acara *')" />
                                    <x-text-input id="hari_tanggal_tk" name="hari_tanggal" type="text" class="mt-1 block w-full" ::disabled="type !== 'ucapan_terima_kasih'" placeholder="Contoh: Kamis, 14 Mei 2026" :value="old('hari_tanggal')" />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="kalimat_penutup_tk" :value="__('Kalimat Penutup')" />
                                <textarea id="kalimat_penutup_tk" name="kalimat_penutup" rows="2" :disabled="type !== 'ucapan_terima_kasih'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_penutup', 'Kami mengucapkan terima kasih yang sebesar-besarnya atas kontribusi, ilmu, dan kerja sama yang telah diberikan. Semoga hubungan baik dan kemitraan ini dapat terus terjalin pada kesempatan yang akan datang.') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Permohonan Kerja Sama -->
                    <div x-show="type==='kerjasama'" x-cloak class="mt-6">
                        <h3 class="font-bold text-indigo-600 border-b-2 border-indigo-500 pb-2 mb-4">Detail Permohonan Kerja Sama / Sponsorship</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <x-input-label for="kalimat_pembuka_ks" :value="__('Kalimat Pembuka')" />
                                <textarea id="kalimat_pembuka_ks" name="kalimat_pembuka" rows="3" :disabled="type !== 'kerjasama'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_pembuka', 'Dengan hormat, dalam rangka menyukseskan agenda kegiatan mahasiswa skala regional/nasional, kami bermaksud menawarkan peluang kerja sama kemitraan yang saling menguntungkan sebagai berikut:') }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <x-input-label for="nama_kegiatan_ks" :value="__('Nama Kegiatan *')" />
                                    <x-text-input id="nama_kegiatan_ks" name="nama_kegiatan" type="text" class="mt-1 block w-full" ::disabled="type !== 'kerjasama'" placeholder="Contoh: ITG Informatics Festival (INFEST) 2026" :value="old('nama_kegiatan')" />
                                </div>
                                <div>
                                    <x-input-label for="bentuk_kerjasama" :value="__('Bentuk Kerja Sama yang Ditawarkan *')" />
                                    <x-text-input id="bentuk_kerjasama" name="bentuk_kerjasama" type="text" class="mt-1 block w-full" ::disabled="type !== 'kerjasama'" placeholder="Contoh: Sponsorship Paket Platinum / Media Partner" :value="old('bentuk_kerjasama')" />
                                </div>
                                <div>
                                    <x-input-label for="kontak_person_ks" :value="__('Contact Person (Nama &amp; HP) *')" />
                                    <x-text-input id="kontak_person_ks" name="kontak_person" type="text" class="mt-1 block w-full" ::disabled="type !== 'kerjasama'" placeholder="Contoh: Annisa Larasati (082112345678)" :value="old('kontak_person')" />
                                </div>
                                <div class="sm:col-span-2">
                                    <x-input-label for="manfaat_timbal_balik" :value="__('Manfaat / Timbal Balik untuk Mitra (Benefit) *')" />
                                    <textarea id="manfaat_timbal_balik" name="manfaat_timbal_balik" rows="3" :disabled="type !== 'kerjasama'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Contoh: Logo mitra dicantumkan pada seluruh spanduk, umbul-umbul, website, dan merchandise acara, serta stan pameran eksklusif.">{{ old('manfaat_timbal_balik') }}</textarea>
                                </div>
                            </div>
                            <div>
                                <x-input-label for="kalimat_penutup_ks" :value="__('Kalimat Penutup')" />
                                <textarea id="kalimat_penutup_ks" name="kalimat_penutup" rows="2" :disabled="type !== 'kerjasama'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_penutup', 'Besar harapan kami agar perusahaan/lembaga Bapak/Ibu berkenan menjadi mitra dalam agenda kegiatan ini. Atas perhatian, kesediaan, dan kerja sama yang baik, kami ucapkan terima kasih.') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Surat Peringatan Internal Ormawa -->
                    <div x-show="type==='peringatan'" x-cloak class="mt-6">
                        <h3 class="font-bold text-rose-600 border-b-2 border-rose-500 pb-2 mb-4">Detail Surat Peringatan Disiplin Internal Ormawa</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <x-input-label for="kalimat_pembuka_sp" :value="__('Kalimat Pembuka')" />
                                <textarea id="kalimat_pembuka_sp" name="kalimat_pembuka" rows="3" :disabled="type !== 'peringatan'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_pembuka', 'Berdasarkan Anggaran Dasar / Anggaran Rumah Tangga (AD/ART) serta hasil rapat evaluasi kedisiplinan pengurus, dengan ini kami memberikan Surat Peringatan kepada:') }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div>
                                    <x-input-label for="tingkat_sp" :value="__('Tingkat Peringatan *')" />
                                    <select id="tingkat_sp" name="tingkat_sp" :disabled="type !== 'peringatan'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
                                        <option value="SP-1">SP-1 (Peringatan Pertama)</option>
                                        <option value="SP-2">SP-2 (Peringatan Kedua)</option>
                                        <option value="SP-3">SP-3 (Peringatan Terakhir)</option>
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="nama_anggota_sp" :value="__('Nama Anggota / Pengurus *')" />
                                    <x-text-input id="nama_anggota_sp" name="nama_anggota" type="text" class="mt-1 block w-full" ::disabled="type !== 'peringatan'" placeholder="Nama Mahasiswa" :value="old('nama_anggota')" />
                                </div>
                                <div>
                                    <x-input-label for="nim_sp" :value="__('NIM *')" />
                                    <x-text-input id="nim_sp" name="nim" type="text" class="mt-1 block w-full font-mono" ::disabled="type !== 'peringatan'" placeholder="NIM Mahasiswa" :value="old('nim')" />
                                </div>
                                <div class="sm:col-span-3">
                                    <x-input-label for="jabatan_divisi_sp" :value="__('Jabatan / Divisi di Organisasi *')" />
                                    <x-text-input id="jabatan_divisi_sp" name="jabatan_divisi" type="text" class="mt-1 block w-full" ::disabled="type !== 'peringatan'" placeholder="Contoh: Staff Divisi Logistik dan Perlengkapan" :value="old('jabatan_divisi')" />
                                </div>
                                <div class="sm:col-span-3">
                                    <x-input-label for="pelanggaran_sp" :value="__('Uraian Pelanggaran / Ketidakhadiran *')" />
                                    <textarea id="pelanggaran_sp" name="pelanggaran" rows="3" :disabled="type !== 'peringatan'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Contoh: Tidak menghadiri rapat koordinasi 3 kali berturut-turut tanpa keterangan dan tidak menyelesaikan tanggung jawab divisi.">{{ old('pelanggaran') }}</textarea>
                                </div>
                                <div class="sm:col-span-3">
                                    <x-input-label for="sanksi_sp" :value="__('Sanksi Internal Organisasi *')" />
                                    <textarea id="sanksi_sp" name="sanksi" rows="2" :disabled="type !== 'peringatan'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Contoh: Penangguhan hak suara dalam musyawarah kerja dan kewajiban piket sekretariat.">{{ old('sanksi') }}</textarea>
                                </div>
                            </div>
                            <div>
                                <x-input-label for="kalimat_penutup_sp" :value="__('Kalimat Penutup')" />
                                <textarea id="kalimat_penutup_sp" name="kalimat_penutup" rows="2" :disabled="type !== 'peringatan'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">{{ old('kalimat_penutup', 'Surat peringatan ini diterbitkan agar yang bersangkutan segera mengevaluasi diri dan meningkatkan komitmen serta tanggung jawab dalam organisasi. Atas perhatiannya diucapkan terima kasih.') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Penandatangan Dinamis --}}
                    <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200"
                         x-data="{
                            signers: [{
                                jenis: 'internal',
                                role: 'ketua',
                                nama: '{{ addslashes(Auth::user()->nama_ketua ?? Auth::user()->name) }}',
                                jabatan: 'Ketua',
                                nim: '{{ addslashes(Auth::user()->nim_ketua ?? '') }}',
                                label_id: 'NIM'
                            }],
                            namaMap: {
                                ketua: '{{ addslashes(Auth::user()->nama_ketua ?? Auth::user()->name) }}',
                                sekretaris: '{{ addslashes(Auth::user()->nama_sekretaris ?? Auth::user()->name) }}',
                                bendahara: '{{ addslashes(Auth::user()->nama_bendahara ?? Auth::user()->name) }}'
                            },
                            nimMap: {
                                ketua: '{{ addslashes(Auth::user()->nim_ketua ?? '') }}',
                                sekretaris: '{{ addslashes(Auth::user()->nim_sekretaris ?? '') }}',
                                bendahara: '{{ addslashes(Auth::user()->nim_bendahara ?? '') }}'
                            },
                            roleLabels: {
                                ketua: 'Ketua Umum',
                                sekretaris: 'Sekretaris',
                                bendahara: 'Bendahara',
                                ketua_pelaksana: 'Ketua Pelaksana',
                                sekretaris_pelaksana: 'Sekretaris Pelaksana',
                                bendahara_pelaksana: 'Bendahara Pelaksana',
                                pembina: 'Dosen Pembina Ormawa',
                                pembimbing: 'Dosen Pembimbing Kegiatan',
                                kaprodi: 'Ketua Program Studi',
                                dekan: 'Dekan Fakultas',
                                dosen: 'Dosen Penanggung Jawab',
                                custom: 'Penandatangan'
                            },
                            dosenRoles: ['pembina', 'pembimbing', 'kaprodi', 'dekan', 'dosen'],
                            updateNama(idx) {
                                let s = this.signers[idx];
                                if (s.jenis !== 'internal') return;
                                s.nama = this.namaMap[s.role] || '';
                                s.nim = this.nimMap[s.role] || '';
                                s.jabatan = this.roleLabels[s.role] || 'Penandatangan';
                                if (this.dosenRoles.includes(s.role)) {
                                    s.label_id = 'NIDN';
                                } else if (['ketua', 'sekretaris', 'bendahara', 'ketua_pelaksana', 'sekretaris_pelaksana', 'bendahara_pelaksana'].includes(s.role)) {
                                    s.label_id = 'NIM';
                                }
                            },
                            onJenisChange(idx) {
                                let s = this.signers[idx];
                                if (s.jenis === 'internal') {
                                    s.nama = this.namaMap[s.role] || '';
                                    s.nim = this.nimMap[s.role] || '';
                                    s.jabatan = this.roleLabels[s.role] || 'Penandatangan';
                                    s.label_id = this.dosenRoles.includes(s.role) ? 'NIDN' : 'NIM';
                                } else {
                                    s.nama = '';
                                    s.nim = '';
                                    s.jabatan = '';
                                    s.label_id = 'NIDN';
                                }
                            },
                            addSigner() {
                                this.signers.push({
                                    jenis: 'internal',
                                    role: 'sekretaris',
                                    nama: this.namaMap['sekretaris'] || '',
                                    jabatan: 'Sekretaris',
                                    nim: this.nimMap['sekretaris'] || '',
                                    label_id: 'NIM'
                                });
                            },
                            removeSigner(idx) {
                                if (this.signers.length > 1) this.signers.splice(idx, 1);
                            }
                         }">
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <h3 class="font-bold text-indigo-600">Penandatangan Surat Resmi</h3>
                                <p class="text-xs text-slate-500">Penandatangan internal menyertakan Nama &amp; Nomor Identitas (NIM untuk Mahasiswa / NIDN untuk Dosen) serta diverifikasi QR Code.</p>
                            </div>
                            <button type="button" @click="addSigner()"
                                class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded-lg px-3 py-1.5 hover:bg-indigo-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                + Tambah Penandatangan
                            </button>
                        </div>

                        <template x-for="(signer, idx) in signers" :key="idx">
                            <div class="flex gap-3 items-start mb-3 p-3.5 bg-white rounded-lg border border-gray-200 shadow-sm">
                                <div class="flex-none pt-1">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold" x-text="idx + 1"></span>
                                </div>
                                <div class="flex-1 grid grid-cols-1 sm:grid-cols-4 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Penandatangan</label>
                                        <select :name="'penandatangan_jenis[' + idx + ']'"
                                                x-model="signer.jenis"
                                                @change="onJenisChange(idx)"
                                                class="block w-full border-gray-300 rounded-md shadow-sm text-xs">
                                            <option value="internal">Internal (Kampus ITG)</option>
                                            <option value="eksternal">Pihak Luar (Manual)</option>
                                        </select>
                                    </div>
                                    <div x-show="signer.jenis === 'internal'">
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Pilihan Jabatan</label>
                                        <select :name="'penandatangan_role[' + idx + ']'"
                                                x-model="signer.role"
                                                @change="updateNama(idx)"
                                                class="block w-full border-gray-300 rounded-md shadow-sm text-xs">
                                            <optgroup label="Pengurus Ormawa (Mahasiswa - NIM)">
                                                <option value="ketua">Ketua Umum Ormawa</option>
                                                <option value="sekretaris">Sekretaris Ormawa</option>
                                                <option value="bendahara">Bendahara Ormawa</option>
                                                <option value="ketua_pelaksana">Ketua Pelaksana</option>
                                                <option value="sekretaris_pelaksana">Sekretaris Pelaksana</option>
                                                <option value="bendahara_pelaksana">Bendahara Pelaksana</option>
                                            </optgroup>
                                            <optgroup label="Dosen &amp; Pembina (NIDN / NIP)">
                                                <option value="pembina">Dosen Pembina Ormawa</option>
                                                <option value="pembimbing">Dosen Pembimbing Kegiatan</option>
                                                <option value="kaprodi">Ketua Program Studi (Kaprodi)</option>
                                                <option value="dekan">Dekan Fakultas</option>
                                                <option value="dosen">Dosen Penanggung Jawab</option>
                                            </optgroup>
                                            <optgroup label="Lainnya">
                                                <option value="custom">Jabatan Lainnya (Input Manual)...</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div x-show="signer.jenis === 'eksternal' || signer.role === 'custom'">
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Jabatan</label>
                                        <input type="text"
                                               :name="'penandatangan_jabatan[' + idx + ']'"
                                               x-model="signer.jabatan"
                                               @input="if(signer.jabatan.toLowerCase().includes('dosen') || signer.jabatan.toLowerCase().includes('pembina') || signer.jabatan.toLowerCase().includes('kaprodi') || signer.jabatan.toLowerCase().includes('dekan')) { signer.label_id = 'NIDN'; }"
                                               placeholder="Contoh: Dosen Pembina / Ketua Mitra"
                                               class="block w-full border-gray-300 rounded-md shadow-sm text-xs" />
                                    </div>
                                    <template x-if="signer.jenis === 'internal' && signer.role !== 'custom'">
                                        <input type="hidden" :name="'penandatangan_jabatan[' + idx + ']'" :value="signer.jabatan">
                                    </template>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap &amp; Gelar</label>
                                        <input type="text"
                                               :name="'penandatangan_nama[' + idx + ']'"
                                               x-model="signer.nama"
                                               placeholder="Nama penandatangan..."
                                               class="block w-full border-gray-300 rounded-md shadow-sm text-xs" />
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Tipe Identitas</label>
                                        <select :name="'penandatangan_label_id[' + idx + ']'"
                                                x-model="signer.label_id"
                                                class="block w-full border-gray-300 rounded-md shadow-sm text-xs font-semibold text-indigo-700">
                                            <option value="NIDN">NIDN (Dosen)</option>
                                            <option value="NIM">NIM (Mahasiswa)</option>
                                            <option value="NIP">NIP (Pegawai/PNS)</option>
                                            <option value="NIK">NIK (Kependudukan)</option>
                                            <option value="ID">No. Identitas Lainnya</option>
                                        </select>
                                    </div>
                                    <div class="sm:col-span-4">
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">
                                            <span x-text="signer.label_id + ' Penandatangan (' + (signer.label_id === 'NIDN' ? 'Nomor Induk Dosen Nasional' : (signer.label_id === 'NIP' ? 'Nomor Induk Pegawai' : (signer.label_id === 'NIM' ? 'Nomor Induk Mahasiswa' : 'Nomor Identitas'))) + ')'"></span>
                                        </label>
                                        <input type="text"
                                               :name="'penandatangan_nim[' + idx + ']'"
                                               x-model="signer.nim"
                                               :placeholder="signer.label_id === 'NIDN' ? 'Contoh NIDN: 0421099301' : (signer.label_id === 'NIP' ? 'Contoh NIP: 198501152010121002' : 'Contoh NIM: 2206001')"
                                               class="block w-full border-gray-300 rounded-md shadow-sm text-xs font-mono" />
                                    </div>
                                </div>
                                <div class="flex-none mt-6">
                                    <button type="button" @click="removeSigner(idx)"
                                            x-show="signers.length > 1"
                                            class="p-1.5 text-slate-400 hover:text-rose-500 rounded transition-colors"
                                            title="Hapus penandatangan ini">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="flex justify-end mt-6"><x-primary-button>Generate Surat Resmi</x-primary-button></div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
