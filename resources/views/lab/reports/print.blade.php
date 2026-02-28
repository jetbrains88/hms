<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Report - {{ $labOrder->lab_number }}</title>
    <style>
        @php
            $showUnits = !in_array($labOrder->testType->name ?? '', ['Special Chemistry', 'Urine Routine Examination']);
        @endphp
        @page {
            margin: 0.5cm;
            size: A4;
        }

        body {
            font-family: 'Arial', sans-serif;
            color: #000;
            line-height: 1.3;
            font-size: 11pt;
            margin: 0;
            padding: 10px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-bottom: 5px;
        }

        .header-table td {
            padding: 5px;
            vertical-align: top;
        }

        .logo-cell {
            width: 100px;
            text-align: center;
            border-right: 1px solid #000;
        }

        .logo-img {
            width: 120px;
            height: auto;
        }

        .hospital-info {
            text-align: center;
            border-right: 1px solid #000;
        }

        .hospital-name {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .hospital-address {
            font-size: 10pt;
        }

        .report-title {
            font-weight: bold;
            text-align: center;
            text-decoration: underline;
            margin: 10px 0 5px 0;
            font-size: 12pt;
        }

        .patient-info-cell {
            width: 40%;
            font-size: 10pt;
        }

        .patient-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .label {
            font-weight: bold;
        }

        .section-title {
            font-weight: bold;
            text-decoration: underline;
            margin: 10px 0 5px 0;
            font-size: 11pt;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
            font-size: 10pt;
        }

        .results-table th, .results-table td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        .results-table th {
            background-color: #f0f0f0; /* Optional: light gray background for header */
        }
        
        .results-table th:first-child, .results-table td:first-child {
             text-align: left;
        }

        .footer {
            margin-top: 20px;
            font-size: 9pt;
            border-top: 1px solid #000;
            padding-top: 5px;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            align-items: flex-end;
        }
        
        .signature-box {
            text-align: center;
        }

        .no-print {
            display: none;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body { 
                -webkit-print-color-adjust: exact; 
            }
        }

        .divider {
            font-family: 'DejaVu Sans', sans-serif;
            margin-top: 5px;
            margin-bottom: 5px;
            font-size: 10pt;
        }
        
        .print-btn {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 10px 20px;
            background: #000;
            color: #fff;
            cursor: pointer;
            border: none;
            z-index: 1000;
        }
    </style>
</head>
<body>

<button class="print-btn no-print" onclick="window.print()">Print Report</button>

<table class="header-table">
    <tr>
        <td class="logo-cell">
            @php
                $logoPath = public_path('images/logo/nhmp_logo.jpg');
                $logoData = '';
                if(file_exists($logoPath)) {
                    $type = pathinfo($logoPath, PATHINFO_EXTENSION);
                    $data = file_get_contents($logoPath);
                    $logoData = 'data:image/' . $type . ';base64,' . base64_encode($data);
                }
            @endphp
            @if($logoData)
                <img src="{{ $logoData }}" alt="NHMP Logo" class="logo-img">
            @else
                <img src="{{ asset('images/logo/nhmp_logo.png') }}" alt="NHMP Logo" class="logo-img">
            @endif
        </td>
        <td class="hospital-info">
            <div class="hospital-name">NHMP Medical Centre</div>
            <div class="hospital-address">Street No. 5, H-8/2, Islamabad</div>
            <div class="divider">&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;&#x2605;</div>
        </td>
        <td class="patient-info-cell">
            <div style="text-align: center; font-weight: bold; text-decoration: underline; margin-bottom: 5px;">LABORATORY REPORT</div>
            <div class="patient-info-row">
                <span class="label">Patient Name:</span>
                <span>{{ $labOrder->patient->name }}</span>
            </div>
            <div class="patient-info-row">
                <span class="label">EMRN:</span>
                <span>{{ $labOrder->patient->emrn }}</span>
            </div>
            <div class="patient-info-row">
                <span class="label">Collection Date:</span>
                <span>{{ $labOrder->collection_date ? $labOrder->collection_date->format('d-M-Y') : 'N/A' }}</span>
            </div>
            <div class="patient-info-row">
                <span class="label">Reporting Date:</span>
                <span>{{ $labOrder->reporting_date ? $labOrder->reporting_date->format('d-M-Y') : date('d-M-Y') }}</span>
            </div>
           <div class="patient-info-row">
                <span class="label">Lab No:</span>
                <span>{{ $labOrder->lab_number }}</span>
            </div>
        </td>
    </tr>
</table>

@if($labOrder->hasResults())
    <div class="report-title">{{ strtoupper($labOrder->testType->name ?? 'TEST REPORT') }}</div>

    <table class="results-table">
        <thead>
        <tr>
            <th style="width: {{ $showUnits ? '35%' : '40%' }};">PARAMETER</th>
            <th style="width: {{ $showUnits ? '25%' : '30%' }};">RESULT</th>
            @if($showUnits)
                <th style="width: 15%;">UNIT</th>
            @endif
            <th style="width: {{ $showUnits ? '25%' : '30%' }};">REFERENCE RANGE</th>
        </tr>
        </thead>
        <tbody>
        @php $currentGroup = null; @endphp
        @foreach($labOrder->formatted_results as $result)
            @if($result['group_name'] && $result['group_name'] !== $currentGroup)
                <tr>
                    <td colspan="{{ $showUnits ? 4 : 3 }}" style="background-color: #f9f9f9; text-align: left; font-weight: bold; padding: 6px; border: 1px solid #000;">
                        {{ strtoupper($result['group_name']) }}
                    </td>
                </tr>
                @php $currentGroup = $result['group_name']; @endphp
            @endif
            <tr>
                <td style="font-weight: {{ $result['is_abnormal'] ? 'bold' : 'normal' }}; text-align: left; padding-left: {{ $result['group_name'] ? '15px' : '5px' }};">
                    {{ $result['test'] }}
                </td>
                <td style="font-weight: {{ $result['is_abnormal'] ? 'bold' : 'normal' }}">{{ $result['result'] }}</td>
                @if($showUnits)
                    <td>{{ $result['units'] ?? '' }}</td>
                @endif
                <td>{{ $result['normal_range'] ?? '' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif

@if($labOrder->comments)
    <div style="margin-top: 15px;">
        <span class="label">Comments / Notes:</span>
        <p style="margin: 0; font-size: 10pt; white-space: pre-line;">{{ $labOrder->comments }}</p>
    </div>
@endif

<div style="font-size: 9pt; margin-top: 15px; border-top: 1px solid #ccc; padding-top: 5px;">
    Result generated by {{ $labOrder->device_name ?? 'Laboratory' }}
</div>

</body>
</html>
