<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Slip Gaji</title>
    <style>
        * {
            font-family: Arial, sans-serif;
            font-size: 11px;
        }

        body {}

        .w-100 {
            width: 100%;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .border-top {
            border-top: 1px solid #000;
        }

        table {
            border-collapse: collapse;
            margin-top: 10px;
            width: 100%;
        }

        th,
        td {
            padding: 4px 6px;
            vertical-align: top;
        }

        .section-title {
            font-weight: bold;
            margin-top: 20px;
            border-top: 1px solid #000;
            padding-top: 6px;
        }

        .logo {
            height: 50px;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <table>
        <tr>
            <td style="width: 20%">
                <img src="{{ public_path('logo-onekreasi.png') }}" alt="Logo" class="logo">
            </td>
            <td style="width: 80%" style="vertical-align: middle">
                <strong style="font-size: 14px;">PT. Kreasi Berdikari Infomedia</strong><br>
                <small style="width: 150px;display:block">
                    Perkantoran Tanjung Mas Raya,
                    Blok B1/No. 44 Tanjung Barat,
                    Jagakarsa – Jakarta Selatan, 12530
                </small>
            </td>
        </tr>
    </table>

    <div style="border-top:1px solid #000;margin-top:10px"></div>
    <!-- Title -->
    <h2 class="text-center" style="margin-top: 30px;font-size:20px">SLIP GAJI</h4>

        <!-- Employee Info -->
        <table style="margin-top: 10px;">
            <tr>
                <td style="width: 20%">ID</td>
                <td> : {{ $payroll->employee->code ?? '-' }}</td>
            </tr>
            <tr>
                <td>NAMA</td>
                <td>: {{ $payroll->employee->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>JABATAN</td>
                <td>: {{ $payroll->employee->formatted_position ?? '-' }}</td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>: {{ $payroll->employee->nik ?? '-' }}</td>
            </tr>
            <tr>
                <td>PERIODE</td>
                <td>: {{ $payroll->period_start->format('d M Y') }} - {{ $payroll->period_end->format('d M Y') }}
                </td>
            </tr>
        </table>

        <!-- Payroll Items -->
        <table style="margin-top: 20px;">
            <tr style="border:1px solid #000">
                <th class="text-left" style="">PENERIMAAN</th>
                <th class="text-left">POTONGAN</th>
            </tr>
            <tr>
                <td>
                    <table>
                        @foreach ($payroll->items->where('type', 'allowance') as $item)
                            <tr style="border-right:1px solid #000">
                                <td style="padding-start: 0">{{ $item->name }}</td>
                                <td>:</td>
                                <td style="padding-start: 0" class="text-right"> Rp
                                    {{ number_format($item->amount, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
                <td>
                    <table>
                        @foreach ($payroll->items->where('type', 'deduction') as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td class="text-right">:</td>
                                <td class="text-right">Rp {{ number_format($item->amount, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </table>
                </td>
            </tr>

            <!-- Total -->
            <tr style="border:1px solid #000">
                <td class="text-right" style="font-weight: bold;">
                    TOTAL PENERIMAAN : Rp
                    {{ number_format($payroll->total_allowance + $payroll->base_salary, 0, ',', '.') }}
                </td>
                <td class="text-right" style="font-weight: bold;">
                    TOTAL POTONGAN : Rp {{ number_format($payroll->total_deduction, 0, ',', '.') }}
                </td>
            </tr>
        </table>

        <table>
            <tr style="border:1px solid #000">
                <td class="" style="font-weight: bold;">
                    TOTAL YANG DITERIMA
                </td>
                <td>:</td>
                <td class="text-right" style="font-weight: bold;">
                    Rp {{ number_format($payroll->total_salary, 0, ',', '.') }}
                </td>
            </tr>
        </table>

        <!-- Footer -->
        <table style="margin-top: 30px;">
            <tr>
                <td style="width: 80%;"></td>
                <td class="text-center">
                    Diterima Oleh<br><br><br><br>
                    <u>{{ $payroll->employee->name }}</u>
                </td>
            </tr>
        </table>

</body>

</html>
