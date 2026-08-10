<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Hasil Belajar - {{ $siswa->nama }}</title>
    <style>
        @page {
            margin: 20px 35px 25px 35px;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.35;
        }
        .biodata-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            margin-bottom: 5px;
        }
        .biodata-table td {
            padding: 2px 0;
            vertical-align: top;
            font-size: 10px;
        }
        .separator-line {
            border-top: 1.5px solid #000;
            margin: 4px 0;
        }
        .title-section {
            text-align: center;
            margin: 10px 0 15px 0;
        }
        .title-section h1 {
            font-size: 13px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .table-title {
            font-weight: bold;
            font-size: 11px;
            margin-top: 8px;
            margin-bottom: 4px;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .report-table th, .report-table td {
            border: 1px solid #000;
            padding: 5px 6px;
            vertical-align: middle;
        }
        .report-table th {
            font-weight: bold;
            text-align: center;
            font-size: 10px;
            background-color: #f2f2f2;
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
        .page-break {
            page-break-after: always;
        }
        
        /* Layout elements page 2 */
        .kokurikuler-box {
            border: 1px solid #000;
            padding: 8px;
            margin-bottom: 12px;
            min-height: 80px;
        }
        .kokurikuler-title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        
        .side-by-side-container {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
        }
        
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
        }
        .attendance-table th, .attendance-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 10px;
        }
        
        .catatan-box {
            border: 1px solid #000;
            padding: 8px;
            min-height: 65px;
        }
        
        .tanggapan-box {
            border: 1px solid #000;
            padding: 8px;
            margin-bottom: 15px;
            min-height: 65px;
        }
        
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .signature-table td {
            text-align: center;
            vertical-align: top;
            width: 33%;
            padding-bottom: 5px;
            font-size: 10px;
        }
        .signature-space {
            height: 55px;
        }
        
        .footer-table {
            width: 100%;
            border-collapse: collapse;
            position: absolute;
            bottom: 0;
            left: 0;
            border-top: 1px solid #000;
            padding-top: 3px;
        }
        .footer-table td {
            font-size: 9px;
            color: #000;
            padding: 2px 0;
        }
    </style>
</head>
<body>

    @php
        $kelasNama = $kelas->nama_kelas;
        $fase = 'B';
        preg_match('/\d+/', $kelasNama, $matches);
        if (!empty($matches)) {
            $num = (int)$matches[0];
            if ($num == 1 || $num == 2) $fase = 'A';
            elseif ($num == 3 || $num == 4) $fase = 'B';
            elseif ($num == 5 || $num == 6) $fase = 'C';
        } else {
            if (preg_match('/\b(vi|VI)\b/', $kelasNama)) $fase = 'C';
            elseif (preg_match('/\b(v|V)\b/', $kelasNama)) $fase = 'C';
            elseif (preg_match('/\b(iv|IV)\b/', $kelasNama)) $fase = 'B';
            elseif (preg_match('/\b(iii|III)\b/', $kelasNama)) $fase = 'B';
            elseif (preg_match('/\b(ii|II)\b/', $kelasNama)) $fase = 'A';
            elseif (preg_match('/\b(i|I)\b/', $kelasNama)) $fase = 'A';
        }
    @endphp



    <!-- Student Header -->
    <table class="biodata-table">
        <tr>
            <td style="width: 13%;">Nama Murid</td>
            <td style="width: 2%;">:</td>
            <td style="width: 40%;">{{ $siswa->nama }}</td>
            
            <td style="width: 15%;">Kelas</td>
            <td style="width: 2%;">:</td>
            <td style="width: 28%;">{{ $kelas->nama_kelas }}</td>
        </tr>
        <tr>
            <td>NIS/NISN</td>
            <td>:</td>
            <td>{{ $siswa->nis ?? '-' }} / {{ $siswa->nisn }}</td>
            
            <td>Fase</td>
            <td>:</td>
            <td>{{ $fase }}</td>
        </tr>
        <tr>
            <td>Sekolah</td>
            <td>:</td>
            <td>SDN 28 Kinali</td>
            
            <td>Semester</td>
            <td>:</td>
            <td>{{ strtolower($activeTa->semester) == 'ganjil' ? '1' : '2' }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $siswa->alamat }}</td>
            
            <td>Tahun Ajaran</td>
            <td>:</td>
            <td>{{ $activeTa->tahun }}</td>
        </tr>
    </table>
    
    <!-- Bottom separator -->
    <div class="separator-line"></div>
    
    <!-- Title -->
    <div class="title-section">
        <h1>LAPORAN HASIL BELAJAR</h1>
    </div>
    
    <table class="report-table" style="margin-top: 10px;">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Mata Pelajaran</th>
                <th style="width: 10%;">Nilai Akhir</th>
                <th style="width: 55%;">Capaian Kompetensi</th>
            </tr>
        </thead>
        <tbody>
            <!-- Kelompok A -->
            <tr>
                <td colspan="4" class="font-bold" style="background-color: #f2f2f2; text-align: left; padding: 4px 6px;">Kelompok A</td>
            </tr>
            @php $no = 1; @endphp
            @forelse($grades->where('mapel.jenis_mapel', 'umum') as $grade)
                @php
                    $highest = $grade->capaian_tertinggi;
                    $lowest = $grade->capaian_perlu_peningkatan;
                    
                    $highestText = '';
                    if ($highest) {
                        if (\Illuminate\Support\Str::startsWith(strtolower(trim($highest)), ['mencapai', 'kompetensi'])) {
                            $highestText = $highest;
                        } else {
                            $highestText = 'Mencapai Kompetensi dengan sangat baik dalam hal ' . $highest . '.';
                        }
                    }
                    
                    $lowestText = '';
                    if ($lowest) {
                        if (\Illuminate\Support\Str::startsWith(strtolower(trim($lowest)), ['perlu', 'peningkatan'])) {
                            $lowestText = $lowest;
                        } else {
                            $lowestText = 'Perlu peningkatan dalam hal ' . $lowest . '.';
                        }
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ $grade->mapel->nama_mapel }}</td>
                    <td class="text-center">{{ round($grade->nilai_akhir, 0) }}</td>
                    <td style="font-size: 9.5px; line-height: 1.3; text-align: justify; padding: 4px 6px;">
                        @if($highestText || $lowestText)
                            @if($highestText)
                                <div style="margin-bottom: 3px;">{{ $highestText }}</div>
                            @endif
                            @if($lowestText)
                                <div>{{ $lowestText }}</div>
                            @endif
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center italic" style="padding: 10px;">Tidak ada data nilai Kelompok A.</td>
                </tr>
            @endforelse

            <!-- Kelompok B -->
            <tr>
                <td colspan="4" class="font-bold" style="background-color: #f2f2f2; text-align: left; padding: 4px 6px;">Kelompok B</td>
            </tr>
            @php $noPilihan = 1; @endphp
            @forelse($grades->where('mapel.jenis_mapel', 'khusus') as $grade)
                @php
                    $highest = $grade->capaian_tertinggi;
                    $lowest = $grade->capaian_perlu_peningkatan;
                    
                    $highestText = '';
                    if ($highest) {
                        if (\Illuminate\Support\Str::startsWith(strtolower(trim($highest)), ['mencapai', 'kompetensi'])) {
                            $highestText = $highest;
                        } else {
                            $highestText = 'Mencapai Kompetensi dengan sangat baik dalam hal ' . $highest . '.';
                        }
                    }
                    
                    $lowestText = '';
                    if ($lowest) {
                        if (\Illuminate\Support\Str::startsWith(strtolower(trim($lowest)), ['perlu', 'peningkatan'])) {
                            $lowestText = $lowest;
                        } else {
                            $lowestText = 'Perlu peningkatan dalam hal ' . $lowest . '.';
                        }
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $noPilihan++ }}</td>
                    <td>{{ $grade->mapel->nama_mapel }}</td>
                    <td class="text-center">{{ round($grade->nilai_akhir, 0) }}</td>
                    <td style="font-size: 9.5px; line-height: 1.3; text-align: justify; padding: 4px 6px;">
                        @if($highestText || $lowestText)
                            @if($highestText)
                                <div style="margin-bottom: 3px;">{{ $highestText }}</div>
                            @endif
                            @if($lowestText)
                                <div>{{ $lowestText }}</div>
                            @endif
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center italic" style="padding: 10px;">Tidak ada data nilai Kelompok B.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Footer Page 1 -->
    <table class="footer-table">
        <tr>
            <td style="text-align: left; width: 70%;">Kelas {{ $kelas->nama_kelas }} | {{ strtoupper($siswa->nama) }} | {{ $siswa->nis ?? '-' }}</td>
            <td style="text-align: right; width: 30%;">Halaman : 1</td>
        </tr>
    </table>

    <div class="page-break"></div>

    <!-- ==================== Halaman 2 ==================== -->
    
    <!-- Student Header -->
    <table class="biodata-table">
        <tr>
            <td style="width: 13%;">Nama Murid</td>
            <td style="width: 2%;">:</td>
            <td style="width: 40%;">{{ $siswa->nama }}</td>
            
            <td style="width: 15%;">Kelas</td>
            <td style="width: 2%;">:</td>
            <td style="width: 28%;">{{ $kelas->nama_kelas }}</td>
        </tr>
        <tr>
            <td>NIS/NISN</td>
            <td>:</td>
            <td>{{ $siswa->nis ?? '-' }} / {{ $siswa->nisn }}</td>
            
            <td>Fase</td>
            <td>:</td>
            <td>{{ $fase }}</td>
        </tr>
        <tr>
            <td>Sekolah</td>
            <td>:</td>
            <td>SDN 28 Kinali</td>
            
            <td>Semester</td>
            <td>:</td>
            <td>{{ strtolower($activeTa->semester) == 'ganjil' ? '1' : '2' }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $siswa->alamat }}</td>
            
            <td>Tahun Ajaran</td>
            <td>:</td>
            <td>{{ $activeTa->tahun }}</td>
        </tr>
    </table>
    
    <!-- Bottom separator -->
    <div class="separator-line"></div>
    
    <!-- Kokurikuler Box -->
    <table class="report-table" style="margin-bottom: 12px; width: 100%;">
        <thead>
            <tr>
                <th class="text-center font-bold" style="background-color: #f2f2f2; font-size: 10px;">Kokurikuler</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="height: 50px; padding: 6px; vertical-align: top; font-size: 10px;">
                </td>
            </tr>
        </tbody>
    </table>
    
    <!-- Ekstrakurikuler Table -->
    @php
        $ekskuls = [];
        if ($rapor) {
            if ($rapor->ekstrakurikuler) {
                $ekskuls = is_string($rapor->ekstrakurikuler)
                    ? json_decode($rapor->ekstrakurikuler, true)
                    : $rapor->ekstrakurikuler;
            }
            
            // Fallback to old columns if JSON is empty
            if (empty($ekskuls) && ($rapor->ekskul_1_nama || $rapor->ekskul_2_nama)) {
                if ($rapor->ekskul_1_nama) {
                    $ekskuls[] = ['nama' => $rapor->ekskul_1_nama, 'ket' => $rapor->ekskul_1_ket ?? ''];
                }
                if ($rapor->ekskul_2_nama) {
                    $ekskuls[] = ['nama' => $rapor->ekskul_2_nama, 'ket' => $rapor->ekskul_2_ket ?? ''];
                }
            }
        }
        
        // Pad to ensure at least 2 rows
        $rowCount = count($ekskuls);
        if ($rowCount < 2) {
            $needed = 2 - $rowCount;
            for ($i = 0; $i < $needed; $i++) {
                $ekskuls[] = ['nama' => '', 'ket' => ''];
            }
        }
    @endphp
    <table class="report-table" style="margin-bottom: 12px; width: 100%;">
        <thead>
            <tr>
                <th style="width: 8%; text-align: center; background-color: #f2f2f2; font-size: 10px;">No</th>
                <th style="width: 32%; text-align: center; background-color: #f2f2f2; font-size: 10px;">Ekstrakurikuler</th>
                <th style="width: 60%; text-align: center; background-color: #f2f2f2; font-size: 10px;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ekskuls as $index => $ekskul)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $ekskul['nama'] ?? '' }}</td>
                <td>{{ $ekskul['ket'] ?? '' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Attendance and Catatan Walas in side-by-side -->
    <table class="side-by-side-container" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td style="width: 40%; vertical-align: top; padding-right: 15px;">
                <table class="report-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th colspan="2" class="text-center font-bold" style="background-color: #f2f2f2; font-size: 10px;">Ketidakhadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="width: 50%; padding: 4px 6px;">Sakit</td>
                            <td style="width: 50%; padding: 4px 6px;" class="font-bold text-center">: {{ $rapor && $rapor->sakit !== null ? $rapor->sakit . ' hari' : '' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 6px;">Izin</td>
                            <td style="padding: 4px 6px;" class="font-bold text-center">: {{ $rapor && $rapor->izin !== null ? $rapor->izin . ' hari' : '' }}</td>
                        </tr>
                        <tr>
                            <td style="padding: 4px 6px;">Tanpa Keterangan</td>
                            <td style="padding: 4px 6px;" class="font-bold text-center">: {{ $rapor && $rapor->alpha !== null ? $rapor->alpha . ' hari' : '' }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td style="width: 60%; vertical-align: top;">
                <table class="report-table" style="width: 100%;">
                    <thead>
                        <tr>
                            <th class="text-center font-bold" style="background-color: #f2f2f2; font-size: 10px;">Catatan Wali Kelas</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="height: 62px; padding: 6px; vertical-align: top; font-size: 10px; line-height: 1.35; text-align: justify;">
                                {{ $rapor && $rapor->catatan_walas ? $rapor->catatan_walas : '' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    
    <!-- Keterangan Kelulusan -->
    @if(strtolower($activeTa->semester) == 'genap')
    <div style="border: 1px solid #000; padding: 6px; margin-bottom: 12px; font-weight: bold; font-size: 10px; text-align: center;">
        Keterangan Kelulusan : {{ $rapor && $rapor->keterangan_kelulusan ? $rapor->keterangan_kelulusan : '' }}
    </div>
    @endif

    <!-- Tanggapan Orang Tua -->
    <table class="report-table" style="margin-bottom: 15px; width: 100%;">
        <thead>
            <tr>
                <th class="text-center font-bold" style="background-color: #f2f2f2; font-size: 10px;">Tanggapan Orang Tua/Wali Murid</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="height: 50px; padding: 6px;"></td>
            </tr>
        </tbody>
    </table>
    
    <!-- Signatures -->
    @php
        $tgl = \Carbon\Carbon::now()->translatedFormat('d F Y');
        if ($tanggal_cetak) {
            $tgl = $tanggal_cetak;
        }
    @endphp
    <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
        <tr>
            <td style="width: 33%;"></td>
            <td style="width: 33%;"></td>
            <td style="width: 33%; text-align: center; font-size: 10px;">Katiagan, {{ $tgl }}</td>
        </tr>
    </table>

    <table class="signature-table" style="margin-top: 5px;">
        <tr>
            <td>
                <p style="margin: 0 0 5px 0;">Orang Tua/Wali,</p>
                <div class="signature-space"></div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">..........................................</p>
            </td>
            <td>
                <p style="margin: 0 0 5px 0;">Kepala Sekolah,</p>
                <div class="signature-space"></div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $kepsek && $kepsek->nama != 'Rahma Indriza' ? $kepsek->nama : 'Fedri Sugianto, S.Pd' }}</p>
                <p style="margin: 0; font-size: 10px;">NIP. {{ $kepsek && $kepsek->nama != 'Rahma Indriza' ? $kepsek->nip : '198208112021211002' }}</p>
            </td>
            <td>
                <p style="margin: 0 0 5px 0;">Wali Kelas,</p>
                <div class="signature-space"></div>
                <p style="margin: 0; font-weight: bold; text-decoration: underline;">{{ $namaWaliKelas }}</p>
                <p style="margin: 0; font-size: 10px;">NIP. {{ $nipWaliKelas }}</p>
            </td>
        </tr>
    </table>
    
    <!-- Footer Page 2 -->
    <table class="footer-table">
        <tr>
            <td style="text-align: left; width: 70%;">Kelas {{ $kelas->nama_kelas }} | {{ strtoupper($siswa->nama) }} | {{ $siswa->nis ?? '-' }}</td>
            <td style="text-align: right; width: 30%;">Halaman : 2</td>
        </tr>
    </table>

</body>
</html>
