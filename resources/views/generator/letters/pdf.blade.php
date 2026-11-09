@php
    $m = $letter->metadata ?? [];
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat - {{ $letter->perihal }}</title>
    <style>
        @page { size: A4; margin: 18mm; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; line-height: 1.5; color: #000; }
        .kop-table { width: 100%; border-collapse: collapse; margin-bottom: 4px; }
        .kop-logo { width: 75px; height: 75px; object-fit: contain; }
        .kop-text { text-align: center; padding: 0 8px; }
        .kop-1 { font-size: 10pt; }
        .kop-2 { font-size: 13pt; font-weight: bold; text-transform: uppercase; }
        .kop-3, .kop-4 { font-size: 9pt; font-style: italic; line-height: 1.3; }
        .judul { text-align: center; font-weight: bold; text-decoration: underline; margin: 26px 0; }
        table.meta { line-height: 1.8; }
        table.meta td { padding: 0 8px 0 0; vertical-align: top; }
        .isi { text-align: justify; line-height: 1.6; }
    </style>
</head>
<body>
    @if($letter->user && $letter->user->hasAnyRole(['ormawa', 'bem', 'bpm']))
        @include('generator.partials.kop-ormawa', ['user' => $letter->user, 'isPdf' => true, 'pdf' => true])
    @else
        @include('generator.partials.kop', ['konfig' => $konfig, 'isPdf' => true, 'pdf' => true])
    @endif

    <div class="judul">
        @if($letter->type === 'undangan') SURAT UNDANGAN
        @elseif($letter->type === 'tugas') SURAT TUGAS / MANDAT
        @elseif($letter->type === 'permohonan') SURAT PERMOHONAN
        @elseif($letter->type === 'keterangan_aktif') SURAT KETERANGAN AKTIF
        @elseif($letter->type === 'izin_kegiatan') SURAT PERMOHONAN IZIN KEGIATAN
        @elseif($letter->type === 'peminjaman_tempat') SURAT PERMOHONAN PEMINJAMAN FASILITAS
        @elseif($letter->type === 'dispensasi') SURAT PERMOHONAN DISPENSASI KULIAH
        @elseif($letter->type === 'rekomendasi') SURAT REKOMENDASI
        @elseif($letter->type === 'pemberitahuan') SURAT PEMBERITAHUAN
        @elseif($letter->type === 'pengantar') SURAT PENGANTAR DOKUMEN
        @elseif($letter->type === 'ucapan_terima_kasih') SURAT UCAPAN TERIMA KASIH &amp; APRESIASI
        @elseif($letter->type === 'kerjasama') SURAT PERMOHONAN KERJA SAMA
        @elseif($letter->type === 'peringatan') SURAT PERINGATAN INTERNAL
        @else SURAT @endif
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 16px; font-size: 11pt; line-height: 1.5;">
        <tr>
            <td style="width: 75px; vertical-align: top;">Nomor</td>
            <td style="width: 15px; vertical-align: top;">:</td>
            <td style="vertical-align: top;">{{ $letter->nomor_surat ?? '___/___/___' }}</td>
            <td style="text-align: right; vertical-align: top;">Garut, {{ \Carbon\Carbon::parse($letter->created_at)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td style="vertical-align: top;">Lampiran</td>
            <td style="vertical-align: top;">:</td>
            <td colspan="2" style="vertical-align: top;">{{ $letter->lampiran ?? ($m['lampiran'] ?? '-') }}</td>
        </tr>
        <tr>
            <td style="vertical-align: top;">Perihal</td>
            <td style="vertical-align: top;">:</td>
            <td colspan="2" style="vertical-align: top; font-weight: bold;">{{ $letter->perihal }}</td>
        </tr>
    </table>

    <div style="margin-bottom: 20px; line-height: 1.4;">
        Kepada Yth.<br>
        <strong>{{ $m['tujuan'] ?? '..........................' }}</strong><br>
        di Tempat
    </div>

    @if($letter->type === 'undangan')
        <div class="isi">{{ $m['kalimat_pembuka'] ?? 'Dengan hormat, sehubungan dengan akan dilaksanakannya kegiatan kami, bersama ini kami bermaksud mengundang Bapak/Ibu untuk dapat hadir pada kegiatan yang diselenggarakan:' }}</div>
        <table class="meta" style="margin-top:14px; margin-bottom:14px; margin-left: 18px;">
            <tr><td style="width: 140px;">Nama Acara</td><td>: {{ $m['nama_acara'] ?? '-' }}</td></tr>
            <tr><td>Hari / Tanggal</td><td>: {{ $m['hari_tanggal'] ?? '-' }}</td></tr>
            <tr><td>Waktu</td><td>: {{ $m['waktu'] ?? '-' }}</td></tr>
            <tr><td>Tempat</td><td>: {{ $m['tempat'] ?? '-' }}</td></tr>
        </table>
        <div class="isi">{{ $m['kalimat_penutup'] ?? 'Demikian surat undangan ini kami sampaikan. Atas perhatian, kehadiran, dan kerja sama Bapak/Ibu, kami ucapkan terima kasih.' }}</div>
    @elseif($letter->type === 'tugas')
        <div class="isi">{{ $m['kalimat_pembuka'] ?? 'Yang bertanda tangan di bawah ini Pengurus Organisasi Kemahasiswaan Institut Teknologi Garut, dengan ini memberikan tugas / mandat kepada:' }}</div>
        @php
            $petugasList = $m['petugas_list'] ?? [];
            if (empty($petugasList) && (!empty($m['nama_petugas']) || !empty($m['nim']))) {
                $petugasList = [['nama' => $m['nama_petugas'] ?? '-', 'nim' => $m['nim'] ?? '-']];
            }
        @endphp

        @if(count($petugasList) > 1)
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 12px; font-size: 11pt;">
                <thead>
                    <tr style="background-color: #f1f5f9; text-align: left;">
                        <th style="width: 35px; padding: 5px 8px; border: 1px solid #000; text-align: center;">No</th>
                        <th style="padding: 5px 8px; border: 1px solid #000;">Nama Penerima Tugas / Mandat</th>
                        <th style="width: 160px; padding: 5px 8px; border: 1px solid #000;">NIM</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($petugasList as $idx => $p)
                        <tr>
                            <td style="padding: 5px 8px; border: 1px solid #000; text-align: center;">{{ $idx + 1 }}.</td>
                            <td style="padding: 5px 8px; border: 1px solid #000; font-weight: bold;">{{ $p['nama'] }}</td>
                            <td style="padding: 5px 8px; border: 1px solid #000; font-family: monospace;">{{ $p['nim'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="meta" style="margin-bottom:12px; margin-left: 18px;">
                <tr><td style="width: 160px; vertical-align: top;">Uraian Tugas</td><td>: {{ $m['uraian_tugas'] ?? '-' }}</td></tr>
                <tr><td style="vertical-align: top;">Tanggal Pelaksanaan</td><td>: {{ $m['tanggal_pelaksanaan'] ?? '-' }}</td></tr>
            </table>
        @else
            <table class="meta" style="margin-top:14px; margin-bottom:14px; margin-left: 18px;">
                <tr><td style="width: 160px;">Nama</td><td>: {{ $petugasList[0]['nama'] ?? ($m['nama_petugas'] ?? '-') }}</td></tr>
                <tr><td>NIM</td><td>: {{ $petugasList[0]['nim'] ?? ($m['nim'] ?? '-') }}</td></tr>
                <tr><td>Uraian Tugas</td><td>: {{ $m['uraian_tugas'] ?? '-' }}</td></tr>
                <tr><td>Tanggal Pelaksanaan</td><td>: {{ $m['tanggal_pelaksanaan'] ?? '-' }}</td></tr>
            </table>
        @endif
        <div class="isi">{{ $m['kalimat_penutup'] ?? 'Untuk melaksanakan tugas tersebut dengan penuh tanggung jawab. Surat tugas ini dibuat untuk dapat dipergunakan sebagaimana mestinya.' }}</div>
    @elseif($letter->type === 'permohonan')
        <div class="isi">{{ $m['kalimat_pembuka'] ?? 'Dengan hormat, sehubungan dengan agenda kegiatan yang akan kami selenggarakan, bersama ini kami mengajukan permohonan peminjaman:' }}</div>
        <table class="meta" style="margin-top:14px; margin-bottom:14px; margin-left: 18px;">
            <tr><td style="width: 170px;">Nama Alat / Ruangan</td><td>: {{ $m['nama_alat_tempat'] ?? '-' }}</td></tr>
            <tr><td>Waktu Penggunaan</td><td>: {{ $m['waktu_penggunaan'] ?? '-' }}</td></tr>
            <tr><td>Alasan / Tujuan</td><td>: {{ $m['alasan_tujuan'] ?? '-' }}</td></tr>
        </table>
        <div class="isi">{{ $m['kalimat_penutup'] ?? 'Demikian permohonan ini kami ajukan. Atas perhatian, izin, dan bantuan Bapak/Ibu, kami ucapkan terima kasih.' }}</div>
    @elseif($letter->type === 'keterangan_aktif')
        <div class="isi">{{ $m['kalimat_pembuka'] ?? 'Yang bertanda tangan di bawah ini Pengurus Organisasi Kemahasiswaan Institut Teknologi Garut, dengan ini menerangkan bahwa:' }}</div>
        @php
            $mahasiswaList = $m['mahasiswa_list'] ?? [];
            if (empty($mahasiswaList) && (!empty($m['nama_mahasiswa']) || !empty($m['nim']))) {
                $mahasiswaList = [[
                    'nama'    => $m['nama_mahasiswa'] ?? '-',
                    'nim'     => $m['nim'] ?? '-',
                    'jabatan' => $m['jabatan'] ?? '-',
                ]];
            }
        @endphp

        @if(count($mahasiswaList) > 1)
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 12px; font-size: 11pt;">
                <thead>
                    <tr style="background-color: #f1f5f9; text-align: left;">
                        <th style="width: 35px; padding: 5px 8px; border: 1px solid #000; text-align: center;">No</th>
                        <th style="padding: 5px 8px; border: 1px solid #000;">Nama Mahasiswa</th>
                        <th style="width: 130px; padding: 5px 8px; border: 1px solid #000;">NIM</th>
                        <th style="padding: 5px 8px; border: 1px solid #000;">Jabatan di Organisasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mahasiswaList as $idx => $mhs)
                        <tr>
                            <td style="padding: 5px 8px; border: 1px solid #000; text-align: center;">{{ $idx + 1 }}.</td>
                            <td style="padding: 5px 8px; border: 1px solid #000; font-weight: bold;">{{ $mhs['nama'] }}</td>
                            <td style="padding: 5px 8px; border: 1px solid #000; font-family: monospace;">{{ $mhs['nim'] }}</td>
                            <td style="padding: 5px 8px; border: 1px solid #000;">{{ $mhs['jabatan'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <table class="meta" style="margin-bottom:12px; margin-left: 18px;">
                <tr><td style="width: 170px; vertical-align: top;">Keperluan</td><td>: {{ $m['keperluan'] ?? '-' }}</td></tr>
            </table>
        @else
            <table class="meta" style="margin-top:14px; margin-bottom:14px; margin-left: 18px;">
                <tr><td style="width: 170px;">Nama</td><td>: {{ $mahasiswaList[0]['nama'] ?? ($m['nama_mahasiswa'] ?? '-') }}</td></tr>
                <tr><td>NIM</td><td>: {{ $mahasiswaList[0]['nim'] ?? ($m['nim'] ?? '-') }}</td></tr>
                <tr><td>Jabatan di Organisasi</td><td>: {{ $mahasiswaList[0]['jabatan'] ?? ($m['jabatan'] ?? '-') }}</td></tr>
                <tr><td>Keperluan</td><td>: {{ $m['keperluan'] ?? '-' }}</td></tr>
            </table>
        @endif
        <div class="isi">{{ $m['kalimat_penutup'] ?? 'Adalah benar mahasiswa/anggota aktif pada organisasi kami. Surat keterangan ini kami buat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.' }}</div>
    @elseif($letter->type === 'izin_kegiatan')
        <div class="isi">{{ $m['kalimat_pembuka'] ?? 'Dengan hormat, sehubungan dengan rencana pelaksanaan agenda kegiatan kami, bersama ini kami mengajukan permohonan izin penyelenggaraan kegiatan sebagai berikut:' }}</div>
        <table class="meta" style="margin-top:14px; margin-bottom:14px; margin-left: 18px;">
            <tr><td style="width: 170px;">Nama Kegiatan</td><td>: {{ $m['nama_kegiatan'] ?? '-' }}</td></tr>
            <tr><td>Hari / Tanggal</td><td>: {{ $m['hari_tanggal'] ?? '-' }}</td></tr>
            <tr><td>Waktu</td><td>: {{ $m['waktu'] ?? '-' }}</td></tr>
            <tr><td>Tempat / Lokasi</td><td>: {{ $m['tempat'] ?? '-' }}</td></tr>
            @if(!empty($m['jumlah_peserta']))
                <tr><td>Estimasi Peserta</td><td>: {{ $m['jumlah_peserta'] }}</td></tr>
            @endif
            @if(!empty($m['penanggung_jawab']))
                <tr><td>Penanggung Jawab</td><td>: {{ $m['penanggung_jawab'] }}</td></tr>
            @endif
        </table>
        <div class="isi">{{ $m['kalimat_penutup'] ?? 'Demikian permohonan izin kegiatan ini kami sampaikan. Atas perhatian, dukungan, dan izin yang diberikan, kami ucapkan terima kasih.' }}</div>
    @elseif($letter->type === 'peminjaman_tempat')
        <div class="isi">{{ $m['kalimat_pembuka'] ?? 'Dengan hormat, sehubungan dengan akan diselenggarakannya agenda kegiatan mahasiswa, bersama ini kami mengajukan permohonan peminjaman fasilitas kampus sebagai berikut:' }}</div>
        <table class="meta" style="margin-top:14px; margin-bottom:14px; margin-left: 18px;">
            <tr><td style="width: 170px;">Nama Fasilitas / Tempat</td><td>: {{ $m['nama_fasilitas'] ?? '-' }}</td></tr>
            <tr><td>Hari / Tanggal</td><td>: {{ $m['hari_tanggal'] ?? '-' }}</td></tr>
            <tr><td>Waktu Penggunaan</td><td>: {{ $m['waktu_mulai_selesai'] ?? '-' }}</td></tr>
            <tr><td>Tujuan / Agenda Acara</td><td>: {{ $m['tujuan_penggunaan'] ?? '-' }}</td></tr>
            @if(!empty($m['penanggung_jawab']))
                <tr><td>Penanggung Jawab</td><td>: {{ $m['penanggung_jawab'] }}</td></tr>
            @endif
        </table>
        <div class="isi">{{ $m['kalimat_penutup'] ?? 'Besar harapan kami agar permohonan peminjaman ini dapat disetujui. Kami berkomitmen menjaga ketertiban, kebersihan, dan keselamatan fasilitas kampus yang digunakan. Atas kerja sama Bapak/Ibu, kami ucapkan terima kasih.' }}</div>
    @elseif($letter->type === 'dispensasi')
        <div class="isi">{{ $m['kalimat_pembuka'] ?? 'Dengan hormat, sehubungan dengan partisipasi dan penugasan mahasiswa pada agenda kegiatan institusi, dengan ini kami memohon dispensasi ketidakhadiran perkuliahan/praktikum bagi mahasiswa berikut:' }}</div>
        @php
            $dispList = $m['dispensasi_list'] ?? [];
            if (empty($dispList) && (!empty($m['nama_mahasiswa']) || !empty($m['nim']))) {
                $dispList = [[
                    'nama' => $m['nama_mahasiswa'] ?? '-',
                    'nim' => $m['nim'] ?? '-',
                    'prodi' => $m['kelas_prodi'] ?? '-'
                ]];
            }
        @endphp

        @if(count($dispList) > 1)
            <table style="width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 12px; font-size: 11pt;">
                <thead>
                    <tr style="background-color: #f1f5f9; text-align: left;">
                        <th style="width: 35px; padding: 5px 8px; border: 1px solid #000; text-align: center;">No</th>
                        <th style="padding: 5px 8px; border: 1px solid #000;">Nama Mahasiswa</th>
                        <th style="width: 130px; padding: 5px 8px; border: 1px solid #000;">NIM</th>
                        <th style="padding: 5px 8px; border: 1px solid #000;">Prodi / Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dispList as $idx => $d)
                        <tr>
                            <td style="padding: 5px 8px; border: 1px solid #000; text-align: center;">{{ $idx + 1 }}.</td>
                            <td style="padding: 5px 8px; border: 1px solid #000; font-weight: bold;">{{ $d['nama'] }}</td>
                            <td style="padding: 5px 8px; border: 1px solid #000; font-family: monospace;">{{ $d['nim'] }}</td>
                            <td style="padding: 5px 8px; border: 1px solid #000;">{{ $d['prodi'] ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <table class="meta" style="margin-top:14px; margin-bottom:14px; margin-left: 18px;">
                <tr><td style="width: 170px;">Nama Mahasiswa</td><td>: {{ $dispList[0]['nama'] ?? ($m['nama_mahasiswa'] ?? '-') }}</td></tr>
                <tr><td>NIM</td><td>: {{ $dispList[0]['nim'] ?? ($m['nim'] ?? '-') }}</td></tr>
                <tr><td>Program Studi / Kelas</td><td>: {{ $dispList[0]['prodi'] ?? ($m['kelas_prodi'] ?? '-') }}</td></tr>
            </table>
        @endif

        <table class="meta" style="margin-bottom:12px; margin-left: 18px;">
            <tr><td style="width: 170px;">Kegiatan / Acara</td><td>: {{ $m['nama_kegiatan'] ?? '-' }}</td></tr>
            <tr><td>Hari / Tanggal</td><td>: {{ $m['hari_tanggal'] ?? '-' }}</td></tr>
            @if(!empty($m['waktu']))
                <tr><td>Waktu / Jam Kuliah</td><td>: {{ $m['waktu'] }}</td></tr>
            @endif
            <tr><td>Keterangan Penugasan</td><td>: {{ $m['keterangan_tugas'] ?? '-' }}</td></tr>
        </table>
        <div class="isi">{{ $m['kalimat_penutup'] ?? 'Demikian permohonan dispensasi ini kami ajukan. Atas perhatian, dukungan, dan kebijaksanaan Bapak/Ibu Dosen Pengampu mata kuliah, kami ucapkan terima kasih.' }}</div>
    @elseif($letter->type === 'rekomendasi')
        <div class="isi">{{ $m['kalimat_pembuka'] ?? 'Yang bertanda tangan di bawah ini Pengurus Organisasi Kemahasiswaan Institut Teknologi Garut, dengan ini memberikan rekomendasi kepada:' }}</div>
        <table class="meta" style="margin-top:14px; margin-bottom:14px; margin-left: 18px;">
            <tr><td style="width: 180px;">Nama Mahasiswa</td><td>: {{ $m['nama_mahasiswa'] ?? '-' }}</td></tr>
            <tr><td>NIM</td><td>: {{ $m['nim'] ?? '-' }}</td></tr>
            <tr><td>Program Studi &amp; Semester</td><td>: {{ $m['program_studi'] ?? '-' }}</td></tr>
            <tr><td>Keperluan Rekomendasi</td><td>: {{ $m['tujuan_rekomendasi'] ?? '-' }}</td></tr>
            <tr><td style="vertical-align: top;">Kualifikasi &amp; Rekam Jejak</td><td style="vertical-align: top;">: {{ $m['prestasi_rekam_jejak'] ?? '-' }}</td></tr>
        </table>
        <div class="isi">{{ $m['kalimat_penutup'] ?? 'Surat rekomendasi ini kami berikan dengan penuh pertimbangan atas dedikasi, integritas, dan kompetensi yang bersangkutan untuk dapat dipergunakan sebagaimana mestinya.' }}</div>
    @elseif($letter->type === 'pemberitahuan')
        <div class="isi">{{ $m['kalimat_pembuka'] ?? 'Dengan hormat, melalui surat ini kami bermaksud memberitahukan bahwa Organisasi Mahasiswa kami akan menyelenggarakan kegiatan sebagai berikut:' }}</div>
        <table class="meta" style="margin-top:14px; margin-bottom:14px; margin-left: 18px;">
            <tr><td style="width: 170px;">Nama Kegiatan</td><td>: {{ $m['nama_kegiatan'] ?? '-' }}</td></tr>
            <tr><td>Hari / Tanggal</td><td>: {{ $m['hari_tanggal'] ?? '-' }}</td></tr>
            <tr><td>Waktu</td><td>: {{ $m['waktu'] ?? '-' }}</td></tr>
            <tr><td>Tempat / Lokasi</td><td>: {{ $m['tempat'] ?? '-' }}</td></tr>
            <tr><td style="vertical-align: top;">Uraian Kegiatan</td><td style="vertical-align: top;">: {{ $m['uraian_kegiatan'] ?? '-' }}</td></tr>
        </table>
        <div class="isi">{{ $m['kalimat_penutup'] ?? 'Demikian surat pemberitahuan ini kami sampaikan sebagai bentuk koordinasi dan keterbukaan informasi. Atas perhatian dan kerja sama yang baik, kami ucapkan terima kasih.' }}</div>
    @elseif($letter->type === 'pengantar')
        <div class="isi">{{ $m['kalimat_pembuka'] ?? 'Dengan hormat, bersama surat ini kami sampaikan berkas dokumen resmi organisasi kemahasiswaan dengan rincian sebagai berikut:' }}</div>
        <table class="meta" style="margin-top:14px; margin-bottom:14px; margin-left: 18px;">
            <tr><td style="width: 170px;">Nama Berkas / Dokumen</td><td>: {{ $m['berkas_dokumen'] ?? '-' }}</td></tr>
            <tr><td>Banyaknya / Eksemplar</td><td>: {{ $m['jumlah_eksemplar'] ?? '-' }}</td></tr>
            @if(!empty($m['keterangan_berkas']))
                <tr><td>Keterangan</td><td>: {{ $m['keterangan_berkas'] }}</td></tr>
            @endif
        </table>
        <div class="isi">{{ $m['kalimat_penutup'] ?? 'Demikian surat pengantar ini kami sampaikan. Mohon perkenan Bapak/Ibu untuk memeriksa dan memproses dokumen tersebut lebih lanjut. Atas perhatiannya, kami ucapkan terima kasih.' }}</div>
    @elseif($letter->type === 'ucapan_terima_kasih')
        <div class="isi">{{ $m['kalimat_pembuka'] ?? 'Dengan hormat, sehubungan dengan telah terselenggaranya kegiatan kami dengan sukses dan lancar, kami segenap Pengurus dan Panitia menyampaikan apresiasi dan penghargaan setinggi-tingginya kepada:' }}</div>
        <table class="meta" style="margin-top:14px; margin-bottom:14px; margin-left: 18px;">
            <tr><td style="width: 180px;">Nama Tokoh / Instansi</td><td>: {{ $m['nama_pihak'] ?? '-' }}</td></tr>
            <tr><td>Bentuk Peran / Kontribusi</td><td>: {{ $m['peran_kontribusi'] ?? '-' }}</td></tr>
            <tr><td>Nama Acara / Kegiatan</td><td>: {{ $m['nama_acara'] ?? '-' }}</td></tr>
            <tr><td>Hari / Tanggal Acara</td><td>: {{ $m['hari_tanggal'] ?? '-' }}</td></tr>
        </table>
        <div class="isi">{{ $m['kalimat_penutup'] ?? 'Kami mengucapkan terima kasih yang sebesar-besarnya atas kontribusi, ilmu, dan kerja sama yang telah diberikan. Semoga hubungan baik dan kemitraan ini dapat terus terjalin pada kesempatan yang akan datang.' }}</div>
    @elseif($letter->type === 'kerjasama')
        <div class="isi">{{ $m['kalimat_pembuka'] ?? 'Dengan hormat, dalam rangka menyukseskan agenda kegiatan mahasiswa skala regional/nasional, kami bermaksud menawarkan peluang kerja sama kemitraan yang saling menguntungkan sebagai berikut:' }}</div>
        <table class="meta" style="margin-top:14px; margin-bottom:14px; margin-left: 18px;">
            <tr><td style="width: 170px;">Nama Kegiatan</td><td>: {{ $m['nama_kegiatan'] ?? '-' }}</td></tr>
            <tr><td>Bentuk Kerja Sama</td><td>: {{ $m['bentuk_kerjasama'] ?? '-' }}</td></tr>
            <tr><td style="vertical-align: top;">Benefit / Timbal Balik</td><td style="vertical-align: top;">: {{ $m['manfaat_timbal_balik'] ?? '-' }}</td></tr>
            <tr><td>Narahubung (CP)</td><td>: {{ $m['kontak_person'] ?? '-' }}</td></tr>
        </table>
        <div class="isi">{{ $m['kalimat_penutup'] ?? 'Besar harapan kami agar perusahaan/lembaga Bapak/Ibu berkenan menjadi mitra dalam agenda kegiatan ini. Atas perhatian, kesediaan, dan kerja sama yang baik, kami ucapkan terima kasih.' }}</div>
    @elseif($letter->type === 'peringatan')
        <div class="isi">{{ $m['kalimat_pembuka'] ?? 'Berdasarkan Anggaran Dasar / Anggaran Rumah Tangga (AD/ART) serta hasil rapat evaluasi kedisiplinan pengurus, dengan ini kami memberikan Surat Peringatan kepada:' }}</div>
        <table class="meta" style="margin-top:14px; margin-bottom:14px; margin-left: 18px;">
            <tr><td style="width: 170px;">Tingkat Peringatan</td><td>: <strong>{{ $m['tingkat_sp'] ?? 'SP-1' }}</strong></td></tr>
            <tr><td>Nama Anggota</td><td>: {{ $m['nama_anggota'] ?? '-' }}</td></tr>
            <tr><td>NIM</td><td>: {{ $m['nim'] ?? '-' }}</td></tr>
            <tr><td>Jabatan / Divisi</td><td>: {{ $m['jabatan_divisi'] ?? '-' }}</td></tr>
            <tr><td style="vertical-align: top;">Uraian Pelanggaran</td><td style="vertical-align: top;">: {{ $m['pelanggaran'] ?? '-' }}</td></tr>
            <tr><td style="vertical-align: top;">Sanksi Disiplin</td><td style="vertical-align: top;">: {{ $m['sanksi'] ?? '-' }}</td></tr>
        </table>
        <div class="isi">{{ $m['kalimat_penutup'] ?? 'Surat peringatan ini diterbitkan agar yang bersangkutan segera mengevaluasi diri dan meningkatkan komitmen serta tanggung jawab dalam organisasi. Atas perhatiannya diucapkan terima kasih.' }}</div>
    @else
        @if(!empty($m['kalimat_pembuka']))
            <div class="isi" style="margin-bottom: 12px;">{{ $m['kalimat_pembuka'] }}</div>
        @endif
        <div class="isi" style="white-space: pre-wrap;">{{ $letter->content }}</div>
        @if(!empty($m['kalimat_penutup']))
            <div class="isi" style="margin-top: 12px;">{{ $m['kalimat_penutup'] }}</div>
        @endif
    @endif

    @include('generator.partials.penandatangan', [
        'penandatanganList' => $letter->penandatangan_list,
        'signatures' => $letter->signatures_by_index,
        'keteranganPeriode' => $letter->user->periode_kepengurusan ?? null,
        'namaOrganisasi' => $letter->user->name ?? null,
    ])
</body>
</html>
