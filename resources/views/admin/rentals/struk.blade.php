<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran #{{ $rental->id }}</title>
    <style>
        /* Reset & Base Style */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', Courier, monospace; /* Font ala mesin kasir */
            background-color: #f0f0f0;
            padding: 20px;
        }

        /* Container Kertas Struk */
        .receipt {
            max-width: 80mm; /* Lebar standar kertas thermal 80mm */
            margin: 0 auto;
            background: #fff;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        /* Elemen Struk */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .mb-1 { margin-bottom: 5px; }
        .mb-2 { margin-bottom: 10px; }
        
        .header {
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .header h2 { font-size: 18px; margin-bottom: 5px; }
        .header p { font-size: 12px; }

        .divider {
            border-top: 1px dashed #333; /* Garis putus-putus */
            margin: 10px 0;
        }

        .info-group {
            font-size: 12px;
            margin-bottom: 5px;
            display: flex;
            justify-content: space-between;
        }

        .item-row {
            font-size: 12px;
            margin-bottom: 8px;
        }
        
        .total-section {
            font-size: 14px;
            margin-top: 10px;
        }

        .footer {
            margin-top: 20px;
            font-size: 10px;
            text-align: center;
        }

        /* Area Tanda Tangan */
        .signature-area {
            margin-top: 25px;
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            text-align: center;
        }
        .sign-box {
            width: 45%;
        }
        .sign-line {
            margin-top: 40px;
            border-top: 1px solid #000;
        }

        /* Tombol Aksi (Tidak ikut terprint) */
        .actions {
            margin-top: 20px;
            text-align: center;
        }
        .btn {
            display: inline-block;
            padding: 8px 15px;
            margin: 0 5px;
            text-decoration: none;
            border-radius: 4px;
            font-family: sans-serif;
            font-size: 14px;
            cursor: pointer;
        }
        .btn-print { background-color: #333; color: white; border: none; }
        .btn-close { background-color: #ddd; color: #333; }

        /* SETTINGAN KHUSUS PRINT */
        @media print {
            body { 
                background: none; 
                padding: 0; 
            }
            .receipt {
                width: 100%;
                max-width: 100%;
                box-shadow: none;
                margin: 0;
                padding: 0;
            }
            .actions {
                display: none !important; /* Hilangkan tombol saat print */
            }
        }
    </style>
</head>
<body onload="window.print()"> <div class="receipt">
        
        <div class="header text-center">
            <h2>RENTAL MOTOR RPL</h2>
            <p>SMKN 1 Singkarak</p>
            <p>WhatsApp: 0812-3456-7890</p>
        </div>

        <div class="divider"></div>

        <div class="info-group">
            <span>No. Nota</span>
            <span>#{{ str_pad($rental->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="info-group">
            <span>Tanggal</span>
            <span>{{ date('d/m/Y H:i') }}</span>
        </div>
        <div class="info-group">
            <span>Kasir</span>
            <span>Admin</span> </div>
        <div class="info-group">
            <span>Penyewa</span>
            <span class="bold">{{ substr($rental->user->name, 0, 15) }}</span>
        </div>

        <div class="divider"></div>

        <div class="item-row">
            <div class="bold">{{ $rental->motor->merk }} ({{ $rental->motor->nopol }})</div>
            <div class="info-group">
                <span>{{ $rental->lama_sewa }} Hari x Rp {{ number_format($rental->motor->harga_sewa) }}</span>
                <span>{{ number_format($rental->total_bayar) }}</span>
            </div>
            <div style="font-size: 10px; color: #555;">
                Pinjam: {{ date('d/m/y', strtotime($rental->tgl_pinjam)) }} <br>
                Kembali: {{ date('d/m/y', strtotime($rental->tgl_kembali)) }}
            </div>
        </div>

        <div class="divider"></div>

        <div class="info-group total-section bold">
            <span>TOTAL BAYAR</span>
            <span style="font-size: 16px;">Rp {{ number_format($rental->total_bayar) }}</span>
        </div>
        <div class="info-group">
            <span>Status</span>
            <span>LUNAS</span>
        </div>

        <div class="signature-area">
            <div class="sign-box">
                <p>Admin / Petugas</p>
                <div class="sign-line"></div>
            </div>
            <div class="sign-box">
                <p>Penyewa</p>
                <div class="sign-line"></div>
                <p>{{ explode(' ', $rental->user->name)[0] }}</p>
            </div>
        </div>

        <div class="footer">
            <p>-- Terima Kasih --</p>
            <p>Simpan struk ini sebagai bukti sah.</p>
        </div>

        <div class="actions">
            <button onclick="window.print()" class="btn btn-print">Cetak Lagi</button>
            <button onclick="window.close()" class="btn btn-close">Tutup</button>
        </div>

    </div>

</body>
</html>