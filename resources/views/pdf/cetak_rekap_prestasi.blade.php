<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Prestasi Siswa SDN 28 Kinali</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            color: #333;
            line-height: 1.3;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
        }
        .header h2 {
            margin: 0;
            font-size: 13px;
            text-transform: uppercase;
        }
        .header h1 {
            margin: 2px 0;
            font-size: 15px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header p {
            margin: 0;
            font-size: 8px;
            font-style: italic;
        }
        .title {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 15px;
        }
        .info-block {
            width: 100%;
            margin-bottom: 10px;
        }
        .info-block table {
            width: 100%;
            border-collapse: collapse;
        }
        .info-block td {
            padding: 2px 0;
        }
        .rekap-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .rekap-table th, .rekap-table td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: middle;
        }
        .rekap-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
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
            margin-top: 25px;
        }
        .signature-box {
            width: 35%;
            float: right;
            text-align: center;
        }
        .signature-space {
            height: 55px;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>

    <!-- Kop Surat -->
    <div class="header">
        <h2>Pemerintah Kabupaten Pasaman Barat</h2>
        <h2>Dinas Pendidikan dan Kebudayaan</h2>
        <h1>SD Negeri 28 Kinali</h1>
        <p>Alamat: Kinali, Kec. Kinali, Kab. Pasaman Barat, Sumatera Barat, Kode Pos: 26367</p>
    </div>

    <!-- Title -->
    <div class="title">
        LAPORAN REKAPITULASI PRESTASI SISWA
    </div>

    <!-- Metadata / Filter Info -->
    <div class="info-block">
        <table>
            <tr>
                <td style="width: 12%;">Tahun Ajaran</td>
                <td style="width: 2%;">:</td>
                <td style="width: 36%; font-weight: bold;">{{ $activeTa ? $activeTa->tahun . ' (' . $activeTa->semester . ')' : '-' }}</td>
                
                <td style="width: 12%;">Filter Kelas</td>
                <td style="width: 2%;">:</td>
                <td style="width: 36%; font-weight: bold;">{{ $kelasText }}</td>
            </tr>
            <tr>
                <td>Kategori</td>
                <td>:</td>
                <td style="font-weight: bold;">{{ $kategori }}</td>
                
                <td>Tanggal Cetak</td>
                <td>:</td>
                <td style="font-weight: bold;">{{ $tanggal_cetak }}</td>
            </tr>
        </table>
    </div>

    <!-- Rekap Table -->
    <table class="rekap-table">
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 18%;">Nama Siswa</th>
                <th style="width: 8%;">NISN</th>
                <th style="width: 8%;">Kelas</th>
                <th style="width: 22%;">Nama Perlombaan / Bidang</th>
                <th style="width: 10%;">Kategori</th>
                <th style="width: 10%;">Tingkat</th>
                <th style="width: 8%;">Juara</th>
                <th style="width: 6%;">Poin</th>
                <th style="width: 6%;">Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($prestasis as $idx => $p)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="font-bold">{{ $p->siswa->nama }}</td>
                    <td class="text-center">{{ $p->siswa->nisn }}</td>
                    <td class="text-center">{{ $p->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $p->nama_lomba }}</td>
                    <td class="text-center">{{ $p->kategori }}</td>
                    <td class="text-center">{{ $p->tingkat }}</td>
                    <td class="text-center">{{ $p->juara }}</td>
                    <td class="text-center font-bold">{{ $p->poin }}</td>
                    <td class="text-center">{{ \Carbon\Carbon::parse($p->tanggal_penghargaan)->translatedFormat('d/m/Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="font-style: italic; padding: 15px;">
                        Tidak ada data prestasi siswa yang sesuai dengan filter pencarian laporan.
                    </td>
                </tr>
            @endforelse
            @if($prestasis->isNotEmpty())
                <tr>
                    <td colspan="8" class="text-right font-bold">Total Akumulasi Poin Seluruh Siswa:</td>
                    <td class="text-center font-bold" style="background-color: #f2f2f2;">{{ $prestasis->sum('poin') }}</td>
                    <td style="background-color: #f2f2f2;"></td>
                </tr>
            @endif
        </tbody>
    </table>

    <!-- Tanda Tangan Kepala Sekolah -->
    <div class="signature-container">
        <div class="signature-box">
            <p>Mengetahui,</p>
            <p>Kepala Sekolah SDN 28 Kinali</p>
            <div class="signature-space"></div>
            <p class="font-bold" style="text-decoration: underline;">{{ $kepsek ? $kepsek->nama : 'Drs. H. Mulyadi, M.Pd.' }}</p>
            <p>NIP: {{ $kepsek ? $kepsek->nip : '196803051994031002' }}</p>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>
