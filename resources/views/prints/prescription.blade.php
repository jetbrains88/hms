<!DOCTYPE html>
<html>
<head>
    <title>Prescription</title>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0;
            padding: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: "DejaVu Sans", sans-serif;
        }

        .header {
            margin: 0;
            padding: 0;
            border-bottom: 2px solid #333;
            width: 100%;
            /* No top/bottom padding here so the table defines the height */
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo-cell {
            /*border-right: 2px solid #333;*/
            width: 120px; /* Slightly wider for better proportions */
            text-align: center;
            vertical-align: middle; /* Centers logo against text */
            padding: 10px;
        }

        .logo {
            /* This height matches the 4 lines of text + divider */
            height: 200px;
            width: auto;
            display: block;
            margin: 0 auto;
        }

        .text-cell {
            vertical-align: middle;
            padding: 10px 0;
            text-align: center;
        }

        .header-text {

            display: inline-block;
            text-align: center;
        }

        .heading {
            margin: 0;
            padding: 0;
            line-height: 1.2; /* Controls the height of the text block */
            font-size: 15px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .divider {
            margin-top: 2px;
            line-height: 1;
            letter-spacing: 4px;
            font-size: 16px;
        }

        .main-content {
            padding: 15px 30px;
        }

        .patient-info {
            margin: 10px 0;
            padding: 10px;
            background: #f4f4f4;
            border-radius: 4px;
        }

        .patient-info table {
            width: 100%;
            border-collapse: collapse;
        }

        .patient-info td {
            border: none;
            padding: 4px 0;
        }

        .rx-symbol {
            font-size: 28px;
            font-weight: bold;
            margin: 20px 0 10px 0;
        }

        .rx-box {
            border: 2px solid #333;
            padding: 5px 15px;
        }

        .prescription-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            border: 2px solid #333;
        }

        .prescription-table th {
            background-color: #333;
            color: white;
            padding: 8px;
        }

        .prescription-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        .notes {
            margin: 15px 0;
            padding: 10px;
            background-color: #f9f9f9;
            border-left: 5px solid #333;
        }

        .signature {
            margin-top: 100px;
            text-align: right;
            padding-right: 20px;
        }

        .signature-line {
            border-top: 2px solid #000;
            width: 265px;
            display: inline-block;
            margin-bottom: 0;
            padding-bottom: 0;

        }

        .signature p {
            margin: 0;
            padding: 0;
            line-height: 1.2;
            font-size: 13px;
        }

        .signature p strong {
            margin: 0;
            padding: 0;
            line-height: 1.2;
            font-size: 13px;
        }


        .bottom-fixed-div {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            border-top: 2px solid #333;
            background-color: #eee;
            text-align: center;
            padding: 12px 0;
            font-size: 12px;
        }
    </style>
</head>
<body>

<header class="header">
    <table class="header-table">
        <tr>
            <td class="logo-cell" style="width: 20%;">
                <img src="{{ public_path('images/logo/nhmp_logo.jpg') }}" alt="Logo" class="logo">
            </td>
            <td class="text-cell" style="width: 80%;">
                <div class="header-text">
                    <h1 class="heading">OFFICE OF THE</h1>
                    <h1 class="heading">CHIEF MEDICAL OFFICE / CIVIL SURGEON</h1>
                    <h1 class="heading">NATIONAL HIGHWAYS & MOTORWAY POLICE</h1>
                    <h1 class="heading">STREET NO. 5, SECTOR H-8/2, ISLAMABAD</h1>
                    <div class="divider">&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;</div>
                </div>
            </td>
        </tr>
    </table>
</header>

<div class="main-content">
    <div class="patient-info">
        <table>
            <tr>
                <td style="width: 55%;"><strong>Patient:</strong> {{ $diagnosis->visit->patient->name }}</td>
                <td><strong>Date:</strong> {{ $diagnosis->created_at->format('d-M-Y') }}</td>
            </tr>
            <tr>
                <td><strong>Age/Gender:</strong> {{ \Carbon\Carbon::parse($diagnosis->visit->patient->dob)->age }}
                    / {{ $diagnosis->visit->patient->gender ?? 'N/A' }}</td>
                <td><strong>EMRN:</strong> {{ $diagnosis->visit->patient->emrn }}</td>
            </tr>
        </table>
    </div>

    <div class="notes">
        <p><strong>Diagnosis/Clinical Notes:</strong></p>
        <p>{{ $diagnosis->diagnosis }}</p>
    </div>

    <div class="rx-symbol"><span class="rx-box">Rx</span></div>

    <table class="prescription-table">
        <thead>
        <tr>
            <th>Medicine Name</th>
            <th>Dosage Instructions</th>
            <th>Qty</th>
        </tr>
        </thead>
        <tbody>
        @foreach($diagnosis->prescriptions as $p)
            <tr>
                <td>{{ $p->medicine->name }}</td>
                <td>{{ $p->dosage }}</td>
                <td>{{ $p->quantity }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="signature">
        <div class="signature-line"></div>
        <p><strong>Chief Medical Officer / Civil Surgeon</strong>
            <br>NHMP, Islamabad</p>
    </div>
</div>

<footer class="bottom-fixed-div">
    <strong>Police Line Headquarter Street # 5, H-8/2, Islamabad. Ph: 051-9250552</strong>
</footer>

</body>
</html>
