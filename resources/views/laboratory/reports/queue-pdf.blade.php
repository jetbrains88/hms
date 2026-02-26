<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Reports Queue - {{ date('Y-m-d') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.2;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }

        .hospital-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 12px;
            font-weight: bold;
            margin: 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        th {
            background-color: #f0f0f0;
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: left;
            font-weight: bold;
        }

        td {
            border: 1px solid #000;
            padding: 4px 6px;
            vertical-align: top;
        }

        .status-pending {
            background-color: #fff3cd;
        }

        .status-processing {
            background-color: #cce5ff;
        }

        .status-completed {
            background-color: #d4edda;
        }

        .status-cancelled {
            background-color: #f8d7da;
        }

        .priority-urgent {
            font-weight: bold;
            color: #dc3545;
        }

        .priority-emergency {
            font-weight: bold;
            color: #dc3545;
        }

        .page-break {
            page-break-before: always;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            padding: 5px 0;
            border-top: 1px solid #ccc;
        }

        .page-number:after {
            content: "Page " counter(page);
        }
    </style>
</head>
<body>
<!-- Header -->
<div class="header">
    <div class="hospital-name">NHMP MEDICAL CENTRE</div>
    <div>Street No. 5, H-8/2, Islamabad</div>
    <div class="report-title">LABORATORY REPORTS QUEUE</div>
    <div>Generated on: {{ now()->format('d-M-Y h:i A') }}</div>
</div>

<!-- Reports Table -->
<table>
    <thead>
    <tr>
        <th width="5%">#</th>
        <th width="10%">Test Code</th>
        <th width="20%">Test Name</th>
        <th width="15%">Patient Name</th>
        <th width="10%">CNIC</th>
        <th width="10%">Doctor</th>
        <th width="10%">Status</th>
        <th width="10%">Priority</th>
        <th width="10%">Created</th>
    </tr>
    </thead>
    <tbody>
    @foreach($reports as $index => $report)
        @if($index > 0 && $index % 25 == 0)
            <tr class="page-break"></tr>
        @endif
        <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $report->test_code }}</td>
            <td>{{ $report->test_name }}</td>
            <td>{{ $report->patient->name }}</td>
            <td>{{ $report->patient->cnic }}</td>
            <td>{{ $report->doctor->name }}</td>
            <td class="status-{{ $report->status }}">
                {{ strtoupper($report->status) }}
            </td>
            <td class="priority-{{ $report->priority }}">
                {{ strtoupper($report->priority) }}
            </td>
            <td>{{ $report->created_at->format('d-M-y') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<!-- Summary -->
<div style="margin-top: 20px; padding-top: 10px; border-top: 1px solid #000;">
    <table>
        <tr>
            <td width="25%"><strong>Pending:</strong> {{ $reports->where('status', 'pending')->count() }}</td>
            <td width="25%"><strong>Processing:</strong> {{ $reports->where('status', 'processing')->count() }}</td>
            <td width="25%"><strong>Completed:</strong> {{ $reports->where('status', 'completed')->count() }}</td>
            <td width="25%">
                <strong>Urgent:</strong> {{ $reports->where('priority', 'urgent')->count() + $reports->where('priority', 'emergency')->count() }}
            </td>
        </tr>
    </table>
</div>

<!-- Footer -->
<div class="footer">
    <div>*** Computer Generated Queue Report ***</div>
    <div class="page-number"></div>
</div>
</body>
</html>
