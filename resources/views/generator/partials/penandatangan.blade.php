@php
    $items = $penandatanganList ?? [];
    $signatures = $signatures ?? [];
    $showQr = $showQr ?? true;
    $count = max(count($items), 1);
    $colWidth = round(100 / $count, 4) . '%';
    $keteranganPeriode = $keteranganPeriode ?? null;
    $namaOrganisasi = $namaOrganisasi ?? null;
@endphp

@if(!empty($keteranganPeriode) || !empty($namaOrganisasi))
    <div style="text-align: center; margin-top: 30px; margin-bottom: 8px; font-size: 10.5pt; font-weight: bold; text-transform: uppercase;">
        @if(!empty($namaOrganisasi))
            <div>PENGURUS {{ $namaOrganisasi }}</div>
        @endif
        @if(!empty($keteranganPeriode))
            <div style="font-size: 9.5pt; font-weight: normal; text-transform: none; margin-top: 2px;">{{ $keteranganPeriode }}</div>
        @endif
    </div>
@endif

{{-- Blok tanda tangan tabel: aman untuk DomPDF (tanpa flexbox).
     Urutan: jabatan, area tanda tangan (QR internal / kosong untuk manual), nama. --}}
<table style="width: 100%; border-collapse: collapse; margin-top: {{ (!empty($keteranganPeriode) || !empty($namaOrganisasi)) ? '15px' : '40px' }};">
    <tr>
        @forelse($items as $idx => $p)
            @php
                $sig = $signatures[$idx] ?? null;
                $isInternal = ($p['jenis'] ?? 'internal') === 'internal';

                $roleKey = strtolower($p['role'] ?? '');
                $jabatanKey = strtolower($p['jabatan'] ?? '');
                $namaKey = strtolower($p['nama'] ?? '');

                // Ambil nomor identitas dari berbagai kemungkinan field
                $nomorId = !empty($p['identitas_nomor'])
                    ? $p['identitas_nomor']
                    : (!empty($p['nidn']) ? $p['nidn'] : (!empty($p['nim']) ? $p['nim'] : null));

                if (!$nomorId && $sig && !empty($sig->nidn_penandatangan) && $sig->nidn_penandatangan !== '-') {
                    $nomorId = $sig->nidn_penandatangan;
                }

                $explicitLabel = !empty($p['identitas_label']) ? strtoupper(trim($p['identitas_label'])) : null;

                $isExplicitMhs = in_array($roleKey, ['ketua', 'sekretaris', 'bendahara', 'ketua_pelaksana', 'sekretaris_pelaksana', 'bendahara_pelaksana', 'anggota'])
                    || $explicitLabel === 'NIM';

                // Deteksi Dosen / Tenaga Pengajar / Pejabat Kampus
                $isDosen = !$isExplicitMhs && (
                    !empty($p['nidn'])
                    || in_array($explicitLabel, ['NIDN', 'NIP'])
                    || in_array($roleKey, ['pembina', 'pembimbing', 'kaprodi', 'prodi', 'dosen', 'dekan', 'wr3', 'bkhm', 'rektor'])
                    || str_contains($jabatanKey, 'dosen')
                    || str_contains($jabatanKey, 'pembina')
                    || str_contains($jabatanKey, 'pembimbing')
                    || str_contains($jabatanKey, 'kaprodi')
                    || str_contains($jabatanKey, 'prodi')
                    || str_contains($jabatanKey, 'dekan')
                    || str_contains($jabatanKey, 'kajur')
                    || str_contains($jabatanKey, 'jurusan')
                    || str_contains($jabatanKey, 'rektor')
                    || str_contains($jabatanKey, 'bkhm')
                    || str_contains($jabatanKey, 'nidn')
                    || str_contains($jabatanKey, 'nip')
                    || str_contains($namaKey, 'dr.')
                    || str_contains($namaKey, 'prof.')
                    || str_contains($namaKey, 'm.t.')
                    || str_contains($namaKey, 'm.kom')
                    || str_contains($namaKey, 'm.pd')
                    || str_contains($namaKey, 's.t., m.t')
                    || str_contains($namaKey, 's.kom., m.kom')
                );

                if ($explicitLabel) {
                    $labelNomor = $explicitLabel;
                } elseif ($isDosen) {
                    $labelNomor = 'NIDN';
                } elseif ($sig && !empty($sig->identitas_label)) {
                    $labelNomor = $sig->identitas_label;
                } else {
                    $labelNomor = 'NIM';
                }
            @endphp
            <td style="border: none; text-align: center; vertical-align: top; width: {{ $colWidth }}; padding: 0 6px;">
                <div style="font-weight: bold;">{{ $p['jabatan'] ?? ucfirst($p['role'] ?? 'Penandatangan') }},</div>

                {{-- Tinggi seragam agar nama antar kolom tetap sejajar. --}}
                <div style="height: 132px; text-align: center;">
                    @if($isInternal && $showQr && $sig)
                        @include('generator.partials.qr-verifikasi', ['signature' => $sig])
                    @endif
                </div>

                <div style="font-weight: bold; text-decoration: underline;">{{ $p['nama'] }}</div>
                @if($nomorId)
                    <div style="font-size: 8.5pt; color: #1e293b; margin-top: 2px;">{{ $labelNomor }}. {{ $nomorId }}</div>
                @endif
            </td>
        @empty
            <td style="border: none; text-align: center; vertical-align: top; width: 100%;">
                <div style="font-weight: bold;">Penandatangan,</div>
                <div style="height: 132px;"></div>
                <div>..........................</div>
            </td>
        @endforelse
    </tr>
</table>
