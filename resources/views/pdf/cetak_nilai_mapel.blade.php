<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Nilai Mata Pelajaran {{ $mapel->nama_mapel }} - Kelas {{ $kelas->nama_kelas }}</title>
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
            width: 15%;
        }
        .meta-colon {
            width: 2%;
        }
        .meta-value {
            width: 33%;
            font-weight: bold;
        }
        .grade-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .grade-table th, .grade-table td {
            border: 1px solid #000;
            padding: 6px 4px;
        }
        .grade-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
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

    <!-- Kop Surat -->
    <div class="header">
        <h2>Pemerintah Kabupaten Pasaman Barat</h2>
        <h2>Dinas Pendidikan dan Kebudayaan</h2>
        <h1>SD Negeri 28 Kinali</h1>
        <p>Alamat: Kinali, Kec. Kinali, Kab. Pasaman Barat, Sumatera Barat, Kode Pos: 26367</p>
    </div>

    <!-- Title -->
    <div class="title">
        LAPORAN REKAPITULASI NILAI MATA PELAJARAN
    </div>

    <!-- Metadata -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Mata Pelajaran</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ $mapel->nama_mapel }} (Umum)</td>
            
            <td class="meta-label" style="padding-left: 40px;">Kelas</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ $kelas->nama_kelas }}</td>
        </tr>
        <tr>
            <td class="meta-label">Batas KKM</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">75</td>
            
            <td class="meta-label" style="padding-left: 40px;">Semester</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ $activeTa ? $activeTa->semester : '-' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Tahun Ajaran</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ $activeTa ? $activeTa->tahun : '-' }}</td>
            
            <td class="meta-label" style="padding-left: 40px;">Wali Kelas</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ $waliKelas->nama }}</td>
        </tr>
    </table>

    <!-- Grades Table -->
    <table class="grade-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">NISN</th>
                <th style="width: 32%;">Nama Siswa</th>
                <th style="width: 9%;">Tugas (20%)</th>
                <th style="width: 9%;">UH (20%)</th>
                <th style="width: 9%;">UTS (30%)</th>
                <th style="width: 9%;">UAS (30%)</th>
                <th style="width: 12%;">Nilai Akhir</th>
                <th style="width: 10%;">Status KKM</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswas as $idx => $siswa)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center">{{ $siswa->nisn }}</td>
                    <td>{{ $siswa->nama_siswa }}</td>
                    <td class="text-center">{{ $siswa->nilai_tugas !== null ? number_format($siswa->nilai_tugas, 0) : '-' }}</td>
                    <td class="text-center">{{ $siswa->nilai_uh !== null ? number_format($siswa->nilai_uh, 0) : '-' }}</td>
                    <td class="text-center">{{ $siswa->nilai_uts !== null ? number_format($siswa->nilai_uts, 0) : '-' }}</td>
                    <td class="text-center">{{ $siswa->nilai_uas !== null ? number_format($siswa->nilai_uas, 0) : '-' }}</td>
                    <td class="text-center font-bold">{{ $siswa->nilai_akhir !== null ? number_format($siswa->nilai_akhir, 2) : '-' }}</td>
                    <td class="text-center font-bold">
                        @if($siswa->nilai_akhir !== null)
                            @if($siswa->nilai_akhir >= 75)
                                <span style="color: green;">Lulus</span>
                            @else
                                <span style="color: red;">Remedial</span>
                            @endif
                        @else
                            <span style="color: red;">Remedial</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="font-style: italic; color: #666; padding: 15px;">
                        Tidak ada data siswa ditemukan untuk kelas ini.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Signature Column -->
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
            <p>Wali Kelas {{ $kelas->nama_kelas }}</p>
            <div class="signature-space"></div>
            <p class="font-bold" style="text-decoration: underline;">{{ $waliKelas->nama }}</p>
            <p>NIP: {{ $waliKelas->nip ?? '-' }}</p>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>
