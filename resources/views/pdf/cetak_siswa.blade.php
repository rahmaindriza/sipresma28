<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Siswa Kelas {{ $kelas->nama_kelas }}</title>
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
        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .student-table th, .student-table td {
            border: 1px solid #000;
            padding: 6px;
        }
        .student-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }
        .text-center {
            text-align: center;
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
        DAFTAR DATA SISWA KELAS
    </div>

    <!-- Metadata -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Kelas</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ $kelas->nama_kelas }}</td>
            
            <td class="meta-label" style="padding-left: 40px;">Tahun Ajaran</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ $activeTa ? $activeTa->tahun : '-' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Wali Kelas</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ $waliKelas->nama }}</td>
            
            <td class="meta-label" style="padding-left: 40px;">Semester</td>
            <td class="meta-colon">:</td>
            <td class="meta-value">{{ $activeTa ? $activeTa->semester : '-' }}</td>
        </tr>
    </table>

    <!-- Student Table -->
    <table class="student-table">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 25%;">NISN</th>
                <th style="width: 47%;">Nama Lengkap</th>
                <th style="width: 20%;">Jenis Kelamin</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $idx => $siswa)
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td class="text-center font-bold">{{ $siswa->nisn }}</td>
                    <td>{{ $siswa->nama }}</td>
                    <td class="text-center">
                        {{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center" style="font-style: italic; padding: 15px;">
                        Tidak ada data siswa ditemukan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Signature -->
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
