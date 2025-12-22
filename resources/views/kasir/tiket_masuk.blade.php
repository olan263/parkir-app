<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Tiket Masuk - {{ $data->kode_tiket }}</title>
    <style>
        /* Pengaturan kertas untuk DomPDF dan Browser */
        @page { 
            size: 80mm 150mm; /* Tinggi disesuaikan agar tidak terpotong di PDF */
            margin: 0; 
        }
        body { 
            width: 70mm; 
            margin: 0 auto; 
            padding: 10px; 
            font-family: 'Courier New', Courier, monospace; 
            font-size: 13px;
            color: #000;
            line-height: 1.2;
        }
        .text-center { text-align: center; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; width: 100%; }
        .barcode-box { 
            background: #000; 
            color: #fff; 
            padding: 8px 5px; 
            display: block; 
            font-size: 18px; 
            font-weight: bold; 
            letter-spacing: 2px;
            margin: 10px 0;
            text-align: center;
        }
        table { width: 100%; border-collapse: collapse; }
        .footer { font-size: 11px; margin-top: 10px; line-height: 1.4; text-align: left; }
    </style>
</head>
<body onload="handlePrint()">
    <div class="text-center">
        <h3 style="margin: 0; letter-spacing: 1px;">PARKIR DIGITAL</h3>
        <p style="margin: 2px 0; font-size: 11px;">Jl. Raya Parkir No. 123, Kota ABC</p>
        
        <div class="divider"></div>
        
        <p style="margin: 5px 0 0 0; font-weight: bold;">KODE TIKET</p>
        <div class="barcode-box">{{ $data->kode_tiket }}</div>
        
        <table style="margin-top: 5px;">
            <tr>
                <td align="left">JENIS</td>
                <td align="right"><strong>{{ strtoupper($data->jenis) }}</strong></td>
            </tr>
            <tr>
                <td align="left">MASUK</td>
                <td align="right">{{ \Carbon\Carbon::parse($data->waktu_masuk)->format('d/m/y H:i') }}</td>
            </tr>
        </table>

        <div class="divider"></div>
        
        <div class="footer">
            <strong>PERHATIAN:</strong><br>
            1. Simpan tiket ini sebagai bukti.<br>
            2. Jangan tinggalkan barang berharga.<br>
            3. Tiket hilang denda Rp 20.000.
        </div>
        
        <div class="divider"></div>
        <p style="font-size: 10px; margin: 0; font-style: italic;">Terima kasih atas kunjungan Anda</p>
    </div>

    <script>
        function handlePrint() {
            window.print();
            // Menutup jendela otomatis setelah print/cancel (hanya berfungsi jika dibuka via target="_blank")
            window.onafterprint = function() {
                window.close();
            };
        }
    </script>
</body>
</html>