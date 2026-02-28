<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Reports Queue - {{ date('Y-m-d') }}</title>
    <style>
        @page {
            margin: 10mm;
            size: landscape;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 11px;
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
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0;
        }

        .print-date {
            text-align: right;
            font-size: 10px;
            margin-bottom: 10px;
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
            background-color: #f8d7da;
        }

        .no-print {
            display: none !important;
        }

        .page-break {
            page-break-before: always;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                font-size: 9px;
            }

            table {
                page-break-inside: avoid;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #2c5282;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            z-index: 1000;
        }

        .print-btn:hover {
            background: #2b6cb0;
        }

        .filters {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
        }

        .filter-item {
            margin-bottom: 10px;
        }

        .filter-label {
            font-weight: bold;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <!-- Print Button -->
    <button class="print-btn no-print" onclick="window.print()">🖨️ Print Queue</button>

    <!-- Filters Summary -->
    <div class="filters no-print">
        <h3>Filter Summary</h3>
        <div class="filter-item">
            <span class="filter-label">Status:</span>
            <span>{{ request('status', 'All') }}</span>
        </div>
        <div class="filter-item">
            <span class="filter-label">Priority:</span>
            <span>{{ request('priority', 'All') }}</span>
        </div>
        <div class="filter-item">
            <span class="filter-label">Date Range:</span>
            <span>{{ request('date_from', 'Any') }} to {{ request('date_to', 'Any') }}</span>
        </div>
        <div class="filter-item">
            <span class="filter-label">Total Reports:</span>
            <span>{{ $reports->count() }}</span>
        </div>
    </div>

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
                <th width="10%">Lab #</th>
                <th width="15%">Test Name</th>
                <th width="15%">Patient Name</th>
                <th width="10%">CNIC</th>
                <th width="10%">Doctor</th>
                <th width="10%">Status</th>
                <th width="10%">Priority</th>
                <th width="10%">Created</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reports as $index => $report)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $report->lab_number ?? 'N/A' }}</td>
                    <td>{{ $report->test_name ?? 'N/A' }}</td>
                    <td>{{ $report->patient->name ?? 'N/A' }}</td>
                    <td>{{ $report->patient->cnic ?? 'N/A' }}</td>
                    <td>{{ $report->doctor->name ?? 'N/A' }}</td>
                    <td class="status-{{ $report->status ?? 'pending' }}">
                        {{ strtoupper($report->status ?? 'PENDING') }}
                    </td>
                    <td class="priority-{{ $report->priority ?? 'normal' }}">
                        {{ strtoupper($report->priority ?? 'NORMAL') }}
                    </td>
                    <td>{{ $report->created_at ? $report->created_at->format('d-M-y') : 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px;">
                        No lab reports found in the queue.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Summary -->
    <div style="margin-top: 20px; padding-top: 10px; border-top: 1px solid #000;">
        <table style="width: 100%; border: none;">
            <tr>
                <td width="25%"><strong>Pending:</strong> {{ $reports->where('status', 'pending')->count() }}</td>
                <td width="25%"><strong>Processing:</strong> {{ $reports->where('status', 'processing')->count() }}
                </td>
                <td width="25%"><strong>Completed:</strong> {{ $reports->where('status', 'completed')->count() }}
                </td>
                <td width="25%">
                    <strong>Urgent:</strong>
                    {{ $reports->where('priority', 'urgent')->count() + $reports->where('priority', 'emergency')->count() }}
                </td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div style="margin-top: 30px; text-align: center; font-size: 9px;">
        <div>*** This is a computer generated queue report ***</div>
        <div>Laboratory Department | Page 1 of 1</div>
    </div>

    <script>
        window.onload = function() {
            // Auto-print if requested
            if (window.location.search.includes('autoprint')) {
                setTimeout(() => {
                    window.print();
                }, 500);
            }
        };
    </script>
</body>

</html>
