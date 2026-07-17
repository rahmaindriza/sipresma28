<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Lembar Lampiran Prestasi Rapor - {{ $siswa->nama }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
        }
        .header h2 {
            margin: 0;
            font-size: 14px;
            text-transform: uppercase;
        }
        .header h1 {
            margin: 2px 0;
            font-size: 16px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header p {
            margin: 0;
            font-size: 9px;
            font-style: italic;
        }
        .title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 15px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .meta-label {
            width: 18%;
        }
        .meta-colon {
            width: 2%;
        }
        .meta-value {
            width: 30%;
            font-weight: bold;
        }
        .achievement-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .achievement-table th, .achievement-table td {
            border: 1px solid #000;
            padding: 6px;
        }
        .achievement-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .font-bold {
            font-weight: bold;
        }
        .signature-container {
            width: 100%;
            margin-top: 30px;
        }
        .signature-box {
            width: 45%;
            float: left;
            text-align: center;
        }
        .signature-box-right {
            width: 45%;
            float: right;
            text-align: center;
        }
        .signature-space {
            height: 60px;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>

    <!-- Kop Surat Sekolah -->
    <div class="header">
        <h2>Pemerintah Kabupaten Pasaman Barat</h2>
        <h2>Dinas Pendidikan dan Kebudayaan</h2>
        <h1>SD Negeri 28 Kinali</h1>
        <p>Alamat: Kinali, Kec. Kinali, Kab. Pasaman Barat, Sumatera Barat, Kode Pos: 26367</p>
    </div>

    <!-- Title -->
    <div class="title">
        LEMBAR LAMPIRAN PRESTASI RAPOR SISWA
    </div>

    <!-- Metadata Siswa -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Nama Siswa</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ $siswa->nama }}</td>
            
            <td class="meta-label" style="padding-left: 40px;">Kelas</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ $kelas->nama_kelas ?? '-' }}</td>
        </tr>
        <tr>
            <td class="meta-label">NISN</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ $siswa->nisn }}</td>
            
            <td class="meta-label" style="padding-left: 40px;">Tahun Ajaran</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ $activeTa ? $activeTa->tahun : '-' }} ({{ $activeTa ? $activeTa->semester : '-' }})</td>
        </tr>
    </table>

    <!-- Achievement List Table -->
    <table class="achievement-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Nama Lomba / Bidang</th>
                <th style="width: 18%;">Kategori</th>
                <th style="width: 15%;">Tingkat</th>
                <th style="width: 12%;">Juara</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 8%;">Poin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($achievements as $idx => $p)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td>{{ $p->nama_lomba }}</td>
                    <td class="text-center">{{ $p->kategori }}</td>
                    <td class="text-center">{{ $p->tingkat }}</td>
                    <td class="text-center">{{ $p->juara }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($p->tanggal_penghargaan)->translatedFormat('d/m/Y') }}</td>
                    <td class="text-center font-bold">{{ $p->poin }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="font-style: italic; padding: 15px;">
                        Belum ada rekaman prestasi akademik maupun non-akademik di semester ini.
                    </td>
                </tr>
            @endforelse
            @if($achievements->isNotEmpty())
                <tr>
                    <td colspan="6" class="text-right font-bold" style="padding: 6px;">Total Akumulasi Poin Prestasi:</td>
                    <td class="text-center font-bold" style="background-color: #f9f9f9;">{{ $totalPoin }}</td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Keterangan Bobot Poin -->
    <div style="margin-top: 15px; font-size: 9px; border: 1px solid #ddd; padding: 8px; background-color: #fcfcfc; border-radius: 4px;">
        <strong>Keterangan Bobot Skor Poin Prestasi SDN 28 Kinali:</strong><br>
        - Tingkat Kecamatan: Juara 1 = 15 poin | Juara 2 = 10 poin | Juara 3 = 5 poin<br>
        - Tingkat Kabupaten: Juara 1 = 30 poin | Juara 2 = 25 poin | Juara 3 = 20 poin<br>
        - Tingkat Provinsi: Juara 1 = 60 poin | Juara 2 = 50 poin | Juara 3 = 40 poin<br>
        - Tingkat Nasional: Juara 1 = 100 poin | Juara 2 = 90 poin | Juara 3 = 80 poin<br>
        - Juara Harapan / Lainnya: 2 poin
    </div>

    <!-- Tanda Tangan -->
    <div class="signature-container">
        <div class="signature-box">
            <p>Mengetahui,</p>
            <p>Kepala Sekolah SDN 28 Kinali</p>
            <div class="signature-space"></div>
            <p class="font-bold" style="text-decoration: underline;">{{ $kepsek ? $kepsek->nama : 'Drs. H. Mulyadi, M.Pd.' }}</p>
            <p>NIP: {{ $kepsek ? $kepsek->nip : '196803051994031002' }}</p>
        </div>
        <div class="signature-box-right">
            <p>Kinali, {{ $tanggal_cetak }}</p>
            <p>Wali Kelas {{ $kelas->nama_kelas ?? '-' }}</p>
            <div class="signature-space"></div>
            <p class="font-bold" style="text-decoration: underline;">{{ $waliKelas ? $waliKelas->nama : 'Wali Kelas' }}</p>
            <p>NIP: {{ $waliKelas && $waliKelas->nip ? $waliKelas->nip : '-' }}</p>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>
