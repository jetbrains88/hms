<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Report - {{ $labOrder->lab_number }}</title>
    <style>
        body { font-family: sans-serif; color: #333; line-height: 1.5; padding: 40px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 20px; margin-bottom: 30px; }
        .info-grid { display: grid; grid-template-cols: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .patient-info { border: 1px solid #ddd; padding: 15px; border-radius: 8px; }
        .order-info { border: 1px solid #ddd; padding: 15px; border-radius: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { bg-color: #f8f9fa; }
        .abnormal { color: red; font-weight: bold; }
        .footer { margin-top: 50px; display: flex; justify-content: space-between; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #6366f1; color: white; border: none; border-radius: 6px; cursor: pointer;">Print Now</button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6b7280; color: white; border: none; border-radius: 6px; cursor: pointer; margin-left: 10px;">Close</button>
    </div>

    <div class="header">
        <h1>Laboratory Report</h1>
        <p>HMS Medical Center - Hospital Management System</p>
    </div>

    <div class="info-grid">
        <div class="patient-info">
            <h3>Patient Information</h3>
            <p><strong>Name:</strong> {{ $labOrder->patient?->name ?? 'N/A' }}</p>
            <p><strong>EMRN:</strong> {{ $labOrder->patient?->emrn ?? 'N/A' }}</p>
            <p><strong>Age/Gender:</strong> {{ $labOrder->patient?->age ?? 'N/A' }} / {{ $labOrder->patient?->gender ?? 'N/A' }}</p>
        </div>
        <div class="order-info">
            <h3>Order Information</h3>
            <p><strong>Order #:</strong> {{ $labOrder->lab_number }}</p>
            <p><strong>Date:</strong> {{ $labOrder->created_at?->format('d M Y H:i') }}</p>
            <p><strong>Doctor:</strong> Dr. {{ $labOrder->doctor?->name ?? 'N/A' }}</p>
        </div>
    </div>

    @foreach($labOrder->items as $item)
        <div style="margin-top: 30px;">
            <h3 style="background: #f3f4f6; padding: 10px;">{{ $item->labTestType->name }}</h3>
            <table>
                <thead>
                    <tr>
                        <th>Parameter</th>
                        <th>Result</th>
                        <th>Reference Range</th>
                        <th>Unit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($item->labResults as $result)
                        <tr>
                            <td>{{ $result->labTestParameter->name }}</td>
                            <td class="{{ $result->is_abnormal ? 'abnormal' : '' }}">{{ $result->display_value }}</td>
                            <td>{{ $result->labTestParameter->reference_range ?? '-' }}</td>
                            <td>{{ $result->labTestParameter->unit ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="footer">
        <div>
            <p>_________________________</p>
            <p>Lab Technician</p>
            <p>{{ $labOrder->technician->name ?? 'Authorized Sign' }}</p>
        </div>
        <div>
            <p>_________________________</p>
            <p>Verified By</p>
            <p>{{ $labOrder->verifiedBy->name ?? 'Authorized Sign' }}</p>
            <p>{{ $labOrder->verified_at ? $labOrder->verified_at->format('d M Y') : '' }}</p>
        </div>
    </div>
</body>
</html>
