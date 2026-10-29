<x-app-layout>
  <style>
    .process-report-table thead th {
      background: #f4f6fb;
      color: #2f5597;
      font-weight: 600;
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: .3px;
      border-bottom: 2px solid #dde3f0;
      white-space: nowrap;
    }

    .process-report-table td {
      vertical-align: middle;
      border-top: 1px solid #eef0f5;
      font-size: 14px;
    }

    .process-report-table .client-name {
      font-weight: 500;
      color: #333;
    }

    .process-report-table .client-mobile {
      font-size: 12px;
      color: #888;
    }

    .process-report-table .client-mobile .material-icons {
      font-size: 13px;
      vertical-align: middle;
      margin-right: 3px;
    }

    .process-report-table .next-step {
      display: inline-block;
      padding: 3px 10px;
      border-radius: 12px;
      background: #fff4e5;
      color: #b26a00;
      font-size: 12px;
      white-space: nowrap;
    }

    .process-report-table .status-pill {
      display: inline-block;
      min-width: 70px;
      padding: 4px 12px;
      border-radius: 14px;
      font-size: 12px;
      font-weight: 600;
    }

    .process-report-table .status-pill.active {
      background: #e3f2fd;
      color: #0d47a1;
    }

    .process-report-table .status-pill.closed {
      background: #e8f5e9;
      color: #1b5e20;
    }
  </style>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-icon card-header-theme">
          <div class="card-icon">
            <i class="material-icons">account_tree</i>
          </div>
          <h4 class="card-title ">Active Process Report</h4>
        </div>
        <div class="card-body">
          <form method="GET" action="{{ route('reports.active_process_report') }}" id="reportFilterForm">
            <div class="d-flex flex-wrap flex-row align-items-center">
              <div class="p-2" style="width:200px;">
                <select class="select2" name="process_id" id="process_id">
                  <option value="">All Process</option>
                  @foreach($processes as $process)
                  <option value="{{ $process->id }}" {{ request('process_id') == $process->id ? 'selected' : '' }}>{{ $process->process_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="p-2" style="width:200px;">
                <select class="select2" name="customer_id" id="customer_id">
                  <option value="">Select Customer</option>
                  @foreach($customers as $customer)
                  <option value="{{ $customer->id }}" {{ request('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="p-2" style="width:150px;">
                <select class="select2" name="status" id="status">
                  <option value="active" {{ request('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                  <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                  <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
                </select>
              </div>
              <div class="p-2" style="width:150px;"><input type="text" class="form-control datepicker" id="start_date" name="start_date" value="{{ request('start_date') }}" placeholder="Start Date" autocomplete="off" readonly></div>
              <div class="p-2" style="width:150px;"><input type="text" class="form-control datepicker" id="end_date" name="end_date" value="{{ request('end_date') }}" placeholder="End Date" autocomplete="off" readonly></div>
              <div class="p-2"><button type="submit" class="btn btn-just-icon btn-theme" title="Search"><i class="material-icons">search</i></button></div>
              <div class="p-2"><button type="button" class="btn btn-just-icon btn-theme" id="downloadReport" title="{!! trans('panel.global.download') !!} Active Process Report"><i class="material-icons">cloud_download</i></button></div>
              <div class="p-2"><a href="{{ route('reports.active_process_report') }}" class="btn btn-just-icon btn-danger" title="Reset"><i class="material-icons">refresh</i></a></div>
            </div>
          </form>

          <div class="table-responsive mt-3">
            <table class="table table-hover process-report-table">
              <thead>
                <tr>
                  <th class="text-center" style="width:60px;">#</th>
                  <th>Client</th>
                  <th>Created On</th>
                  <th>Process</th>
                  <th style="min-width:170px;">Progress</th>
                  <th>Last Completed</th>
                  <th>Next Step</th>
                  <th class="text-center">Status</th>
                </tr>
              </thead>
              <tbody>
                @forelse($rows as $index => $row)
                <tr>
                  <td class="text-center text-muted">{{ $records->firstItem() + $index }}</td>
                  <td>
                    <div class="client-name">{{ $row->name }}</div>
                    <div class="client-mobile"><i class="material-icons">phone</i>{{ $row->mobile }}</div>
                  </td>
                  <td class="text-nowrap">{{ $row->created_date }}</td>
                  <td>{{ $row->process }}</td>
                  <td>
                    <div class="d-flex justify-content-between small mb-1">
                      <span>{{ $row->completed }} of {{ $row->total }} steps</span>
                      <span class="font-weight-bold">{{ $row->percent }}%</span>
                    </div>
                    <div class="progress" style="height:6px;">
                      <div class="progress-bar {{ $row->closed ? 'bg-success' : 'bg-info' }}" role="progressbar" style="width: {{ $row->percent }}%;"></div>
                    </div>
                  </td>
                  <td>
                    @if($row->last_completed)
                    <div>{{ $row->last_completed }}</div>
                    <div class="small text-muted text-nowrap">{{ $row->last_completed_at }}</div>
                    @else
                    <span class="text-muted">—</span>
                    @endif
                  </td>
                  <td>
                    @if($row->next_step)
                    <span class="next-step">{{ $row->next_step }}</span>
                    @else
                    <span class="text-muted">—</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <span class="status-pill {{ $row->closed ? 'closed' : 'active' }}">{{ $row->closed ? 'Closed' : 'Active' }}</span>
                  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="8" class="text-center text-muted py-4">No records found.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="d-flex flex-wrap justify-content-between align-items-center mt-2">
            <span class="text-muted small">Showing {{ $records->firstItem() ?? 0 }} to {{ $records->lastItem() ?? 0 }} of {{ $records->total() }} entries</span>
            {{ $records->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    $(function() {
      $('#start_date').change(function() {
        $('#end_date').datepicker("option", "minDate", $('#start_date').datepicker('getDate'));
      });

      $('#downloadReport').click(function() {
        window.location.href = "{{ route('reports.active_process_report.download') }}?" + $('#reportFilterForm').serialize();
      });
    });
  </script>
</x-app-layout>
