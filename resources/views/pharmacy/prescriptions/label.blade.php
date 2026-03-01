<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prescription Label - {{ $prescription->id }}</title>
    <style>
        @page {
            size: 80mm 50mm;
            margin: 0;
        }
        body {
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 5mm;
            width: 80mm;
            height: 50mm;
            box-sizing: border-box;
            font-size: 10pt;
            line-height: 1.2;
            color: #1e293b;
        }
        .header {
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 2mm;
            margin-bottom: 2mm;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .pharmacy-name {
            font-weight: 900;
            font-size: 11pt;
            color: #0066FF;
            text-transform: uppercase;
        }
        .date {
            font-size: 8pt;
            color: #64748b;
        }
        .patient-info {
            margin-bottom: 2mm;
        }
        .patient-name {
            font-weight: 700;
            font-size: 10pt;
        }
        .emrn {
            font-size: 8pt;
            color: #64748b;
        }
        .medicine-info {
            background: #f8fafc;
            padding: 2mm;
            border-radius: 4px;
            border-left: 3px solid #0066FF;
        }
        .medicine-name {
            font-weight: 800;
            font-size: 11pt;
            display: block;
        }
        .dosage {
            font-weight: 600;
            color: #0f172a;
            display: block;
            margin-top: 1mm;
        }
        .instructions {
            font-style: italic;
            font-size: 9pt;
            margin-top: 1mm;
            display: block;
            color: #334155;
        }
        .footer {
            position: absolute;
            bottom: 3mm;
            left: 5mm;
            right: 5mm;
            font-size: 7pt;
            color: #94a3b8;
            display: flex;
            justify-content: space-between;
            border-top: 1px solid #f1f5f9;
            padding-top: 1mm;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <span class="pharmacy-name">HMS PHARMACY</span>
        <span class="date">{{ now()->format('d/m/Y H:i') }}</span>
    </div>

    <div class="patient-info">
        <div class="patient-name">{{ $prescription->diagnosis->visit->patient->name }}</div>
        <div class="emrn">EMRN: {{ $prescription->diagnosis->visit->patient->emrn }}</div>
    </div>

    <div class="medicine-info">
        <span class="medicine-name">{{ $prescription->medicine->name }}</span>
        <span class="dosage">{{ $prescription->dosage }}</span>
        @if($prescription->instructions)
            <span class="instructions">{{ $prescription->instructions }}</span>
        @endif
    </div>

    <div class="footer">
        <span>Prescription ID: #{{ $prescription->id }}</span>
        <span>Dispensed by: {{ auth()->user()->name }}</span>
    </div>

    <div class="no-print" style="position: fixed; top: 0; right: 0; padding: 10px;">
        <button onclick="window.close()" style="padding: 5px 10px; cursor: pointer; background: #ef4444; color: white; border: none; border-radius: 4px;">Close</button>
    </div>
</body>
</html>
