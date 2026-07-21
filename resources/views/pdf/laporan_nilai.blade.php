<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Belajar - {{ $siswa->nama }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
        }
        .header h2 {
            margin: 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header h1 {
            margin: 2px 0;
            font-size: 18px;
            text-transform: uppercase;
            font-weight: bold;
        }
        .header p {
            margin: 0;
            font-size: 10px;
            italic;
        }
        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
            text-decoration: underline;
        }
        .profile-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .profile-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        .profile-label {
            width: 18%;
        }
        .profile-colon {
            width: 2%;
        }
        .profile-value {
            width: 30%;
            font-weight: bold;
        }
        .grade-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .grade-table th, .grade-table td {
            border: 1px solid #000;
            padding: 6px;
        }
        .grade-table th {
            background-color: #f2f2f2;
            text-align: center;
            font-weight: bold;
        }
        .text-center {
            text-align: center;
        }
        .font-bold {
            font-weight: bold;
        }
        .section-header {
            background-color: #fafafa;
            font-weight: bold;
            font-style: italic;
        }
        .achievement-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .achievement-table th, .achievement-table td {
            border: 1px solid #000;
            padding: 6px;
        }
        .achievement-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .signature-container {
            width: 100%;
            margin-top: 40px;
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
            height: 70px;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>

    <!-- Header / Kop Surat -->
    <div class="header">
        <h2>Pemerintah Kabupaten Pasaman Barat</h2>
        <h2>Dinas Pendidikan dan Kebudayaan</h2>
        <h1>SD Negeri 28 Kinali</h1>
        <p>Alamat: Kinali, Kec. Kinali, Kab. Pasaman Barat, Sumatera Barat, Kode Pos: 26367</p>
    </div>

    <!-- Title -->
    <div class="title">
        Laporan Hasil Evaluasi Nilai Belajar Siswa
    </div>

    <!-- Student & Class Metadata Info -->
    <table class="profile-table">
        <tr>
            <td class="profile-label">Nama Siswa</td>
            <td class="profile-colon">:</td>
            <td class="profile-value">{{ $siswa->nama }}</td>
            
            <td class="profile-label" style="padding-left: 50px;">Kelas</td>
            <td class="profile-colon">:</td>
            <td class="profile-value">{{ $kelas->nama_kelas }}</td>
        </tr>
        <tr>
            <td class="profile-label">NISN</td>
            <td class="profile-colon">:</td>
            <td class="profile-value">{{ $siswa->nisn }}</td>
            
            <td class="profile-label" style="padding-left: 50px;">Semester</td>
            <td class="profile-colon">:</td>
            <td class="profile-value">{{ $activeTa->semester }}</td>
        </tr>
        <tr>
            <td class="profile-label">Sekolah</td>
            <td class="profile-colon">:</td>
            <td class="profile-value">SDN 28 Kinali</td>
            
            <td class="profile-label" style="padding-left: 50px;">Tahun Ajaran</td>
            <td class="profile-colon">:</td>
            <td class="profile-value">{{ $activeTa->tahun }}</td>
        </tr>
    </table>

    <!-- Subject Grades Table -->
    <table class="grade-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 45%;">Mata Pelajaran</th>
                <th style="width: 12%;">KKM</th>
                <th style="width: 18%;">Nilai Akhir</th>
                <th style="width: 20%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            <!-- Mata Pelajaran Umum -->
            <tr class="section-header">
                <td colspan="5">A. Mata Pelajaran Umum</td>
            </tr>
            @php $no = 1; $totalScore = 0; $countMapels = 0; @endphp
            @foreach($grades->where('mapel.jenis_mapel', 'umum') as $grade)
            @php 
                $totalScore += $grade->nilai_akhir; 
                $countMapels++;
            @endphp
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ $grade->mapel->nama_mapel }}</td>
                <td class="text-center">{{ $grade->mapel->kkm }}</td>
                <td class="text-center font-bold">{{ round($grade->nilai_akhir, 0) }}</td>
                <td class="text-center">
                    {{ $grade->nilai_akhir >= $grade->mapel->kkm ? 'TUNTAS' : 'REMEDIAL' }}
                </td>
            </tr>
            @endforeach

            <!-- Mata Pelajaran Khusus -->
            <tr class="section-header">
                <td colspan="5">B. Mata Pelajaran Khusus</td>
            </tr>
            @foreach($grades->where('mapel.jenis_mapel', 'khusus') as $grade)
            @php 
                $totalScore += $grade->nilai_akhir; 
                $countMapels++;
            @endphp
            <tr>
                <td class="text-center">{{ $no++ }}</td>
                <td>{{ $grade->mapel->nama_mapel }}</td>
                <td class="text-center">{{ $grade->mapel->kkm }}</td>
                <td class="text-center font-bold">{{ round($grade->nilai_akhir, 0) }}</td>
                <td class="text-center">
                    {{ $grade->nilai_akhir >= $grade->mapel->kkm ? 'TUNTAS' : 'REMEDIAL' }}
                </td>
            </tr>
            @endforeach

            <!-- Summary Scores -->
            <tr>
                <td colspan="3" class="font-bold" style="text-align: right; padding-right: 15px;">Jumlah Nilai</td>
                <td class="text-center font-bold">{{ round($totalScore, 0) }}</td>
                <td style="background-color: #f9f9f9;"></td>
            </tr>
            <tr>
                <td colspan="3" class="font-bold" style="text-align: right; padding-right: 15px;">Rata-rata Nilai</td>
                <td class="text-center font-bold text-blue">{{ $rata_rata }}</td>
                <td style="background-color: #f9f9f9;"></td>
            </tr>
            <tr>
                <td colspan="3" class="font-bold" style="text-align: right; padding-right: 15px;">Peringkat Kelas</td>
                <td class="text-center font-bold" style="color: #d97706;">{{ $rank }}</td>
                <td style="background-color: #f9f9f9;"></td>
            </tr>
        </tbody>
    </table>

    <!-- Student Achievements (Prestasi) -->
    <h3 style="font-size: 13px; font-weight: bold; margin-bottom: 5px; text-transform: uppercase;">C. Prestasi Siswa</h3>
    <table class="achievement-table">
        <thead>
            <tr>
                <th style="width: 5%; text-align: center;">No</th>
                <th style="width: 25%;">Jenis Prestasi</th>
                <th style="width: 70%;">Keterangan Penghargaan</th>
            </tr>
        </thead>
        <tbody>
            @if($achievements->isEmpty())
            <tr>
                <td colspan="3" class="text-center" style="color: #666; font-style: italic;">Siswa belum mencatatkan prestasi pada semester ini.</td>
            </tr>
            @else
                @php $noAch = 1; @endphp
                @foreach($achievements as $ach)
                <tr>
                    <td class="text-center">{{ $noAch++ }}</td>
                    <td class="font-bold">{{ $ach->jenis_prestasi }}</td>
                    <td>{{ $ach->keterangan }} (Tanggal: {{ \Carbon\Carbon::parse($ach->tanggal)->format('d/m/Y') }})</td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>

    <!-- Signature Columns -->
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
            <p class="font-bold" style="text-decoration: underline;">{{ $namaWaliKelas }}</p>
            <p>NIP: {{ $nipWaliKelas }}</p>
        </div>
        <div class="clear"></div>
    </div>

</body>
</html>
