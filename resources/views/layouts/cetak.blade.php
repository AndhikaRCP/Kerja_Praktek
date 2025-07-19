<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">

    <style>
        @page {
            size: A5 portrait;
            margin: 15mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }

        h1, h2, h3, p {
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 12px;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .text-start {
            text-align: left;
        }

        hr {
            border: 2px solid black;
            margin: 10px 0 20px 0;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>

    @yield('styles')
</head>
<body>

    @yield('content')

    {{-- Tombol Cetak & Tutup --}}
    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()">🖨️ Cetak</button>
        <button onclick="window.close()">Tutup</button>
    </div>

</body>
</html>
