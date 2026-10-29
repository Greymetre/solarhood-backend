<x-app-layout>
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

          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover no-wrap">
              <thead class="text-primary">
                @foreach($headings as $heading)
                <th>{{ $heading }}</th>
                @endforeach
              </thead>
              <tbody>
                @forelse($rows as $row)
                <tr>
                  @foreach($row as $cell)
                  <td @if($cell === 'Pending') class="text-danger" @endif>{{ $cell }}</td>
                  @endforeach
                </tr>
                @empty
                <tr>
                  <td colspan="{{ count($headings) }}" class="text-center text-muted">No records found.</td>
                </tr>
                @endforelse
              </tbody>
            </table>
          </div>
          <div class="d-flex justify-content-between align-items-center">
            <span>Showing {{ $records->firstItem() ?? 0 }} to {{ $records->lastItem() ?? 0 }} of {{ $records->total() }} entries</span>
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
