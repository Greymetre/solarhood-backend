<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Customer Process Report</title>

    {{-- Bootstrap 5 CDN (remove if you already include it somewhere else) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous">

    <style>
        body {
            font-size: 12px;
            background-color: #efeded;
        }

        .report-wrapper {
            max-width: 900px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
        }

        .page-break {
            page-break-before: always;
        }

        /* .customer-header {
            border-left: 4px solid #0d6efd;
            background-color: #efeded;
        } */

        .customer-header h4 {
            margin: 0;
        }

        .badge-status {
            font-size: 11px;
            text-transform: capitalize;
        }

        .table td,
        .table th {
            padding: 0.35rem 0.5rem;
        }

        .meta-label {
            font-weight: 600;
        }
    </style>
</head>

<body>
    <div class="container-fluid my-4">
        <div class="report-wrapper">
            <div class="text-center mb-3">
                <img class="rounded iconimg" alt="Solar Hood" src="{!! url('/').'/'.asset('assets/img/solar_logo_text.png?') !!}?v=3" width="100%">
            </div>
            
            @php
            $currentCustomerId = null;
            @endphp

            @foreach($all_processes as $activeProcess)
            {{-- New page & header when customer changes --}}
            @if($currentCustomerId !== $activeProcess->customer_id)
            @php
            $currentCustomerId = $activeProcess->customer_id;
            @endphp

            {{-- Page break except for very first customer --}}
            @if(!$loop->first)
            <div class="page-break"></div>
            @endif

            <div class="shadow-sm mb-4 customer-header px-4 py-2" style="line-height: 22px;">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1">
                            Customer Name -
                            {{ $activeProcess->customer->name ?? $activeProcess->customer->first_name.' '.$activeProcess->customer->last_name ?? 'N/A' }}
                        </h4>
                        <div>
                            <span class="meta-label">Register Mobile no.:</span>
                            {{ $activeProcess->customer->mobile ?? $activeProcess->customer->contact_number ?? 'N/A' }}
                        </div>
                        <div>
                            <span class="meta-label">Email Id:</span>
                            {{ $activeProcess->customer->email ?? 'N/A' }}

                        </div>
                        <div>
                            <span class="meta-label">Address:</span>
                            {{ $activeProcess->customer->full_address ?? '-' }}
                        </div>
                        <div>
                            <span class="meta-label">Creation Date:</span>
                            {{ optional($activeProcess->customer->created_at)->format('d-m-Y') ?? 'N/A' }}
                        </div>
                        <div>
                            <span class="meta-label">Inverter Serial No.:</span>
                            {{ $activeProcess->customer->inverter_sr_no ?? 'N/A' }}
                        </div>
                    </div>
                    <div class="text-end small text-muted mt-2 mt-md-0">
                        <h4 class="mb-1">
                            Capacity:
                            {{ $activeProcess->customer->sap_code ?? 'N/A' }}
                        </h4>
                        <div>
                            <span class="meta-label">IVRS no.:</span>
                            {{ $activeProcess->customer->ivrs_no ?? 'N/A' }}
                        </div>

                        <div>
                            <span class="meta-label">User id:</span>
                            {{ $activeProcess->customer->new_id ?? 'N/A' }}
                        </div>
                        <div>
                            <span class="meta-label">Password:</span>
                            {{ $activeProcess->customer->password_string ?? 'N/A' }}
                        </div>
                        <div>
                            <span class="meta-label">PV Model No.:</span>
                            {{ $activeProcess->customer?->customerdetails?->otherid_no ?? 'N/A' }}
                        </div>
                        <div>
                            <span class="meta-label">Warranty Date:</span>
                            {{ $activeProcess->customer->warranty_end_date ? date('d-m-Y', strtotime($activeProcess->customer->warranty_end_date)) :  'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Process Card (like "Process Govt Work" / "Process Service" in PDF) --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-header d-flex flex-wrap justify-content-between align-items-center">
                    <div class="fw-semibold">
                        Process:
                        {{ $activeProcess->process->process_name ?? 'N/A' }}
                    </div>

                    <div class="small text-end">
                        <span class="me-2">
                            <span class="meta-label">Overall Status:</span>
                            @php $status = $activeProcess->status; @endphp
                            <span class="badge badge-status
                                @if($status === 'completed') bg-success
                                @elseif($status === 'pending') bg-warning text-dark
                                @else bg-secondary
                                @endif
                            ">
                                {{ $status ?? 'N/A' }}
                            </span>
                        </span>
                        <div class="mt-1">
                            <span class="meta-label">Started:</span>
                            {{ optional($activeProcess->created_at)->format('d-m-Y') ?? '-' }}
                            &nbsp;|&nbsp;
                            <span class="meta-label">Completed:</span>
                            {{ optional($activeProcess->completed_at)->format('d-m-Y') ?? '-' }}
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    {{-- If you have extra meta like Appointment Date, User, Pass, System, show here --}}
                    {{-- Example (replace with your real fields): --}}
                    {{--
                    <div class="row mb-3 small">
                        <div class="col-md-6">
                            <span class="meta-label">Appointment Date:</span> 28-11-2025
                        </div>
                        <div class="col-md-6 text-md-end">
                            <span class="meta-label">User:</span> XYZ
                            &nbsp;|&nbsp;
                            <span class="meta-label">Pass:</span> 123
                            &nbsp;|&nbsp;
                            <span class="meta-label">System:</span> 3KW
                        </div>
                    </div>
                    --}}

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40%;">Step Name</th>
                                    <th style="width: 15%;">Status</th>
                                    <th style="width: 20%;">Completed Date</th>
                                    <th style="width: 25%;">Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeProcess->steps as $step)
                                <tr>
                                    <td>
                                        {{-- Adjust according to your relation/column names --}}
                                        {{ $step->step->value
                                            ?? optional($step->step ?? null)->value
                                            ?? 'Step '.$loop->iteration }}
                                    </td>
                                    <td>
                                        @php $stepStatus = $step->status; @endphp
                                        <span class="badge badge-status
                                            @if($stepStatus === 'completed') bg-success
                                            @elseif($stepStatus === 'pending') bg-warning text-dark
                                            @else bg-secondary
                                            @endif
                                        ">
                                            {{ $stepStatus ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ optional($step->completed_at)->format('d-m-Y') ?? '-' }}
                                    </td>
                                    <td>
                                        {{ $step->remark ?? '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        No steps found for this process.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            @endforeach

        </div>
    </div>
</body>

</html>