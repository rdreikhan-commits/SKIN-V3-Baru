<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Laporan Pertanggungjawaban (LPJ)') }}
        </h2>
    </x-slot>

    @php
        $defaultType = old('jenis_lpj', $jenisLpjDefault ?? (isset($proposal) && !empty($proposal->jenis_proposal) ? $proposal->jenis_proposal : (isset($pengajuan) && !empty($pengajuan->jenis_pengajuan) ? $pengajuan->jenis_pengajuan : 'kegiatan')));
        $namaAwal = old('nama_kegiatan', $proposal->nama_kegiatan ?? ($pengajuan->nama_kegiatan ?? ''));
        $tahunAwal = old('tahun_akademik', '2024-2025');
    @endphp

    <div class="py-12" x-data="{
        jenisLpj: '{{ $defaultType }}',
        get judulHalaman() {
            if (this.jenisLpj === 'pengadaan_barang') {
                return 'Buat Laporan Pertanggungjawaban (LPJ) Pengadaan Barang / Inventaris';
            } else if (this.jenisLpj === 'pendanaan_lomba') {
                return 'Buat Laporan Pertanggungjawaban (LPJ) Delegasi Perlombaan';
            }
            return 'Buat Laporan Pertanggungjawaban (LPJ) Kegiatan Mahasiswa';
        }
    }">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                <!-- Header Dinamis Berdasarkan Template -->
                <div class="mb-6 pb-4 border-b border-slate-200 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-2 px-2.5 py-1 rounded-full text-xs font-bold tracking-wide mb-2"
                             :class="{
                                'bg-indigo-100 text-indigo-800': jenisLpj === 'kegiatan',
                                'bg-emerald-100 text-emerald-800': jenisLpj === 'pengadaan_barang',
                                'bg-amber-100 text-amber-800': jenisLpj === 'pendanaan_lomba'
                             }">
                            <span class="w-2 h-2 rounded-full"
                                  :class="{
                                    'bg-indigo-600': jenisLpj === 'kegiatan',
                                    'bg-emerald-600': jenisLpj === 'pengadaan_barang',
                                    'bg-amber-600': jenisLpj === 'pendanaan_lomba'
                                  }"></span>
                            <span x-text="jenisLpj === 'pengadaan_barang' ? 'Template LPJ Pengadaan Barang' : (jenisLpj === 'pendanaan_lomba' ? 'Template LPJ Delegasi Perlombaan' : 'Template LPJ Kegiatan Mahasiswa')"></span>
                        </div>
                        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight" x-text="judulHalaman">
                            Buat Laporan Pertanggungjawaban (LPJ)
                        </h2>
                        <p class="text-xs text-slate-500 mt-1">
                            Pilih template LPJ yang sesuai dengan jenis realisasi dana organisasi Anda untuk menghasilkan format laporan standar ITG.
                        </p>
                    </div>

                    <a href="{{ route('profile.edit') }}" target="_blank" class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-50 border border-slate-300 hover:bg-slate-100 text-slate-700 text-xs font-semibold rounded-lg shadow-2xs transition">
                        <span>Pengaturan KOP di Profil</span> &rarr;
                    </a>
                </div>

                <!-- Informasi Konfigurasi KOP Profil -->
                <div class="mb-6 p-4 rounded-2xl bg-indigo-50/70 border border-indigo-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div class="flex items-start gap-2.5">
                        <svg class="w-5 h-5 text-indigo-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div class="text-slate-700 leading-relaxed">
                            <span class="font-bold text-indigo-950">KOP Dokumen LPJ Otomatis:</span> Dokumen LPJ ini otomatis mencantumkan KOP resmi organisasi Anda (Nama Ormawa, Logo, Alamat Kampus, Website, dan Email). Konfigurasi KOP dapat disesuaikan kapan saja di <strong class="text-indigo-900">Halaman Profil</strong>.
                        </div>
                    </div>
                </div>

                <!-- SELECTOR TEMPLATE LPJ (3 KATEGORI) -->
                <div class="mb-8 p-5 bg-gradient-to-br from-slate-50 to-indigo-50/30 rounded-2xl border border-slate-200">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-3">
                        Pilih Kategori Template LPJ:
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                        <!-- Opsi 1: Kegiatan Mahasiswa -->
                        <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition select-none"
                               :class="jenisLpj === 'kegiatan' ? 'border-indigo-600 bg-white shadow-md ring-2 ring-indigo-500/20' : 'border-slate-200 bg-white/70 hover:border-slate-300 hover:bg-white'">
                            <input type="radio" name="jenis_lpj_selector" value="kegiatan" x-model="jenisLpj" class="sr-only">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-2xl">🎪</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-indigo-100 text-indigo-800">Event / Pelatihan</span>
                            </div>
                            <div class="font-bold text-sm text-slate-900">LPJ Kegiatan Mahasiswa</div>
                            <p class="text-[11px] text-slate-500 mt-1 leading-snug">
                                Untuk seminar, workshop, webinar, musyawarah, dan latihan dasar. Dilengkapi rundown acara, metode, dan daftar presensi peserta.
                            </p>
                        </label>

                        <!-- Opsi 2: Pengadaan Barang -->
                        <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition select-none"
                               :class="jenisLpj === 'pengadaan_barang' ? 'border-emerald-600 bg-white shadow-md ring-2 ring-emerald-500/20' : 'border-slate-200 bg-white/70 hover:border-slate-300 hover:bg-white'">
                            <input type="radio" name="jenis_lpj_selector" value="pengadaan_barang" x-model="jenisLpj" class="sr-only">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-2xl">📦</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">Aset &amp; Inventaris</span>
                            </div>
                            <div class="font-bold text-sm text-slate-900">LPJ Pengadaan Barang</div>
                            <p class="text-[11px] text-slate-500 mt-1 leading-snug">
                                Untuk belanja inventaris ormawa &amp; peralatan sekretariat. Dilengkapi tabel spesifikasi barang, BAST serah terima, lokasi simpan, dan SOP aset.
                            </p>
                        </label>

                        <!-- Opsi 3: Pendanaan Lomba -->
                        <label class="relative flex flex-col p-4 rounded-xl border-2 cursor-pointer transition select-none"
                               :class="jenisLpj === 'pendanaan_lomba' ? 'border-amber-600 bg-white shadow-md ring-2 ring-amber-500/20' : 'border-slate-200 bg-white/70 hover:border-slate-300 hover:bg-white'">
                            <input type="radio" name="jenis_lpj_selector" value="pendanaan_lomba" x-model="jenisLpj" class="sr-only">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-2xl">🏆</span>
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-amber-100 text-amber-800">Kompetisi Mahasiswa</span>
                            </div>
                            <div class="font-bold text-sm text-slate-900">LPJ Delegasi Perlombaan</div>
                            <p class="text-[11px] text-slate-500 mt-1 leading-snug">
                                Untuk pengiriman delegasi lomba kampus/nasional. Dilengkapi tabel nama delegasi, tingkat lomba, capaian juara/piala, dan evaluasi tanding.
                            </p>
                        </label>
                    </div>
                </div>

                <form action="{{ route('generator.lpj.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="proposal_id" value="{{ $proposal->id ?? '' }}">
                    <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id ?? '' }}">
                    <input type="hidden" name="jenis_lpj" :value="jenisLpj">

                    <!-- ================= BAGIAN 1: IDENTITAS & WAKTU / PENEMPATAN ================= -->
                    <div class="mb-10">
                        <div class="flex items-center mb-6 pb-2 border-b-2"
                             :class="{
                                'border-indigo-500': jenisLpj === 'kegiatan',
                                'border-emerald-500': jenisLpj === 'pengadaan_barang',
                                'border-amber-500': jenisLpj === 'pendanaan_lomba'
                             }">
                            <span class="text-white w-8 h-8 rounded-full flex items-center justify-center font-bold mr-3 text-sm"
                                  :class="{
                                    'bg-indigo-600': jenisLpj === 'kegiatan',
                                    'bg-emerald-600': jenisLpj === 'pengadaan_barang',
                                    'bg-amber-600': jenisLpj === 'pendanaan_lomba'
                                  }">1</span>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800"
                                    x-text="jenisLpj === 'pengadaan_barang' ? 'Identitas Pengadaan & Tanggal Penerimaan Barang' : (jenisLpj === 'pendanaan_lomba' ? 'Identitas Kompetisi & Delegasi Perlombaan' : 'Identitas & Waktu Kegiatan')">
                                    Identitas &amp; Waktu Kegiatan
                                </h3>
                                <p class="text-xs text-gray-500"
                                   x-text="jenisLpj === 'pengadaan_barang' ? 'Informasi belanja inventaris dan lokasi penempatan aset sesuai format resmi ITG' : (jenisLpj === 'pendanaan_lomba' ? 'Informasi kejuaraan, penyelenggara, dan kontingen delegasi lomba sesuai format resmi ITG' : 'Informasi utama kegiatan sesuai format standar ITG')">
                                    Informasi utama kegiatan sesuai format standar ITG
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Field Nama (Adaptif) -->
                            <div class="md:col-span-2">
                                <label class="block font-bold text-sm text-gray-700 mb-1">
                                    <span x-show="jenisLpj === 'kegiatan'">A. Nama Kegiatan</span>
                                    <span x-show="jenisLpj === 'pengadaan_barang'" style="display: none;">A. Nama Pengadaan / Belanja Inventaris</span>
                                    <span x-show="jenisLpj === 'pendanaan_lomba'" style="display: none;">A. Nama Kejuaraan / Kompetisi Perlombaan</span>
                                    <span class="text-rose-500">*</span>
                                </label>
                                <input id="nama_kegiatan" name="nama_kegiatan" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" required
                                       :placeholder="jenisLpj === 'pengadaan_barang' ? 'Contoh: PENGADAAN PERALATAN MULTIMEDIA & INVENTARIS SEKRETARIAT HIMA 2026' : (jenisLpj === 'pendanaan_lomba' ? 'Contoh: DELEGASI KOMPETISI GEMASTIK XIX - DIVISI PROGRAMMING 2026' : 'Contoh: SEMINAR NASIONAL CYBER SECURITY 2026')"
                                       value="{{ $namaAwal }}" />
                            </div>

                            <div>
                                <x-input-label for="tahun_akademik" :value="__('Tahun Akademik')" class="font-bold text-gray-700" />
                                <x-text-input id="tahun_akademik" name="tahun_akademik" type="text" class="mt-1 block w-full text-sm" required value="{{ $tahunAwal }}" placeholder="Contoh: 2024-2025" />
                            </div>

                            <!-- Bidang Khusus Perlombaan: Penyelenggara & Tingkat -->
                            <div x-show="jenisLpj === 'pendanaan_lomba'" style="display: none;" class="space-y-4 md:col-span-1">
                                <div>
                                    <x-input-label for="penyelenggara_lomba" :value="__('Lembaga Penyelenggara Lomba')" class="font-bold text-gray-700" />
                                    <x-text-input id="penyelenggara_lomba" name="penyelenggara_lomba" type="text" class="mt-1 block w-full text-sm" placeholder="Contoh: Puspresnas / Universitas Indonesia / IEEE" value="Pusat Prestasi Nasional (Puspresnas) & Kemendikbudristek" />
                                </div>
                            </div>

                            <div x-show="jenisLpj === 'pendanaan_lomba'" style="display: none;">
                                <x-input-label for="tingkat_kejuaraan" :value="__('Tingkat Kejuaraan')" class="font-bold text-gray-700" />
                                <select id="tingkat_kejuaraan" name="tingkat_kejuaraan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
                                    <option value="Nasional">Nasional</option>
                                    <option value="Internasional">Internasional</option>
                                    <option value="Regional / Provinsi">Regional / Provinsi</option>
                                    <option value="Kabupaten / Wilayah">Kabupaten / Wilayah</option>
                                    <option value="Antar Perguruan Tinggi">Antar Perguruan Tinggi</option>
                                </select>
                            </div>

                            <!-- Hari / Tanggal (Adaptif) -->
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-1">
                                    <span x-show="jenisLpj === 'kegiatan'">B. Hari &amp; Tanggal Pelaksanaan</span>
                                    <span x-show="jenisLpj === 'pengadaan_barang'" style="display: none;">B. Tanggal Pembelian / Penerimaan Barang</span>
                                    <span x-show="jenisLpj === 'pendanaan_lomba'" style="display: none;">B. Waktu &amp; Tanggal Perlombaan</span>
                                </label>
                                <input id="waktu_hari_tanggal" name="waktu_hari_tanggal" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" required
                                       :placeholder="jenisLpj === 'pengadaan_barang' ? 'Contoh: Senin, 10 November 2026' : (jenisLpj === 'pendanaan_lomba' ? 'Contoh: 12 – 15 Oktober 2026' : 'Contoh: Sabtu, 18 Mei 2026')"
                                       value="Sabtu, 18 Mei 2026" />
                            </div>

                            <!-- Waktu Jam (Hanya Kegiatan) -->
                            <div x-show="jenisLpj === 'kegiatan'">
                                <x-input-label for="waktu_jam" :value="__('Waktu / Jam')" class="font-bold text-gray-700" />
                                <x-text-input id="waktu_jam" name="waktu_jam" type="text" class="mt-1 block w-full text-sm" placeholder="Contoh: 08.00 – 16.00 WIB" value="08.00 – 16.00 WIB" />
                            </div>

                            <!-- Tempat / Lokasi / Penempatan (Adaptif) -->
                            <div>
                                <label class="block font-bold text-sm text-gray-700 mb-1">
                                    <span x-show="jenisLpj === 'kegiatan'">Tempat Pelaksanaan</span>
                                    <span x-show="jenisLpj === 'pengadaan_barang'" style="display: none;">Lokasi Penempatan / Penyimpanan Aset</span>
                                    <span x-show="jenisLpj === 'pendanaan_lomba'" style="display: none;">Tempat / Venue Perlombaan</span>
                                </label>
                                <input id="tempat" name="tempat" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" required
                                       :placeholder="jenisLpj === 'pengadaan_barang' ? 'Contoh: Ruang Sekretariat HIMA Informatika, Gedung D Lantai 2' : (jenisLpj === 'pendanaan_lomba' ? 'Contoh: Gedung Rektorat Kampus UI Depok / Daring via Zoom' : 'Contoh: Aula Gedung D Institut Teknologi Garut')"
                                       value="Aula Gedung D Institut Teknologi Garut" />
                            </div>

                            <!-- Field Tambahan Pengadaan: Penanggung Jawab Barang -->
                            <div x-show="jenisLpj === 'pengadaan_barang'" style="display: none;">
                                <x-input-label for="penanggung_jawab_aset" :value="__('Penanggung Jawab Aset / Divisi Inventaris')" class="font-bold text-gray-700" />
                                <x-text-input id="penanggung_jawab_aset" name="penanggung_jawab_aset" type="text" class="mt-1 block w-full text-sm" placeholder="Contoh: Divisi Rumah Tangga & Inventaris HIMA Informatika" value="Divisi Rumah Tangga & Inventaris HIMA" />
                            </div>

                            <!-- Field Tambahan Lomba: Dosen Pendamping -->
                            <div x-show="jenisLpj === 'pendanaan_lomba'" style="display: none;">
                                <x-input-label for="dosen_pendamping" :value="__('Dosen Pembimbing / Pendamping Delegasi')" class="font-bold text-gray-700" />
                                <x-text-input id="dosen_pendamping" name="dosen_pendamping" type="text" class="mt-1 block w-full text-sm" placeholder="Contoh: Ir. Dosen Pendamping, M.T. (NIDN: 0421099001)" value="Dosen Pembimbing Akademik / Kemahasiswaan ITG" />
                            </div>
                        </div>
                    </div>

                    <!-- ================= BAGIAN 2: BATANG TUBUH LAPORAN ================= -->
                    <div class="mb-10">
                        <div class="flex items-center mb-6 pb-2 border-b-2"
                             :class="{
                                'border-indigo-500': jenisLpj === 'kegiatan',
                                'border-emerald-500': jenisLpj === 'pengadaan_barang',
                                'border-amber-500': jenisLpj === 'pendanaan_lomba'
                             }">
                            <span class="text-white w-8 h-8 rounded-full flex items-center justify-center font-bold mr-3 text-sm"
                                  :class="{
                                    'bg-indigo-600': jenisLpj === 'kegiatan',
                                    'bg-emerald-600': jenisLpj === 'pengadaan_barang',
                                    'bg-amber-600': jenisLpj === 'pendanaan_lomba'
                                  }">2</span>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Batang Tubuh Laporan</h3>
                                <p class="text-xs text-gray-500"
                                   x-text="jenisLpj === 'pengadaan_barang' ? 'Narasi urgensi pengadaan, berita acara serah terima, SOP pemeliharaan aset, dan evaluasi pengadaan' : (jenisLpj === 'pendanaan_lomba' ? 'Narasi tujuan delegasi, tahapan babak kompetisi, hasil kejuaraan/prestasi, dan evaluasi pertandingan' : 'Poin narasi evaluasi, sasaran, metode, dan keberhasilan kegiatan')">
                                    Poin narasi evaluasi, sasaran, metode, dan keberhasilan kegiatan
                                </p>
                            </div>
                        </div>

                        <!-- SUB-BAGIAN 2A: KHUSUS KEGIATAN MAHASISWA -->
                        <div x-show="jenisLpj === 'kegiatan'" class="space-y-6">
                            <div>
                                <x-input-label for="tujuan_kegiatan" :value="__('C. Tujuan Kegiatan')" class="font-bold text-gray-700" />
                                <textarea id="tujuan_kegiatan" name="tujuan_kegiatan" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Kegiatan ini bertujuan untuk meningkatkan pemahaman mahasiswa mengenai...">Kegiatan ini bertujuan untuk memberikan wawasan komprehensif, pemahaman teknis, dan sertifikasi kompetensi dasar kepada mahasiswa di lingkungan Institut Teknologi Garut.</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="sasaran_kegiatan" :value="__('D. Sasaran Kegiatan')" class="font-bold text-gray-700" />
                                    <textarea id="sasaran_kegiatan" name="sasaran_kegiatan" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Sasaran dalam kegiatan ini adalah seluruh mahasiswa tingkat 1-4...">Sasaran dalam kegiatan ini adalah seluruh mahasiswa aktif Institut Teknologi Garut serta perwakilan delegasi ormawa.</textarea>
                                </div>
                                <div>
                                    <x-input-label for="ruang_lingkup" :value="__('Ruang Lingkup Pembahasan')" class="font-bold text-gray-700" />
                                    <textarea id="ruang_lingkup" name="ruang_lingkup" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Adapun ruang lingkup kegiatan ini mencakup pembahasan mengenai...">Adapun ruang lingkup kegiatan ini mencakup pembahasan mengenai:
▪ Penerapan keamanan jaringan dan sistem komputer
▪ Simulasi pertahanan siber dan mitigasi ancaman
▪ Diskusi interaktif dan evaluasi implementasi sistem</textarea>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="metode_kegiatan" :value="__('E. Metode Pendekatan Kegiatan')" class="font-bold text-gray-700" />
                                    <textarea id="metode_kegiatan" name="metode_kegiatan" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">Kegiatan dilakukan berdasarkan pendekatan partisipatif, dimana para peserta dilibatkan secara aktif dalam workshop dan diskusi studi kasus langsung. Narasumber memfasilitasi jalannya praktik dan tanya jawab.</textarea>
                                </div>
                                <div>
                                    <x-input-label for="tahapan_kegiatan" :value="__('Tahapan Kegiatan')" class="font-bold text-gray-700" />
                                    <textarea id="tahapan_kegiatan" name="tahapan_kegiatan" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">Tahapan kegiatannya adalah sebagai berikut:
▪ Tahap 1: Registrasi peserta, pembukaan, dan pre-test materi
▪ Tahap 2: Pemaparan modul materi oleh narasumber utama
▪ Tahap 3: Praktik terbimbing (hands-on) dan diskusi interaktif
▪ Tahap 4: Post-test, evaluasi capaian, dan penutupan</textarea>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="peserta_deskripsi" :value="__('F. Peserta')" class="font-bold text-gray-700" />
                                    <textarea id="peserta_deskripsi" name="peserta_deskripsi" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">Peserta yang hadir dalam kegiatan ini berjumlah 120 orang mahasiswa dari berbagai program studi di ITG, dengan daftar kehadiran terlampir pada dokumen ini.</textarea>
                                </div>
                                <div>
                                    <x-input-label for="indikator_keberhasilan" :value="__('H. Indikator Keberhasilan')" class="font-bold text-gray-700" />
                                    <textarea id="indikator_keberhasilan" name="indikator_keberhasilan" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">Indikator keberhasilan kegiatan adalah tercapainya tingkat kepuasan peserta minimal 85% dan peningkatan pemahaman teknis peserta melalui hasil post-test.</textarea>
                                </div>
                            </div>

                            <div>
                                <x-input-label for="jadwal_kegiatan" :value="__('G. Jadwal Kegiatan (Rundown)')" class="font-bold text-gray-700" />
                                <textarea id="jadwal_kegiatan" name="jadwal_kegiatan" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xs font-mono">08.00 - 08.30  Registrasi Peserta & Pengisian Presensi
08.30 - 09.00  Pembukaan dan Sambutan Ketua Pelaksana serta Ketua Ormawa
09.00 - 12.00  Pemaparan Materi Utama dan Sesi Tanya Jawab
12.00 - 13.00  Istirahat, Sholat, dan Makan (Ishoma)
13.00 - 15.30  Workshop Praktik Terbimbing dan Simulasi Kasus
15.30 - 16.00  Rekomendasi Peningkatan, Evaluasi, dan Penutupan</textarea>
                            </div>
                        </div>

                        <!-- SUB-BAGIAN 2B: KHUSUS PENGADAAN BARANG / INVENTARIS -->
                        <div x-show="jenisLpj === 'pengadaan_barang'" style="display: none;" class="space-y-6">
                            <div>
                                <x-input-label for="urgensi_pengadaan" :value="__('C. Latar Belakang & Urgensi Pengadaan Barang')" class="font-bold text-gray-700" />
                                <textarea id="urgensi_pengadaan" name="urgensi_pengadaan" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" placeholder="Jelaskan urgensi mengapa inventaris ini perlu diadakan...">Pengadaan barang inventaris ini dilaksanakan guna memenuhi kebutuhan mendesak operasional organisasi dan penunjang kegiatan kemahasiswaan. Ketersediaan inventaris ini sangat krusial untuk menggantikan peralatan lama yang telah aus serta mendukung digitalisasi administrasi ormawa di lingkungan Institut Teknologi Garut.</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="pemanfaatan_aset" :value="__('D. Rincian Pemanfaatan & Pengguna Aset')" class="font-bold text-gray-700" />
                                    <textarea id="pemanfaatan_aset" name="pemanfaatan_aset" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">Barang yang dibeli dimanfaatkan secara aktif untuk:
▪ Sarana penunjang kegiatan rapat kerja, pelatihan teknis, dan webinar internal.
▪ Operasional harian kesekretariatan dan pengarsipan berkas fisik ormawa.
▪ Peminjaman resmi untuk divisi kerja dalam menyelenggarakan program kerja.</textarea>
                                </div>
                                <div>
                                    <x-input-label for="bast_klausul" :value="__('E. Berita Acara Penerimaan Barang (BAST)')" class="font-bold text-gray-700" />
                                    <textarea id="bast_klausul" name="bast_klausul" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">Telah diterima dengan baik seluruh barang inventaris yang dibeli dalam keadaan baru, lengkap dengan kartu garansi dan buku petunjuk. Seluruh unit telah melalui uji coba fungsi dan beroperasi normal 100%, serta telah diberi nomor kode register aset ormawa.</textarea>
                                </div>
                            </div>

                            <div>
                                <x-input-label for="sop_pemeliharaan" :value="__('F. SOP Pemeliharaan & Tata Kelola Aset')" class="font-bold text-gray-700" />
                                <textarea id="sop_pemeliharaan" name="sop_pemeliharaan" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">Pemeliharaan inventaris dilakukan berkala oleh Divisi Rumah Tangga/Inventaris setiap bulan. Barang disimpan dalam lemari berkunci di ruang sekretariat dan pemakaian ke luar ruangan wajib mengisi buku log peminjaman sarana.</textarea>
                            </div>
                        </div>

                        <!-- SUB-BAGIAN 2C: KHUSUS PENDANAAN LOMBA / DELEGASI -->
                        <div x-show="jenisLpj === 'pendanaan_lomba'" style="display: none;" class="space-y-6">
                            <div>
                                <x-input-label for="tujuan_lomba" :value="__('C. Tujuan Keikutsertaan Delegasi')" class="font-bold text-gray-700" />
                                <textarea id="tujuan_lomba" name="tujuan_kegiatan" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">Pengiriman delegasi ini bertujuan untuk membawa nama baik almamater Institut Teknologi Garut di kancah kompetisi, mengasah kemampuan teknis dan daya saing mahasiswa di tingkat nasional, serta memperluas jejaring akademik antar-perguruan tinggi se-Indonesia.</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="capaian_prestasi" :value="__('D. Hasil Capaian Prestasi & Kejuaraan')" class="font-bold text-gray-700" />
                                    <textarea id="capaian_prestasi" name="capaian_prestasi" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm font-semibold text-emerald-900 bg-emerald-50/50">Capaian Prestasi yang Diraih:
🏆 JUARA II (MEDALI PERAK) - Kategori Programming Competition
▪ Nilai Akhir / Skor: 94.50
▪ Sertifikat & Piala Penghargaan Resmi terlampir pada dokumen laporan ini.</textarea>
                                </div>
                                <div>
                                    <x-input-label for="tahapan_seleksi" :value="__('E. Tahapan Babak yang Dilalui')" class="font-bold text-gray-700" />
                                    <textarea id="tahapan_seleksi" name="tahapan_seleksi" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">Tahapan kompetisi yang dilalui delegasi:
▪ Tahap 1: Seleksi administrasi dan submit berkas portofolio karya
▪ Tahap 2: Babak penyisihan daring (Online Preliminary Round)
▪ Tahap 3: Babak semifinal dan problem-solving langsung
▪ Tahap 4: Babak grand final, presentasi karya, dan penganugerahan juara</textarea>
                                </div>
                            </div>

                            <div>
                                <x-input-label for="evaluasi_lomba" :value="__('F. Evaluasi Pertandingan & Pengalaman Delegasi')" class="font-bold text-gray-700" />
                                <textarea id="evaluasi_lomba" name="evaluasi_lomba" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">Secara umum delegasi mampu bersaing secara tangguh menghadapi peserta dari berbagai PTN/PTS terkemuka. Penguasaan algoritma dan ketepatan waktu delegasi ITG dinilai sangat solid oleh dewan juri. Diperlukan penambahan simulasi mock-competition untuk delegasi di kompetisi mendatang.</textarea>
                            </div>
                        </div>

                        <!-- HAMBATAN, UPAYA, & PENUTUP (BERLAKU UNTUK SEMUA) -->
                        <div class="mt-6 space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="hambatan" :value="__('Hambatan / Kendala yang Dihadapi')" class="font-bold text-gray-700" />
                                    <textarea id="hambatan" name="hambatan" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">- Penyesuaian jadwal pelaksanaan dengan agenda perkuliahan.
- Koordinasi teknis dengan pihak vendor / panitia eksternal memerlukan waktu verifikasi tambahan.</textarea>
                                </div>
                                <div>
                                    <x-input-label for="upaya_mengatasi" :value="__('Upaya Mengatasi Hambatan')" class="font-bold text-gray-700" />
                                    <textarea id="upaya_mengatasi" name="upaya_mengatasi" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">- Dilakukan penyesuaian jadwal dispensasi akademik melalui rekomendasi biro kemahasiswaan.
- Dibentuk tim komunikasi khusus untuk memantau status secara langsung.</textarea>
                                </div>
                            </div>

                            <div>
                                <x-input-label for="penutup" :value="__('Penutup Laporan')" class="font-bold text-gray-700" />
                                <textarea id="penutup" name="penutup" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">Demikian laporan pertanggungjawaban ini dibuat dengan sebenarnya dengan penuh rasa tanggung jawab sebagai wujud transparansi pelaksanaan dan akuntabilitas demi kemajuan Institut Teknologi Garut.</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- ================= BAGIAN 3A: TABEL SPESIFIKASI BARANG (KHUSUS PENGADAAN BARANG) ================= -->
                    <div x-show="jenisLpj === 'pengadaan_barang'" style="display: none;" class="mb-10" x-data="barangHandler()">
                        <div class="flex items-center justify-between mb-6 pb-2 border-b-2 border-emerald-500">
                            <div class="flex items-center">
                                <span class="bg-emerald-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold mr-3 text-sm">3</span>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">Rincian Spesifikasi &amp; Kondisi Barang yang Dibeli</h3>
                                    <p class="text-xs text-gray-500">Tabel inventarisasi fisik barang, merk/tipe, jumlah unit, dan kondisi kelayakan</p>
                                </div>
                            </div>
                            <button type="button" @click="addRow()" class="inline-flex items-center text-xs bg-emerald-50 text-emerald-700 border border-emerald-300 px-3 py-1.5 rounded-lg hover:bg-emerald-100 font-semibold transition">
                                + Tambah Barang
                            </button>
                        </div>

                        <div class="overflow-x-auto border rounded-xl shadow-2xs">
                            <table class="min-w-full divide-y divide-gray-200 text-xs">
                                <thead class="bg-emerald-50 font-bold text-emerald-950 uppercase">
                                    <tr>
                                        <th class="p-2 text-center w-10">No</th>
                                        <th class="p-2 text-left">Nama Barang / Alat</th>
                                        <th class="p-2 text-left w-48">Merk / Tipe / Spesifikasi</th>
                                        <th class="p-2 text-center w-28">Jumlah (Unit)</th>
                                        <th class="p-2 text-left w-36">Kondisi Fisik</th>
                                        <th class="p-2 text-left w-40">Lokasi Simpan</th>
                                        <th class="p-2 text-center w-12">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(item, idx) in items" :key="idx">
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="p-2 text-center font-bold text-gray-500" x-text="idx + 1"></td>
                                            <td class="p-1.5">
                                                <input type="text" :name="'barang_items[' + idx + '][nama]'" x-model="item.nama" placeholder="Contoh: Proyektor Mini Portable" class="w-full text-xs border-gray-300 rounded p-1">
                                            </td>
                                            <td class="p-1.5">
                                                <input type="text" :name="'barang_items[' + idx + '][spesifikasi]'" x-model="item.spesifikasi" placeholder="Contoh: Epson EB-E500 3300 Lumens" class="w-full text-xs border-gray-300 rounded p-1">
                                            </td>
                                            <td class="p-1.5">
                                                <input type="text" :name="'barang_items[' + idx + '][jumlah]'" x-model="item.jumlah" placeholder="1 Unit" class="w-full text-xs text-center border-gray-300 rounded p-1">
                                            </td>
                                            <td class="p-1.5">
                                                <input type="text" :name="'barang_items[' + idx + '][kondisi]'" x-model="item.kondisi" placeholder="Baik / Baru 100%" class="w-full text-xs border-gray-300 rounded p-1">
                                            </td>
                                            <td class="p-1.5">
                                                <input type="text" :name="'barang_items[' + idx + '][lokasi]'" x-model="item.lokasi" placeholder="Sekretariat Gedung D" class="w-full text-xs border-gray-300 rounded p-1">
                                            </td>
                                            <td class="p-1.5 text-center">
                                                <button type="button" @click="removeRow(idx)" class="text-rose-500 font-bold hover:text-rose-700">&times;</button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ================= BAGIAN 3B: TABEL DELEGASI MAHASISWA (KHUSUS PENDANAAN LOMBA) ================= -->
                    <div x-show="jenisLpj === 'pendanaan_lomba'" style="display: none;" class="mb-10" x-data="delegasiHandler()">
                        <div class="flex items-center justify-between mb-6 pb-2 border-b-2 border-amber-500">
                            <div class="flex items-center">
                                <span class="bg-amber-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold mr-3 text-sm">3</span>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">Daftar Kontingen &amp; Delegasi Mahasiswa</h3>
                                    <p class="text-xs text-gray-500">Daftar nama mahasiswa yang diberangkatkan sebagai delegasi resmi ITG</p>
                                </div>
                            </div>
                            <button type="button" @click="addRow()" class="inline-flex items-center text-xs bg-amber-50 text-amber-700 border border-amber-300 px-3 py-1.5 rounded-lg hover:bg-amber-100 font-semibold transition">
                                + Tambah Delegasi
                            </button>
                        </div>

                        <div class="overflow-x-auto border rounded-xl shadow-2xs">
                            <table class="min-w-full divide-y divide-gray-200 text-xs">
                                <thead class="bg-amber-50 font-bold text-amber-950 uppercase">
                                    <tr>
                                        <th class="p-2 text-center w-10">No</th>
                                        <th class="p-2 text-left">Nama Lengkap Mahasiswa</th>
                                        <th class="p-2 text-left w-36">NIM</th>
                                        <th class="p-2 text-left w-48">Program Studi</th>
                                        <th class="p-2 text-left w-40">Peran dalam Tim</th>
                                        <th class="p-2 text-center w-12">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(delegasi, idx) in items" :key="idx">
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="p-2 text-center font-bold text-gray-500" x-text="idx + 1"></td>
                                            <td class="p-1.5">
                                                <input type="text" :name="'delegasi_items[' + idx + '][nama]'" x-model="delegasi.nama" placeholder="Nama Mahasiswa..." class="w-full text-xs border-gray-300 rounded p-1">
                                            </td>
                                            <td class="p-1.5">
                                                <input type="text" :name="'delegasi_items[' + idx + '][nim]'" x-model="delegasi.nim" placeholder="2206001" class="w-full text-xs border-gray-300 rounded p-1 font-mono">
                                            </td>
                                            <td class="p-1.5">
                                                <input type="text" :name="'delegasi_items[' + idx + '][prodi]'" x-model="delegasi.prodi" placeholder="Informatika" class="w-full text-xs border-gray-300 rounded p-1">
                                            </td>
                                            <td class="p-1.5">
                                                <input type="text" :name="'delegasi_items[' + idx + '][peran]'" x-model="delegasi.peran" placeholder="Ketua Tim / Anggota" class="w-full text-xs border-gray-300 rounded p-1">
                                            </td>
                                            <td class="p-1.5 text-center">
                                                <button type="button" @click="removeRow(idx)" class="text-rose-500 font-bold hover:text-rose-700">&times;</button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ================= BAGIAN 4: LAMPIRAN LAPORAN KEUANGAN (6 KOLOM RESMI) ================= -->
                    <div class="mb-10" x-data="keuanganHandler()">
                        <div class="flex items-center justify-between mb-6 pb-2 border-b-2"
                             :class="{
                                'border-indigo-500': jenisLpj === 'kegiatan',
                                'border-emerald-500': jenisLpj === 'pengadaan_barang',
                                'border-amber-500': jenisLpj === 'pendanaan_lomba'
                             }">
                            <div class="flex items-center">
                                <span class="text-white w-8 h-8 rounded-full flex items-center justify-center font-bold mr-3 text-sm"
                                      :class="{
                                        'bg-indigo-600': jenisLpj === 'kegiatan',
                                        'bg-emerald-600': jenisLpj === 'pengadaan_barang',
                                        'bg-amber-600': jenisLpj === 'pendanaan_lomba'
                                      }">4</span>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">Lampiran Laporan Keuangan (6 Kolom Resmi)</h3>
                                    <p class="text-xs text-gray-500">Pencatatan tanggal, jenis kebutuhan belanja/kuitansi, dana diterima, pengeluaran, dan saldo sisa</p>
                                </div>
                            </div>
                            <button type="button" @click="addRow()" class="inline-flex items-center text-xs bg-slate-50 text-slate-700 border border-slate-300 px-3 py-1.5 rounded-lg hover:bg-slate-100 font-semibold transition">
                                + Tambah Baris Transaksi
                            </button>
                        </div>

                        <div class="overflow-x-auto border rounded-xl shadow-2xs">
                            <table class="min-w-full divide-y divide-gray-200 text-xs">
                                <thead class="bg-gray-100 font-bold text-gray-700 uppercase">
                                    <tr>
                                        <th class="p-2 text-left w-32">Tanggal</th>
                                        <th class="p-2 text-left">Jenis Kebutuhan</th>
                                        <th class="p-2 text-right w-36">Pemasukan (Rp)</th>
                                        <th class="p-2 text-right w-36">Pengeluaran (Rp)</th>
                                        <th class="p-2 text-right w-36">Sisa (Rp)</th>
                                        <th class="p-2 text-left w-44">Keterangan</th>
                                        <th class="p-2 text-center w-12">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(row, idx) in items" :key="idx">
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="p-1.5">
                                                <input type="text" :name="'keuangan_items[' + idx + '][tanggal]'" x-model="row.tanggal" placeholder="14 Mei 2026" class="w-full text-xs border-gray-300 rounded p-1">
                                            </td>
                                            <td class="p-1.5">
                                                <input type="text" :name="'keuangan_items[' + idx + '][kebutuhan]'" x-model="row.kebutuhan" placeholder="Anggaran Kemahasiswaan / Pembelian Barang / Registrasi" class="w-full text-xs border-gray-300 rounded p-1">
                                            </td>
                                            <td class="p-1.5">
                                                <input type="number" :name="'keuangan_items[' + idx + '][pemasukan]'" x-model.number="row.pemasukan" @input="recalc()" class="w-full text-xs text-right border-gray-300 rounded p-1">
                                            </td>
                                            <td class="p-1.5">
                                                <input type="number" :name="'keuangan_items[' + idx + '][pengeluaran]'" x-model.number="row.pengeluaran" @input="recalc()" class="w-full text-xs text-right border-gray-300 rounded p-1">
                                            </td>
                                            <td class="p-1.5">
                                                <input type="number" :name="'keuangan_items[' + idx + '][sisa]'" :value="row.sisa" readonly class="w-full text-xs text-right bg-gray-100 border-gray-200 rounded p-1 font-semibold text-gray-700">
                                            </td>
                                            <td class="p-1.5">
                                                <input type="text" :name="'keuangan_items[' + idx + '][keterangan]'" x-model="row.keterangan" placeholder="Kuitansi / Faktur Toko No..." class="w-full text-xs border-gray-300 rounded p-1">
                                            </td>
                                            <td class="p-1.5 text-center">
                                                <button type="button" @click="removeRow(idx)" class="text-rose-500 font-bold hover:text-rose-700">&times;</button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                                <tfoot class="bg-gray-50 font-bold text-xs text-gray-800">
                                    <tr>
                                        <td colspan="2" class="p-2 text-right">TOTAL KESELURUHAN:</td>
                                        <td class="p-2 text-right text-indigo-700" x-text="formatRupiah(totalPemasukan)"></td>
                                        <td class="p-2 text-right text-rose-700" x-text="formatRupiah(totalPengeluaran)"></td>
                                        <td class="p-2 text-right text-emerald-700 font-extrabold" x-text="formatRupiah(totalSisa)"></td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- ================= BAGIAN 5: LAMPIRAN DAFTAR HADIR (HANYA UNTUK KEGIATAN) ================= -->
                    <div x-show="jenisLpj === 'kegiatan'" class="mb-10" x-data="hadirHandler()">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6 pb-2 border-b-2 border-indigo-500">
                            <div class="flex items-center">
                                <span class="bg-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold mr-3 text-sm">5</span>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">Lampiran Daftar Hadir Peserta Kegiatan</h3>
                                    <p class="text-xs text-gray-500">Tabel presensi peserta untuk dicetak pada lembar lampiran terpisah</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="button" @click="showPasteModal = true" class="inline-flex items-center text-xs bg-emerald-50 text-emerald-700 border border-emerald-300 px-3 py-1.5 rounded-lg hover:bg-emerald-100 font-semibold transition">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                                    Paste dari Excel / Spreadsheet
                                </button>
                                <button type="button" @click="addRow()" class="inline-flex items-center text-xs bg-indigo-50 text-indigo-700 border border-indigo-200 px-3 py-1.5 rounded-lg hover:bg-indigo-100 font-semibold transition">
                                    + Tambah Peserta
                                </button>
                            </div>
                        </div>

                        <!-- Modal Paste Excel -->
                        <div x-show="showPasteModal" class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4" style="display: none;" @keydown.escape.window="showPasteModal = false">
                            <div class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl border border-slate-200 space-y-4" @click.away="showPasteModal = false">
                                <div class="flex items-center justify-between border-b pb-3">
                                    <h4 class="font-bold text-slate-800 text-base">Paste Data Peserta dari Excel / Google Sheets</h4>
                                    <button type="button" @click="showPasteModal = false" class="text-slate-400 hover:text-slate-600 text-lg font-bold">&times;</button>
                                </div>
                                <div class="text-xs text-slate-600 space-y-1">
                                    <p>Salin (Copy) tabel peserta dari Excel atau Spreadsheet Anda, lalu tempel (Paste) pada kotak di bawah.</p>
                                    <p class="text-slate-500 font-mono text-[11px]">Format didukung: <strong>[Nama] [Tab] [Jabatan/Instansi] [Tab] [No HP]</strong></p>
                                </div>
                                <textarea x-model="pasteText" rows="6" placeholder="Contoh salinan Excel:&#10;Muhammad Rasyid	Ketua HIMA	085712345678&#10;Aisyah Zahra	Peserta	081234567890" class="w-full text-xs font-mono border-slate-300 rounded-lg p-3"></textarea>
                                <div class="flex justify-end gap-2 pt-2">
                                    <button type="button" @click="showPasteModal = false" class="px-4 py-2 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg">Batal</button>
                                    <button type="button" @click="parseAndImport()" class="px-4 py-2 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg shadow-xs">Impor ke Tabel</button>
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto border rounded-xl shadow-2xs">
                            <table class="min-w-full divide-y divide-gray-200 text-xs">
                                <thead class="bg-gray-100 font-bold text-gray-700 uppercase">
                                    <tr>
                                        <th class="p-2 text-center w-12">No</th>
                                        <th class="p-2 text-left">Nama Lengkap</th>
                                        <th class="p-2 text-left">Jabatan / Instansi</th>
                                        <th class="p-2 text-left w-36">No HP</th>
                                        <th class="p-2 text-center w-12">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(peserta, idx) in items" :key="idx">
                                        <tr class="border-b hover:bg-gray-50">
                                            <td class="p-2 text-center font-bold text-gray-500" x-text="idx + 1"></td>
                                            <td class="p-1.5">
                                                <input type="text" :name="'daftar_hadir_items[' + idx + '][nama]'" x-model="peserta.nama" placeholder="Nama Peserta..." class="w-full text-xs border-gray-300 rounded p-1">
                                            </td>
                                            <td class="p-1.5">
                                                <input type="text" :name="'daftar_hadir_items[' + idx + '][jabatan]'" x-model="peserta.jabatan" placeholder="Mahasiswa / Dosen / Tamu" class="w-full text-xs border-gray-300 rounded p-1">
                                            </td>
                                            <td class="p-1.5">
                                                <input type="text" :name="'daftar_hadir_items[' + idx + '][no_hp]'" x-model="peserta.no_hp" placeholder="0812xxxx" class="w-full text-xs border-gray-300 rounded p-1">
                                            </td>
                                            <td class="p-1.5 text-center">
                                                <button type="button" @click="removeRow(idx)" class="text-rose-500 font-bold hover:text-rose-700">&times;</button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- ================= BAGIAN 6: UPLOAD DOKUMENTASI, BUKTI STRUK, & PIAGAM ================= -->
                    <div class="mb-10">
                        <div class="flex items-center mb-6 pb-2 border-b-2"
                             :class="{
                                'border-indigo-500': jenisLpj === 'kegiatan',
                                'border-emerald-500': jenisLpj === 'pengadaan_barang',
                                'border-amber-500': jenisLpj === 'pendanaan_lomba'
                             }">
                            <span class="text-white w-8 h-8 rounded-full flex items-center justify-center font-bold mr-3 text-sm"
                                  :class="{
                                    'bg-indigo-600': jenisLpj === 'kegiatan',
                                    'bg-emerald-600': jenisLpj === 'pengadaan_barang',
                                    'bg-amber-600': jenisLpj === 'pendanaan_lomba'
                                  }"
                                  x-text="jenisLpj === 'kegiatan' ? '6' : '5'">6</span>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800"
                                    x-text="jenisLpj === 'pengadaan_barang' ? 'Lampiran Foto Fisik Barang & Faktur/Nota Toko Asli' : (jenisLpj === 'pendanaan_lomba' ? 'Lampiran Foto Kompetisi, Trophy, Scan Sertifikat & Tiket/Hotel' : 'Lampiran Foto Dokumentasi, Struk & Berkas PDF')">
                                    Lampiran Foto Dokumentasi, Struk &amp; Berkas PDF
                                </h3>
                                <p class="text-xs text-gray-500">Unggah bukti foto visual, nota kuitansi asli, serta berkas PDF tambahan opsional</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Foto Dokumentasi (Adaptif) -->
                            <div class="p-5 rounded-xl border border-dashed"
                                 :class="jenisLpj === 'pengadaan_barang' ? 'bg-emerald-50/50 border-emerald-300' : (jenisLpj === 'pendanaan_lomba' ? 'bg-amber-50/50 border-amber-300' : 'bg-indigo-50/50 border-indigo-300')">
                                <label class="block font-bold text-xs uppercase tracking-wide mb-1"
                                       :class="jenisLpj === 'pengadaan_barang' ? 'text-emerald-950' : (jenisLpj === 'pendanaan_lomba' ? 'text-amber-950' : 'text-indigo-950')">
                                    <span x-show="jenisLpj === 'kegiatan'">Upload Foto Dokumentasi Kegiatan</span>
                                    <span x-show="jenisLpj === 'pengadaan_barang'" style="display: none;">Upload Foto Fisik Barang &amp; Penempatan di Sekretariat</span>
                                    <span x-show="jenisLpj === 'pendanaan_lomba'" style="display: none;">Upload Foto Saat Bertanding &amp; Penyerahan Trophy / Medali</span>
                                </label>
                                <input id="foto_dokumentasi" name="foto_dokumentasi[]" type="file" multiple accept="image/*" class="mt-2 block w-full text-xs text-slate-600 file:mr-4 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-800 file:text-white hover:file:bg-slate-700" />
                                <p class="mt-2 text-[11px] text-slate-500">Format gambar JPEG, PNG. Seluruh foto otomatis ditata ke dalam lembar kisi berbingkai pada PDF.</p>
                            </div>

                            <!-- Bukti Struk / Kuitansi / Faktur / Piagam -->
                            <div class="p-5 bg-emerald-50/50 rounded-xl border border-dashed border-emerald-300">
                                <label class="block font-bold text-xs uppercase tracking-wide text-emerald-950 mb-1">
                                    <span x-show="jenisLpj === 'kegiatan'">Upload Bukti Struk / Kuitansi / Nota</span>
                                    <span x-show="jenisLpj === 'pengadaan_barang'" style="display: none;">Upload Faktur / Nota Toko Asli &amp; Kartu Garansi</span>
                                    <span x-show="jenisLpj === 'pendanaan_lomba'" style="display: none;">Upload Scan Sertifikat / Piagam Juara &amp; Struk Tiket/Hotel</span>
                                </label>
                                <input id="bukti_pembayaran" name="bukti_pembayaran[]" type="file" multiple accept="image/*,.pdf" class="mt-2 block w-full text-xs text-slate-600 file:mr-4 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-700 file:text-white hover:file:bg-emerald-800" />
                                <p class="mt-2 text-[11px] text-slate-500">Format JPEG, PNG, atau PDF. Seluruh bukti akan disandingkan di lembar Laporan Keuangan.</p>
                            </div>

                            <!-- PDF Lampiran Tambahan -->
                            <div class="md:col-span-2 p-5 bg-slate-50 rounded-xl border border-dashed border-slate-300">
                                <x-input-label for="file_lampiran_pdf" :value="__('Upload Berkas PDF Tambahan untuk Digabungkan / Auto Merge (Opsional)')" class="font-bold text-slate-800" />
                                <input id="file_lampiran_pdf" name="file_lampiran_pdf" type="file" accept="application/pdf" class="mt-2 block w-full text-xs text-slate-600 file:mr-4 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-700 file:text-white hover:file:bg-slate-800" />
                                <p class="mt-2 text-[11px] text-slate-500">Format PDF maks 10MB. Jika diunggah, seluruh halaman dokumen PDF ini akan otomatis digabungkan (merge) di akhir lembar LPJ resmi.</p>
                            </div>
                        </div>
                    </div>

                    <!-- ================= BAGIAN 7: SUSUNAN PENANDATANGAN DOKUMEN ================= -->
                    <div class="mb-10 p-6 bg-slate-50 rounded-2xl border border-slate-200"
                         x-data="{
                            signers: [
                                { jenis: 'internal', role: 'ormawa', nama: '{{ addslashes(Auth::user()->nama_ketua ?? Auth::user()->name) }}', jabatan: 'Ketua Ormawa', nim: '{{ addslashes(Auth::user()->nim_ketua ?? '') }}' },
                                { jenis: 'internal', role: 'pelaksana', nama: '{{ addslashes(Auth::user()->nama_sekretaris ?? Auth::user()->name) }}', jabatan: 'Ketua Pelaksana', nim: '{{ addslashes(Auth::user()->nim_sekretaris ?? '') }}' },
                                { jenis: 'eksternal', role: 'bkhm', nama: 'Encep Jianul Hayat, M.Kom.', jabatan: 'Kepala BKHM', nim: '0419089201' },
                                { jenis: 'eksternal', role: 'wr3', nama: 'Dr. Ayu Latifah, S.T., M.T.', jabatan: 'Wakil Rektor III', nim: '0421099301' }
                            ],
                            updateJabatanPelaksana() {
                                if (this.jenisLpj === 'pengadaan_barang') {
                                    this.signers[1].jabatan = 'Penanggung Jawab Inventaris';
                                } else if (this.jenisLpj === 'pendanaan_lomba') {
                                    this.signers[1].jabatan = 'Ketua Tim Delegasi';
                                } else {
                                    this.signers[1].jabatan = 'Ketua Pelaksana';
                                }
                            }
                         }"
                         x-init="$watch('jenisLpj', () => updateJabatanPelaksana()); updateJabatanPelaksana();">
                        
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 20h16"/></svg>
                            <h3 class="text-base font-bold text-slate-800">Susunan Penandatangan Dokumen (Standar ITG)</h3>
                        </div>
                        <p class="text-xs text-slate-500 mb-6">Secara default disesuaikan dengan template resmi ITG: Ketua Ormawa &amp; Penanggung Jawab Pelaksana (isi NIM), serta Mengetahui Kepala BKHM dan Wakil Rektor III (NIDN).</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <template x-for="(signer, idx) in signers" :key="idx">
                                <div class="p-3.5 bg-white rounded-xl border border-slate-200 shadow-2xs">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-bold text-indigo-700" x-text="(idx < 2 ? 'Pengaju: ' : 'Mengetahui: ') + signer.jabatan"></span>
                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600" x-text="signer.jenis === 'internal' ? 'Digital QR Ormawa' : 'Pejabat ITG'"></span>
                                    </div>
                                    <input type="hidden" :name="'penandatangan_jenis[' + idx + ']'" :value="signer.jenis">
                                    <input type="hidden" :name="'penandatangan_role[' + idx + ']'" :value="signer.role">
                                    <div class="space-y-2">
                                        <div>
                                            <label class="block text-[11px] font-medium text-slate-500">Jabatan Penandatangan</label>
                                            <input type="text" :name="'penandatangan_jabatan[' + idx + ']'" x-model="signer.jabatan" class="w-full text-xs border-slate-300 rounded p-1.5 font-medium">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-medium text-slate-500">Nama Lengkap &amp; Gelar</label>
                                            <input type="text" :name="'penandatangan_nama[' + idx + ']'" x-model="signer.nama" class="w-full text-xs border-slate-300 rounded p-1.5 font-semibold">
                                        </div>
                                        <div>
                                            <label class="block text-[11px] font-medium text-slate-500" x-text="idx < 2 ? 'Nomor Induk Mahasiswa (NIM)' : 'Nomor Induk Dosen (NIDN)'"></label>
                                            <input type="text" :name="'penandatangan_nim[' + idx + ']'" x-model="signer.nim" :placeholder="idx < 2 ? 'Contoh: 2106001' : 'Contoh: 0421099301'" class="w-full text-xs border-slate-300 rounded p-1.5 font-mono">
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="flex justify-end gap-3 mt-8 pt-4 border-t border-slate-200">
                        <x-secondary-button type="submit" name="action" value="draft" class="px-6 py-2.5 text-xs font-bold">
                            Simpan Draft LPJ
                        </x-secondary-button>
                        <x-primary-button type="submit" name="action" value="print" class="px-8 py-3 text-sm font-bold shadow-md">
                            Simpan &amp; Lihat Preview LPJ
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function keuanganHandler() {
            return {
                items: [
                    { tanggal: '14 Mei 2026', kebutuhan: 'Anggaran Kemahasiswaan ITG (Penerimaan Dana)', pemasukan: 1000000, pengeluaran: 0, sisa: 1000000, keterangan: 'Kuitansi Terlampir' },
                    { tanggal: '18 Mei 2026', kebutuhan: 'Realisasi Pembelian / Belanja Kebutuhan', pemasukan: 0, pengeluaran: 850000, sisa: 150000, keterangan: 'Faktur / Struk Terlampir' }
                ],
                totalPemasukan: 1000000,
                totalPengeluaran: 850000,
                totalSisa: 150000,
                addRow() {
                    let lastSisa = this.items.length > 0 ? this.items[this.items.length - 1].sisa : 0;
                    this.items.push({
                        tanggal: '',
                        kebutuhan: '',
                        pemasukan: 0,
                        pengeluaran: 0,
                        sisa: lastSisa,
                        keterangan: 'Kuitansi / Faktur Terlampir'
                    });
                    this.recalc();
                },
                removeRow(idx) {
                    if (this.items.length > 1) {
                        this.items.splice(idx, 1);
                        this.recalc();
                    }
                },
                recalc() {
                    let runningSisa = 0;
                    let sumIn = 0;
                    let sumOut = 0;

                    this.items.forEach(row => {
                        let pIn = Number(row.pemasukan) || 0;
                        let pOut = Number(row.pengeluaran) || 0;
                        runningSisa += (pIn - pOut);
                        row.sisa = runningSisa;
                        sumIn += pIn;
                        sumOut += pOut;
                    });

                    this.totalPemasukan = sumIn;
                    this.totalPengeluaran = sumOut;
                    this.totalSisa = runningSisa;
                },
                formatRupiah(num) {
                    return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
                }
            };
        }

        function barangHandler() {
            return {
                items: [
                    { nama: 'Proyektor Mini Portable & Layar Tripod', spesifikasi: 'Epson EB-E500 3300 Lumens + Tripod 70 inch', jumlah: '1 Set', kondisi: 'Baik / Baru 100%', lokasi: 'Ruang Sekretariat HIMA' },
                    { nama: 'Mic Wireless Clip-On Dual Channel', spesifikasi: 'Boya BY-M1V2 Wireless Microphone', jumlah: '1 Unit', kondisi: 'Baik / Berfungsi Normal', lokasi: 'Lemari Inventaris Divisi Multimedia' }
                ],
                addRow() {
                    this.items.push({ nama: '', spesifikasi: '', jumlah: '1 Unit', kondisi: 'Baik / Baru 100%', lokasi: 'Sekretariat' });
                },
                removeRow(idx) {
                    if (this.items.length > 1) {
                        this.items.splice(idx, 1);
                    }
                }
            };
        }

        function delegasiHandler() {
            return {
                items: [
                    { nama: 'Muhammad Rasyid Ridha', nim: '2206001', prodi: 'Informatika', peran: 'Ketua Tim / Delegasi Utama' },
                    { nama: 'Aisyah Putri Azzahra', nim: '2206002', prodi: 'Informatika', peran: 'Anggota 1' },
                    { nama: 'Fajar Nugraha', nim: '2206003', prodi: 'Sistem Informasi', peran: 'Anggota 2' }
                ],
                addRow() {
                    this.items.push({ nama: '', nim: '', prodi: '', peran: 'Anggota Tim' });
                },
                removeRow(idx) {
                    if (this.items.length > 1) {
                        this.items.splice(idx, 1);
                    }
                }
            };
        }

        function hadirHandler() {
            return {
                showPasteModal: false,
                pasteText: '',
                items: [
                    { nama: 'Ahmad Fauzi, S.Kom.', jabatan: 'Dosen Pembimbing ITG', no_hp: '081234567890' },
                    { nama: 'Muhammad Rasyid', jabatan: 'Ketua HIMA Informatika', no_hp: '085712345678' }
                ],
                addRow() {
                    this.items.push({ nama: '', jabatan: '', no_hp: '' });
                },
                removeRow(idx) {
                    if (this.items.length > 1) {
                        this.items.splice(idx, 1);
                    }
                },
                parseAndImport() {
                    if (!this.pasteText.trim()) return;
                    const lines = this.pasteText.trim().split(/\r?\n/);
                    const parsed = [];
                    lines.forEach(line => {
                        if (!line.trim()) return;
                        let cols = line.split('\t');
                        if (cols.length === 1 && line.includes(';')) {
                            cols = line.split(';');
                        }
                        cols = cols.map(c => c.trim());
                        if (cols.length === 0) return;

                        let nama = '';
                        let jabatan = '';
                        let no_hp = '';

                        if (/^\d+$/.test(cols[0]) && cols.length > 1) {
                            nama = cols[1] || '';
                            jabatan = cols[2] || 'Peserta';
                            no_hp = cols[3] || '';
                        } else {
                            nama = cols[0] || '';
                            jabatan = cols[1] || 'Peserta';
                            no_hp = cols[2] || '';
                        }

                        if (nama && !/^nama/i.test(nama) && !/^no\b/i.test(nama)) {
                            parsed.push({
                                nama: nama,
                                jabatan: jabatan || 'Peserta',
                                no_hp: no_hp
                            });
                        }
                    });

                    if (parsed.length > 0) {
                        if (this.items.length === 2 && this.items[0].nama.includes('Ahmad Fauzi')) {
                            this.items = parsed;
                        } else {
                            this.items = this.items.concat(parsed);
                        }
                        this.pasteText = '';
                        this.showPasteModal = false;
                    }
                }
            };
        }
    </script>
</x-app-layout>
