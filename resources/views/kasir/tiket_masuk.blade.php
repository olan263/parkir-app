<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Tiket Masuk - {{ $data->kode_tiket }}</title>
    <style>
        /* Optimasi Khusus DomPDF */
        @page { 
            margin: 0; 
        }
        body { 
            margin: 0;
            padding: 10px;
            font-family: 'Courier', sans-serif; /* Font standar printer thermal */
            font-size: 12px;
            line-height: 1.2;
        }
        .text-center { text-align: center; }
        .divider { 
            border-top: 1px dashed #000; 
            margin: 8px 0; 
        }
        /* Kotak Kode Tiket agar terlihat seperti barcode manual */
        .barcode-box { 
            border: 2px solid #000;
            padding: 8px; 
            font-size: 18px; 
            font-weight: bold; 
            letter-spacing: 4px;
            margin: 10px 0;
            text-align: center;
        }
        table { width: 100%; border-collapse: collapse; }
        .footer { font-size: 10px; margin-top: 8px; }
    </style>
</head>
<body>
    <div class="text-center">
        <h3 style="margin: 0; font-size: 16px;">PARKIR DIGITAL</h3>
        <p style="margin: 2px 0; font-size: 10px;">Jl. Raya Parkir No. 123, Kota ABC</p>
        
        <div class="divider"></div>
        
        <p style="margin: 5px 0 2px 0; font-weight: bold;">KODE TIKET</p>
        <div class="barcode-box">{{ $data->kode_tiket }}</div>
        
        <table>
            <tr>
                <td align="left">JENIS</td>
                <td align="right"><strong>{{ strtoupper($data->jenis) }}</strong></td>
            </tr>
            <tr>
                <td align="left">TGL/JAM</td>
                <td align="right">{{ $data->waktu_masuk->format('d/m/y H:i') }}</td>
            </tr>
        </table>

        <div class="divider"></div>
        
        <div class="footer" style="text-align: left;">
            <strong>PERHATIAN:</strong><br>
            1. Simpan tiket ini sebagai bukti.<br>
            2. Jangan tinggalkan barang berharga.<br>
            3. Tiket hilang denda Rp 20.000.
        </div>
        
        <div class="divider"></div>
        <p style="font-size: 10px; margin: 0; font-style: italic;">*** TERIMA KASIH ***</p>
    </div>
</body>
</html>