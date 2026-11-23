<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pembuatan Proposal Otomatis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-8">
                
                <!-- Breadcrumbs -->
                <div class="flex items-center text-sm text-gray-500 mb-8">
                    <a href="{{ route('dashboard') }}" class="hover:text-indigo-600">Dashboard</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-800 font-medium">Buat Proposal</span>
                </div>

                <!-- Informasi Konfigurasi KOP Profil -->
                <div class="mb-6 p-4 rounded-2xl bg-indigo-50/70 border border-indigo-200/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
                    <div class="flex items-start gap-2.5">
                        <svg class="w-5 h-5 text-indigo-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <div class="text-slate-700 leading-relaxed">
                            <span class="font-bold text-indigo-950">KOP Proposal Otomatis:</span> Dokumen ini akan otomatis mencantumkan KOP resmi organisasi Anda (Nama Ormawa, Logo, Alamat Kampus, Website, dan Email). Konfigurasi KOP dapat disesuaikan kapan saja di <strong class="text-indigo-900">Halaman Profil</strong>.
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}" target="_blank" class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-indigo-300 hover:bg-indigo-50 text-indigo-700 font-semibold rounded-lg shadow-sm transition">
                        <span>Atur KOP di Profil</span> &rarr;
                    </a>
                </div>

                <form action="{{ route('generator.store') }}" method="POST" id="proposal-form">
                    @csrf
                    <input type="hidden" name="jenis_proposal" id="jenis_proposal" value="kegiatan">

                    <!-- Pilihan Jenis Proposal / Template Cepat -->
                    <div class="mb-10 p-5 rounded-2xl bg-slate-50 border border-slate-200 shadow-2xs">
                        <div class="flex items-center justify-between flex-wrap gap-2 mb-3">
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 flex items-center gap-1.5">
                                    <span>📑</span> Pilih Jenis Proposal &amp; Template Otomatis
                                </h3>
                                <p class="text-xs text-slate-500">Pilih jenis permohonan dana untuk mengisi template narasi, tabel RAB, dan struktur kepengurusan secara instan.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <button type="button" onclick="selectProposalType('kegiatan')" id="btn-type-kegiatan"
                                    class="p-4 rounded-xl border-2 text-left transition flex flex-col justify-between border-indigo-600 bg-indigo-50/70 shadow-xs">
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xl">🎪</span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-indigo-100 text-indigo-800">Event</span>
                                    </div>
                                    <div class="font-bold text-sm text-slate-900">Kegiatan Mahasiswa</div>
                                    <p class="text-xs text-slate-600 mt-1">Seminar, workshop, turnamen, baksos, atau latihan dasar organisasi.</p>
                                </div>
                                <span class="text-[11px] font-semibold text-indigo-700 mt-3 inline-flex items-center gap-1">
                                    <span>Gunakan Template Ini</span> &rarr;
                                </span>
                            </button>

                            <button type="button" onclick="selectProposalType('pengadaan_barang')" id="btn-type-pengadaan_barang"
                                    class="p-4 rounded-xl border-2 text-left transition flex flex-col justify-between border-slate-200 bg-white hover:border-emerald-400 hover:bg-emerald-50/30">
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xl">📦</span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">Inventaris</span>
                                    </div>
                                    <div class="font-bold text-sm text-slate-900">Pengadaan Barang</div>
                                    <p class="text-xs text-slate-600 mt-1">Pembelian alat sekretariat, proyektor, sound system, atau lemari arsip ormawa.</p>
                                </div>
                                <span class="text-[11px] font-semibold text-emerald-700 mt-3 inline-flex items-center gap-1">
                                    <span>Gunakan Template Ini</span> &rarr;
                                </span>
                            </button>

                            <button type="button" onclick="selectProposalType('pendanaan_lomba')" id="btn-type-pendanaan_lomba"
                                    class="p-4 rounded-xl border-2 text-left transition flex flex-col justify-between border-slate-200 bg-white hover:border-amber-400 hover:bg-amber-50/30">
                                <div>
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xl">🏆</span>
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-amber-100 text-amber-800">Delegasi</span>
                                    </div>
                                    <div class="font-bold text-sm text-slate-900">Pendanaan Lomba</div>
                                    <p class="text-xs text-slate-600 mt-1">Biaya registrasi, tiket transportasi, akomodasi kontingen mahasiswa berprestasi.</p>
                                </div>
                                <span class="text-[11px] font-semibold text-amber-800 mt-3 inline-flex items-center gap-1">
                                    <span>Gunakan Template Ini</span> &rarr;
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- Section I: Pendahuluan & Narasi -->
                    <div class="mb-12">
                        <div class="flex items-center mb-6 pb-2 border-b-2 border-indigo-500">
                            <span class="bg-indigo-500 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold mr-3">I</span>
                            <h3 class="text-xl font-bold text-gray-800" id="section-1-title">Pendahuluan &amp; Narasi</h3>
                        </div>

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <x-input-label for="nama_kegiatan" :value="__('Nama Kegiatan / Permohonan')" id="lbl_nama_kegiatan" />
                                <x-text-input id="nama_kegiatan" name="nama_kegiatan" type="text" class="mt-1 block w-full" required placeholder="Contoh: Lomba Coding Nasional 2026" />
                            </div>
                            <div>
                                <x-input-label for="latar_belakang" :value="__('Latar Belakang')" />
                                <textarea id="latar_belakang" name="latar_belakang" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required placeholder="Jelaskan alasan kegiatan ini diadakan..."></textarea>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <x-input-label for="tujuan" :value="__('Tujuan Kegiatan')" />
                                    <textarea id="tujuan" name="tujuan" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required placeholder="Apa yang ingin dicapai?"></textarea>
                                </div>
                                <div>
                                    <x-input-label for="sasaran" :value="__('Sasaran / Peserta')" />
                                    <textarea id="sasaran" name="sasaran" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required placeholder="Contoh: Mahasiswa Se-Indonesia"></textarea>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <x-input-label for="indikator" :value="__('Indikator Keberhasilan')" />
                                    <textarea id="indikator" name="indikator" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Cth: Jumlah peserta, tingkat kepuasan"></textarea>
                                </div>
                                <div>
                                    <x-input-label for="luaran" :value="__('Luaran (Output)')" />
                                    <textarea id="luaran" name="luaran" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Cth: Laporan, produk, publikasi"></textarea>
                                </div>
                                <div>
                                    <x-input-label for="dampak" :value="__('Dampak (Outcome)')" />
                                    <textarea id="dampak" name="dampak" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Cth: Peningkatan kompetensi mahasiswa"></textarea>
                                </div>
                            </div>
                            <div>
                                <x-input-label for="penutup" :value="__('Penutup')" />
                                <textarea id="penutup" name="penutup" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required placeholder="Kalimat penutup proposal..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Section II: RAB -->
                    <div class="mb-12">
                        <div class="flex items-center mb-6 pb-2 border-b-2 border-indigo-500">
                            <span class="bg-indigo-500 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold mr-3">II</span>
                            <h3 class="text-xl font-bold text-gray-800">Rencana Anggaran Biaya (RAB)</h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rincian Kebutuhan</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase w-24">Vol</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase w-32">Satuan</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase w-40">Harga Satuan</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase w-16">#</th>
                                    </tr>
                                </thead>
                                <tbody id="rab-body">
                                    <tr class="border-b">
                                        <td class="p-2"><input type="text" name="rab_rincian[]" class="w-full border-gray-300 rounded-sm" placeholder="Misal: Konsumsi Peserta"></td>
                                        <td class="p-2"><input type="number" name="rab_vol[]" class="w-full text-center border-gray-300 rounded-sm"></td>
                                        <td class="p-2"><input type="text" name="rab_sat[]" class="w-full text-center border-gray-300 rounded-sm" placeholder="Box/Org"></td>
                                        <td class="p-2"><input type="number" name="rab_harga[]" class="w-full text-right border-gray-300 rounded-sm"></td>
                                        <td class="p-2 text-center">
                                            <button type="button" onclick="this.closest('tr').remove()" class="text-red-500 hover:text-red-700">&times;</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" onclick="addRow('rab-body')" class="mt-4 text-sm text-indigo-600 font-semibold hover:text-indigo-800">+ Tambah Baris RAB</button>
                        </div>
                    </div>

                    <!-- Section III: Susunan Panitia -->
                    <div class="mb-12">
                        <div class="flex items-center mb-6 pb-2 border-indigo-500 border-b-2">
                            <span class="bg-indigo-500 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold mr-3">III</span>
                            <h3 class="text-xl font-bold text-gray-800">Susunan Panitia</h3>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jabatan</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Mahasiswa</th>
                                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase w-16">#</th>
                                    </tr>
                                </thead>
                                <tbody id="pan-body">
                                    <tr class="border-b">
                                        <td class="p-2"><input type="text" name="pan_jabatan[]" class="w-full border-gray-300 rounded-sm" placeholder="Ketua Pelaksana"></td>
                                        <td class="p-2"><input type="text" name="pan_nama[]" class="w-full border-gray-300 rounded-sm" placeholder="Nama Lengkap"></td>
                                        <td class="p-2 text-center">
                                            <button type="button" onclick="this.closest('tr').remove()" class="text-red-500 hover:text-red-700">&times;</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <button type="button" onclick="addRow('pan-body')" class="mt-4 text-sm text-indigo-600 font-semibold hover:text-indigo-800">+ Tambah Panitia</button>
                        </div>
                    </div>

                    <!-- Section IV: Penandatangan -->
                    <div class="mb-12 p-6 bg-slate-50 rounded-xl border border-slate-200"
                         x-data="{
                            signers: [{
                                jenis: 'internal',
                                role: 'ketua',
                                nama: '{{ addslashes(Auth::user()->nama_ketua ?? Auth::user()->name) }}',
                                nim: '{{ addslashes(Auth::user()->nim_ketua ?? '') }}',
                                jabatan: 'Ketua',
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
                                ketua: 'Ketua Ormawa',
                                sekretaris: 'Sekretaris Ormawa',
                                bendahara: 'Bendahara Ormawa',
                                ketua_pelaksana: 'Ketua Pelaksana',
                                sekretaris_pelaksana: 'Sekretaris Pelaksana',
                                bendahara_pelaksana: 'Bendahara Pelaksana',
                                custom: 'Pengurus / Panitia Mahasiswa'
                            },
                            presetEksternal: {
                                pembina: { jabatan: 'Dosen Pembina Ormawa', label_id: 'NIDN' },
                                pembimbing: { jabatan: 'Dosen Pembimbing Kegiatan', label_id: 'NIDN' },
                                kaprodi: { jabatan: 'Ketua Program Studi (Kaprodi)', label_id: 'NIDN' },
                                dekan: { jabatan: 'Dekan Fakultas', label_id: 'NIDN' },
                                wr3: { jabatan: 'Wakil Rektor III', label_id: 'NIDN' },
                                rektor: { jabatan: 'Rektor Institut Teknologi Garut', label_id: 'NIDN' },
                                mitra: { jabatan: 'Pimpinan Mitra / Sponsor', label_id: 'NIK' }
                            },
                            isProfileRole(role) {
                                return ['ketua', 'sekretaris', 'bendahara'].includes(role);
                            },
                            updateRole(idx) {
                                let s = this.signers[idx];
                                if (s.jenis !== 'internal') return;
                                if (this.isProfileRole(s.role)) {
                                    s.nama = this.namaMap[s.role] || '';
                                    s.nim = this.nimMap[s.role] || '';
                                    s.jabatan = this.roleLabels[s.role] || 'Pengurus Ormawa';
                                    s.label_id = 'NIM';
                                } else if (s.role === 'custom') {
                                    s.nama = '';
                                    s.nim = '';
                                    s.jabatan = '';
                                    s.label_id = 'NIM';
                                } else {
                                    s.nama = '';
                                    s.nim = '';
                                    s.jabatan = this.roleLabels[s.role] || '';
                                    s.label_id = 'NIM';
                                }
                            },
                            onJenisChange(idx) {
                                let s = this.signers[idx];
                                if (s.jenis === 'internal') {
                                    s.role = 'ketua';
                                    s.nama = this.namaMap['ketua'] || '';
                                    s.nim = this.nimMap['ketua'] || '';
                                    s.jabatan = 'Ketua Ormawa';
                                    s.label_id = 'NIM';
                                } else {
                                    s.role = 'custom';
                                    s.nama = '';
                                    s.nim = '';
                                    s.jabatan = '';
                                    s.label_id = 'NIDN';
                                }
                            },
                            applyPresetEksternal(idx, presetKey) {
                                let s = this.signers[idx];
                                if (this.presetEksternal[presetKey]) {
                                    s.jabatan = this.presetEksternal[presetKey].jabatan;
                                    s.label_id = this.presetEksternal[presetKey].label_id;
                                }
                            },
                            addSigner() {
                                this.signers.push({
                                    jenis: 'internal',
                                    role: 'sekretaris',
                                    nama: this.namaMap['sekretaris'] || '',
                                    nim: this.nimMap['sekretaris'] || '',
                                    jabatan: 'Sekretaris Ormawa',
                                    label_id: 'NIM'
                                });
                            },
                            removeSigner(idx) {
                                if (this.signers.length > 1) this.signers.splice(idx, 1);
                            }
                         }">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-slate-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 20h16"/></svg>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-800">Penandatangan Proposal</h3>
                                    <p class="text-xs text-slate-500">Pilih jabatan pengurus ormawa atau panitia pelaksana (NIM) serta pejabat kampus / mitra luar (Manual).</p>
                                </div>
                            </div>
                            <button type="button" @click="addSigner()"
                                class="inline-flex items-center gap-1 text-xs font-semibold text-indigo-600 bg-indigo-50 border border-indigo-200 rounded-lg px-3 py-1.5 hover:bg-indigo-100 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                + Tambah Penandatangan
                            </button>
                        </div>

                        <!-- Keamanan TTD Alert -->
                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-xl text-xs text-blue-900 flex items-start gap-2.5">
                            <svg class="w-4 h-4 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <div>
                                <span class="font-bold">Keamanan Tanda Tangan:</span> Tanda tangan digital kriptografis (QR Code) khusus diperuntukkan bagi <strong>Pengurus &amp; Panitia Mahasiswa (Internal)</strong> ber-NIM. Dosen, Dekan, Rektor, dan Pihak Luar menggunakan <strong>Tanda Tangan Manual Fisik</strong> (ruang tanda tangan basah &amp; cap) untuk mencegah penyalahgunaan wewenang TTD.
                            </div>
                        </div>

                        <template x-for="(signer, idx) in signers" :key="idx">
                            <div class="mb-3 p-4 bg-white rounded-xl border border-slate-200 shadow-2xs space-y-3">
                                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-700 text-xs font-bold" x-text="idx + 1"></span>
                                        <span class="text-xs font-bold text-slate-700" x-text="'Penandatangan ke-' + (idx + 1)"></span>
                                        <template x-if="signer.jenis === 'internal' && isProfileRole(signer.role)">
                                            <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                                ✓ Otomatis terisi dari profil
                                            </span>
                                        </template>
                                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-full"
                                              :class="signer.jenis === 'internal' ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-slate-100 text-slate-600'"
                                              x-text="signer.jenis === 'internal' ? 'Digital QR (Mahasiswa)' : 'Manual Basah (Dosen/Pejabat/Mitra)'"></span>
                                    </div>
                                    <button type="button" @click="removeSigner(idx)"
                                            x-show="signers.length > 1"
                                            class="text-xs text-rose-500 hover:text-rose-700 font-semibold p-1"
                                            title="Hapus penandatangan ini">
                                        Hapus
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Jenis Pihak</label>
                                        <select :name="'penandatangan_jenis[' + idx + ']'"
                                                x-model="signer.jenis"
                                                @change="onJenisChange(idx)"
                                                class="block w-full border-slate-300 rounded-lg shadow-2xs text-xs">
                                            <option value="internal">Internal (Pengurus Mahasiswa - QR)</option>
                                            <option value="eksternal">Pihak Luar / Dosen / Rektor (Manual)</option>
                                        </select>
                                    </div>

                                    <div x-show="signer.jenis === 'internal'">
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Jabatan Mahasiswa</label>
                                        <select :name="'penandatangan_role[' + idx + ']'"
                                                x-model="signer.role"
                                                @change="updateRole(idx)"
                                                class="block w-full border-slate-300 rounded-lg shadow-2xs text-xs">
                                            <optgroup label="Pengurus Inti (Otomatis dari Profil)">
                                                <option value="ketua">Ketua Ormawa</option>
                                                <option value="sekretaris">Sekretaris Ormawa</option>
                                                <option value="bendahara">Bendahara Ormawa</option>
                                            </optgroup>
                                            <optgroup label="Kepanitiaan Kegiatan Mahasiswa">
                                                <option value="ketua_pelaksana">Ketua Pelaksana</option>
                                                <option value="sekretaris_pelaksana">Sekretaris Pelaksana</option>
                                                <option value="bendahara_pelaksana">Bendahara Pelaksana</option>
                                            </optgroup>
                                            <optgroup label="Lainnya">
                                                <option value="custom">Jabatan Mahasiswa Lainnya...</option>
                                            </optgroup>
                                        </select>
                                    </div>

                                    <div x-show="signer.jenis === 'eksternal'">
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Pilihan Cepat / Pejabat</label>
                                        <select @change="applyPresetEksternal(idx, $event.target.value)"
                                                class="block w-full border-slate-300 rounded-lg shadow-2xs text-xs bg-slate-50">
                                            <option value="">-- Pilih Preset atau Input Bebas --</option>
                                            <optgroup label="Dosen &amp; Pejabat Kampus ITG">
                                                <option value="pembina">Dosen Pembina Ormawa</option>
                                                <option value="pembimbing">Dosen Pembimbing Kegiatan</option>
                                                <option value="kaprodi">Ketua Program Studi (Kaprodi)</option>
                                                <option value="dekan">Dekan Fakultas</option>
                                                <option value="wr3">Wakil Rektor III</option>
                                                <option value="rektor">Rektor ITG</option>
                                            </optgroup>
                                            <optgroup label="Pihak Luar">
                                                <option value="mitra">Pimpinan Mitra / Sponsor</option>
                                            </optgroup>
                                        </select>
                                    </div>

                                    <!-- Kolom Jabatan Kustom / Eksternal -->
                                    <div x-show="signer.jenis === 'eksternal' || signer.role === 'custom'">
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Jabatan</label>
                                        <input type="text"
                                               :name="'penandatangan_jabatan[' + idx + ']'"
                                               x-model="signer.jabatan"
                                               placeholder="Contoh: Dosen Pembina / Ketua Pelaksana"
                                               class="block w-full border-slate-300 rounded-lg shadow-2xs text-xs" />
                                    </div>
                                    <!-- Hidden fallback input jabatan untuk preset -->
                                    <template x-if="signer.jenis === 'internal' && signer.role !== 'custom'">
                                        <input type="hidden" :name="'penandatangan_jabatan[' + idx + ']'" :value="signer.jabatan">
                                    </template>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-1">
                                    <div class="sm:col-span-2">
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Lengkap &amp; Gelar</label>
                                        <input type="text"
                                               :name="'penandatangan_nama[' + idx + ']'"
                                               x-model="signer.nama"
                                               :readonly="signer.jenis === 'internal' && isProfileRole(signer.role)"
                                               :class="signer.jenis === 'internal' && isProfileRole(signer.role) ? 'bg-slate-100 cursor-not-allowed text-slate-700' : 'bg-white text-slate-900'"
                                               :placeholder="signer.jenis === 'internal' && isProfileRole(signer.role) ? 'Nama terisi dari profil' : 'Masukkan nama lengkap & gelar...'"
                                               class="block w-full border-slate-300 rounded-lg shadow-2xs text-xs font-medium" />
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">Tipe Identitas</label>
                                        <select :name="'penandatangan_label_id[' + idx + ']'"
                                                x-model="signer.label_id"
                                                :disabled="signer.jenis === 'internal'"
                                                class="block w-full border-slate-300 rounded-lg shadow-2xs text-xs font-semibold text-indigo-700">
                                            <option value="NIM">NIM (Mahasiswa)</option>
                                            <option value="NIDN">NIDN (Dosen)</option>
                                            <option value="NIP">NIP (Pegawai/PNS)</option>
                                            <option value="NIK">NIK (Kependudukan)</option>
                                            <option value="ID">No. Identitas Lainnya</option>
                                        </select>
                                        <template x-if="signer.jenis === 'internal'">
                                            <input type="hidden" :name="'penandatangan_label_id[' + idx + ']'" value="NIM">
                                        </template>
                                    </div>

                                    <div class="sm:col-span-3">
                                        <label class="block text-xs font-semibold text-slate-600 mb-1">
                                            <span x-text="signer.label_id + ' Penandatangan'"></span>
                                        </label>
                                        <input type="text"
                                               :name="'penandatangan_nim[' + idx + ']'"
                                               x-model="signer.nim"
                                               :readonly="signer.jenis === 'internal' && isProfileRole(signer.role)"
                                               :class="signer.jenis === 'internal' && isProfileRole(signer.role) ? 'bg-slate-100 cursor-not-allowed text-slate-700' : 'bg-white text-slate-900'"
                                               :placeholder="signer.label_id === 'NIDN' ? 'Contoh NIDN: 0421099301' : (signer.label_id === 'NIP' ? 'Contoh NIP: 198501152010121002' : 'Contoh NIM: 2106001')"
                                               class="block w-full border-slate-300 rounded-lg shadow-2xs text-xs font-mono" />
                                    </div>
                                </div>
                            </div>
                        </template>

                        <p class="mt-4 text-xs text-slate-500 italic">Penandatangan internal mendapat tanda tangan digital kriptografis yang dapat diverifikasi melalui QR code pada lembar pengesahan proposal.</p>
                    </div>

                    <div class="flex justify-end items-center gap-3 mt-8">
                        <a href="{{ route('generator.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-md font-semibold text-sm text-gray-700 bg-white hover:bg-gray-50">Batal</a>
                        <button type="submit" name="action" value="draft" class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-md font-semibold text-sm text-gray-700 bg-white hover:bg-gray-50">Simpan Draft</button>
                        <button type="submit" name="action" value="print" class="inline-flex items-center px-8 py-3 bg-indigo-600 border border-transparent rounded-md font-semibold text-sm text-white hover:bg-indigo-700">Simpan & Cetak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function addRow(tableId) {
            const tbody = document.getElementById(tableId);
            const firstRow = tbody.querySelector('tr');
            const newRow = firstRow.cloneNode(true);
            newRow.querySelectorAll('input').forEach(input => input.value = '');
            tbody.appendChild(newRow);
        }

        const proposalTemplates = {
            kegiatan: {
                nama: 'Lomba Coding & Hackathon Mahasiswa ITG 2026',
                latar_belakang: 'Dalam era transformasi digital, penguasaan teknologi informasi dan logika pemrograman menjadi kompetensi kunci mahasiswa. Kegiatan ini diselenggarakan untuk memfasilitasi mahasiswa ITG dan perguruan tinggi se-Indonesia dalam mengasah kemampuan problem solving serta mempererat jejaring akademik nasional.',
                tujuan: '1. Meningkatkan daya saing dan kemampuan algorithmic coding mahasiswa.\n2. Menjalin relasi antar mahasiswa teknologi informasi se-Indonesia.\n3. Mengharumkan nama almamater Institut Teknologi Garut di kancah nasional.',
                sasaran: 'Mahasiswa aktif D3/D4/S1 perguruan tinggi se-Indonesia dan seluruh anggota ormawa ITG.',
                indikator: 'Partisipasi minimal 100 tim peserta dari berbagai kampus, tingkat kepuasan peserta >= 85%, dan seluruh tahapan kompetisi terlaksana sesuai timeline.',
                luaran: 'Dokumentasi acara, sertifikat penghargaan kejuaraan, repository kode karya finalis, dan laporan pertanggungjawaban kegiatan.',
                dampak: 'Peningkatan reputasi kampus ITG serta motivasi mahasiswa dalam berkarya di bidang teknologi informasi.',
                penutup: 'Demikian proposal kegiatan ini kami susun dengan harapan mendapat dukungan penuh dari pihak pimpinan Institut Teknologi Garut. Atas perhatian dan kerja sama yang diberikan, kami ucapkan terima kasih.',
                rab: [
                    { rincian: 'Sewa Server Cloud & Platform Ujian Coding', vol: 1, sat: 'Paket', harga: 1500000 },
                    { rincian: 'Hadiah & Trophy Juara 1, 2, 3', vol: 1, sat: 'Paket', harga: 3000000 },
                    { rincian: 'Honorarium Juri Praktisi Industri', vol: 2, sat: 'Orang', harga: 750000 },
                    { rincian: 'Konsumsi Panitia & Juri Final', vol: 30, sat: 'Box', harga: 25000 }
                ],
                panitia: [
                    { jabatan: 'Ketua Pelaksana', nama: 'Fajar Nugraha' },
                    { jabatan: 'Sekretaris Pelaksana', nama: 'Nadia Salsabila' },
                    { jabatan: 'Bendahara Pelaksana', nama: 'Rizki Pratama' },
                    { jabatan: 'Koordinator Acara', nama: 'Dimas Setiawan' }
                ]
            },
            pengadaan_barang: {
                nama: 'Pengadaan Sarana & Inventaris Sekretariat Ormawa 2026',
                latar_belakang: 'Sekretariat ormawa merupakan pusat operasional, administrasi, dan koordinasi seluruh aktivitas organisasi mahasiswa di lingkungan Institut Teknologi Garut. Kebutuhan fasilitas penunjang yang layak seperti proyektor, peralatan sound system, serta lemari arsip sangat mendesak demi menjaga kelancaran rapat kerja, pelatihan internal, dan pengarsipan dokumen resmi ormawa.',
                tujuan: '1. Menyediakan fasilitas sarana dan prasarana penunjang operasional sekretariat ormawa.\n2. Meningkatkan efisiensi kerja dan kenyamanan anggota dalam menyelenggarakan koordinasi dan rapat organisasi.\n3. Menjaga keamanan inventaris dan dokumen fisik ormawa dalam jangka panjang.',
                sasaran: 'Seluruh pengurus dan anggota ormawa, serta mahasiswa yang memanfaatkan fasilitas sekretariat bersama.',
                indikator: 'Tersedianya inventaris baru dalam kondisi 100% baik dan berfungsi, serta tercatat secara resmi dalam Buku Inventaris Sarpras Ormawa ITG.',
                luaran: 'Barang inventaris fisik yang siap pakai, kartu inventaris barang, struk/faktur asli pembelian, dan berita acara serah terima inventaris.',
                dampak: 'Peningkatan produktivitas organisasi, efisiensi operasional tanpa perlu menyewa alat dari pihak luar, serta peningkatan mutu tata kelola kesekretariatan.',
                penutup: 'Demikian proposal permohonan dana pengadaan barang dan inventaris ini kami sampaikan. Besar harapan kami permohonan ini dapat disetujui demi menunjang kelancaran organisasi. Atas perhatian dan dukungannya, kami sampaikan terima kasih.',
                rab: [
                    { rincian: 'Proyektor EPSON Full HD & Screen Tripod 70 inch', vol: 1, sat: 'Unit', harga: 4500000 },
                    { rincian: 'Speaker Portable Wireless & Mic Bluetooth', vol: 1, sat: 'Set', harga: 1800000 },
                    { rincian: 'Lemari Arsip Besi 4 Laci Anti-Karat', vol: 1, sat: 'Unit', harga: 1650000 },
                    { rincian: 'Kabel Roll 20 Meter & Colokan Stopkontak Heavy Duty', vol: 2, sat: 'Pcs', harga: 150000 }
                ],
                panitia: [
                    { jabatan: 'Penanggung Jawab Inventaris', nama: 'Ketua Ormawa' },
                    { jabatan: 'Koordinator Tim Pengadaan Barang', nama: 'M. Wildan' },
                    { jabatan: 'Verifikator Administrasi & Faktur', nama: 'Bendahara Ormawa' }
                ]
            },
            pendanaan_lomba: {
                nama: 'Permohonan Dana Delegasi Kompetisi Mahasiswa Tingkat Nasional 2026',
                latar_belakang: 'Sebagai wujud kontribusi mahasiswa ITG dalam menorehkan prestasi di kancah nasional, delegasi mahasiswa telah lolos seleksi awal dan diundang untuk berkompetisi pada babak final kejuaraan bergengsi tingkat nasional. Keikutsertaan delegasi ini membutuhkan dukungan pendanaan kampus untuk biaya pendaftaran resmi, transportasi delegasi, dan akomodasi selama kegiatan berlangsung.',
                tujuan: '1. Mengirimkan delegasi mahasiswa ITG untuk berkompetisi dan meraih medali/juara di ajang nasional.\n2. Mengharumkan nama almamater Institut Teknologi Garut di tingkat perguruan tinggi nasional.\n3. Meningkatkan perolehan poin Sistem Informasi Manajemen Pemeringkatan Kemahasiswaan (SIMKATMAWA) ITG.',
                sasaran: 'Tim kontingen/delegasi mahasiswa ITG (terdiri dari ketua tim, anggota delegasi, dan dosen pendamping).',
                indikator: 'Kehadiran penuh delegasi pada seluruh sesi kompetisi final, submit prototipe/karya tepat waktu, dan perolehan medali atau predikat kejuaraan tingkat nasional.',
                luaran: 'Sertifikat kejuaraan/finalis nasional, medali/trophy kejuaraan, dokumentasi presentasi final, dan laporan delegasi prestasi mahasiswa.',
                dampak: 'Meningkatnya reputasi dan pemeringkatan kemahasiswaan kampus ITG di level LLDIKTI IV dan Kemendikbudristek, serta menginspirasi mahasiswa lain untuk berprestasi.',
                penutup: 'Demikian proposal permohonan dana delegasi lomba ini kami ajukan. Kami berkomitmen untuk berjuang maksimal demi mengharumkan nama Institut Teknologi Garut. Terima kasih atas perhatian dan dukungannya.',
                rab: [
                    { rincian: 'Biaya Registrasi Kompetisi Tingkat Nasional (Finalis)', vol: 1, sat: 'Tim', harga: 750000 },
                    { rincian: 'Tiket Transportasi Kereta Api / Travel PP Delegasi', vol: 4, sat: 'Orang', harga: 350000 },
                    { rincian: 'Akomodasi / Penginapan Kontingen (3 Hari 2 Malam)', vol: 2, sat: 'Kamar', harga: 450000 },
                    { rincian: 'Uang Saku Harian & Konsumsi Delegasi (3 Hari)', vol: 4, sat: 'Orang', harga: 200000 }
                ],
                panitia: [
                    { jabatan: 'Ketua Delegasi Lomba', nama: 'Ilham Kurniawan' },
                    { jabatan: 'Anggota Tim Delegasi 1', nama: 'Anisa Putri' },
                    { jabatan: 'Anggota Tim Delegasi 2', nama: 'Rian Hidayat' },
                    { jabatan: 'Dosen Pembimbing Delegasi', nama: 'Dosen Pembimbing' }
                ]
            }
        };

        function selectProposalType(type) {
            document.getElementById('jenis_proposal').value = type;

            ['kegiatan', 'pengadaan_barang', 'pendanaan_lomba'].forEach(t => {
                const btn = document.getElementById('btn-type-' + t);
                if (t === type) {
                    if (t === 'kegiatan') {
                        btn.className = 'p-4 rounded-xl border-2 text-left transition flex flex-col justify-between border-indigo-600 bg-indigo-50/70 shadow-xs';
                    } else if (t === 'pengadaan_barang') {
                        btn.className = 'p-4 rounded-xl border-2 text-left transition flex flex-col justify-between border-emerald-600 bg-emerald-50/70 shadow-xs';
                    } else {
                        btn.className = 'p-4 rounded-xl border-2 text-left transition flex flex-col justify-between border-amber-600 bg-amber-50/70 shadow-xs';
                    }
                } else {
                    btn.className = 'p-4 rounded-xl border-2 text-left transition flex flex-col justify-between border-slate-200 bg-white hover:border-slate-300';
                }
            });

            const tmpl = proposalTemplates[type];
            if (!tmpl) return;

            document.getElementById('nama_kegiatan').value = tmpl.nama;
            document.getElementById('latar_belakang').value = tmpl.latar_belakang;
            document.getElementById('tujuan').value = tmpl.tujuan;
            document.getElementById('sasaran').value = tmpl.sasaran;
            document.getElementById('indikator').value = tmpl.indikator;
            document.getElementById('luaran').value = tmpl.luaran;
            document.getElementById('dampak').value = tmpl.dampak;
            document.getElementById('penutup').value = tmpl.penutup;

            // RAB
            const rabBody = document.getElementById('rab-body');
            rabBody.innerHTML = '';
            tmpl.rab.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = 'border-b';
                tr.innerHTML = `
                    <td class="p-2"><input type="text" name="rab_rincian[]" value="${item.rincian}" class="w-full border-gray-300 rounded-sm"></td>
                    <td class="p-2"><input type="number" name="rab_vol[]" value="${item.vol}" class="w-full text-center border-gray-300 rounded-sm"></td>
                    <td class="p-2"><input type="text" name="rab_sat[]" value="${item.sat}" class="w-full text-center border-gray-300 rounded-sm"></td>
                    <td class="p-2"><input type="number" name="rab_harga[]" value="${item.harga}" class="w-full text-right border-gray-300 rounded-sm"></td>
                    <td class="p-2 text-center">
                        <button type="button" onclick="this.closest('tr').remove()" class="text-red-500 hover:text-red-700 font-bold">&times;</button>
                    </td>
                `;
                rabBody.appendChild(tr);
            });

            // Panitia / Tim
            const panBody = document.getElementById('pan-body');
            panBody.innerHTML = '';
            tmpl.panitia.forEach(item => {
                const tr = document.createElement('tr');
                tr.className = 'border-b';
                tr.innerHTML = `
                    <td class="p-2"><input type="text" name="pan_jabatan[]" value="${item.jabatan}" class="w-full border-gray-300 rounded-sm"></td>
                    <td class="p-2"><input type="text" name="pan_nama[]" value="${item.nama}" class="w-full border-gray-300 rounded-sm"></td>
                    <td class="p-2 text-center">
                        <button type="button" onclick="this.closest('tr').remove()" class="text-red-500 hover:text-red-700 font-bold">&times;</button>
                    </td>
                `;
                panBody.appendChild(tr);
            });
        }
    </script>
</x-app-layout>
