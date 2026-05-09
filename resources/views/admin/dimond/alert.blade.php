<?php

use App\Models\Process;
?>
@extends('layouts.admin')
@section('content')

<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0 font-size-18">Alert Diamonds</h4>
    </div>
  </div>
</div>
<!-- end page title -->

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">

        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="mdi mdi-check-all me-2"></i>
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form method="GET" action="{{ route('admin.dimond.alert') }}" class="row g-3 mb-4">
          <div class="col-md-4">
            <label for="party_id" class="form-label">Party</label>
            <select id="party_id" name="party_id" class="form-select">
              <option value="">All Parties</option>
              @foreach ($parties as $party)
              <option value="{{ $party->id }}" {{ request('party_id') == $party->id ? 'selected' : '' }}>{{ $party->fname }} {{ $party->lname }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-primary">Filter</button>
            <a href="{{ route('admin.dimond.alert') }}" class="btn btn-light ms-2">Clear</a>
          </div>
        </form>

        @if(request()->filled('party_id'))
        <!-- Show single party's diamonds -->
        @php
        $selectedParty = \App\Models\Party::find(request('party_id'));
        @endphp
        @if($selectedParty)
        <div class="card mb-4">
          <div class="card-header bg-light d-flex align-items-center justify-content-between">
            <div>
              <h5 class="mb-0">
                {{ $selectedParty->fname }} {{ $selectedParty->lname }}
                <span class="badge bg-danger ms-2">{{ $dimonds->count() }} Diamonds</span>
              </h5>
            </div>
            <div>
              <a href="{{ route('admin.dimond.alert.export', ['party_id' => $selectedParty->id]) }}" class="btn btn-success btn-sm">
                <i class="mdi mdi-file-excel me-1"></i> Export Excel
              </a>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Party Name</th>
                    <th>Barcode</th>
                    <th>Diamond Name</th>
                    <th>Janger No</th>
                    <th>Weight</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Days Pending</th>
                    <th>Process</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($dimonds as $dimond)
                  @php
                  $process = Process::where('dimonds_id', $dimond->id)->latest()->first();
                  $designation = isset($process) ? $process->designation : '';
                  $pendingDays = $dimond->created_at ? $dimond->created_at->diffInDays(now()) : '';
                  @endphp
                  <tr>
                    <td>
                      <a href="/admin/print-image/{{ $dimond->id }}" target="_blank" class="btn btn-primary btn-sm">Print</a>
                      <a href="{{ route('admin.dimond.show', $dimond->barcode_number) }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                    </td>
                    <td>{{ $dimond->parties->fname }} {{ $dimond->parties->lname }}</td>
                    <td>{{ $dimond->barcode_number }}</td>
                    <td>{{ $dimond->dimond_name }}</td>
                    <td>{{ $dimond->janger_no }}</td>
                    <td>{{ $dimond->weight }}</td>
                    <td>{{ $dimond->status }}</td>
                    <td>{{ $dimond->created_at ? $dimond->created_at->format('Y-m-d') : '' }}</td>
                    <td>{{ $pendingDays }}</td>
                    <td>{{ $designation }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        @endif
        @else
        <!-- Show diamonds grouped by party -->
        @forelse($dimonds->groupBy('parties_id') as $partyId => $partyDimonds)
        <div class="card mb-4">
          <div class="card-header bg-light d-flex align-items-center justify-content-between">
            <div>
              <h5 class="mb-0">
                {{ $partyDimonds->first()->parties->fname }} {{ $partyDimonds->first()->parties->lname }}
                <span class="badge bg-danger ms-2">{{ $partyDimonds->count() }} Diamonds</span>
              </h5>
            </div>
            <div>
              <a href="{{ route('admin.dimond.alert.export', ['party_id' => $partyId]) }}" class="btn btn-success btn-sm">
                <i class="mdi mdi-file-excel me-1"></i> Export Excel
              </a>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="datatable-{{ $partyId }}" class="table table-bordered dt-responsive nowrap w-100 datatable">
                <thead>
                  <tr>
                    <th>Action</th>
                    <th>Barcode</th>
                    <th>Diamond Name</th>
                    <th>Janger No</th>
                    <th>Weight</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Days Pending</th>
                    <th>Process</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($partyDimonds as $dimond)
                  @php
                  $process = Process::where('dimonds_id', $dimond->id)->latest()->first();
                  $designation = isset($process) ? $process->designation : '';
                  $pendingDays = $dimond->created_at ? $dimond->created_at->diffInDays(now()) : '';
                  @endphp
                  <tr>
                    <td>
                      <a href="/admin/print-image/{{ $dimond->id }}" target="_blank" class="btn btn-primary btn-sm">Print</a>
                      <a href="{{ route('admin.dimond.show', $dimond->barcode_number) }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></a>
                    </td>
                    <td>{{ $dimond->barcode_number }}</td>
                    <td>{{ $dimond->dimond_name }}</td>
                    <td>{{ $dimond->janger_no }}</td>
                    <td>{{ $dimond->weight }}</td>
                    <td>{{ $dimond->status }}</td>
                    <td>{{ $dimond->created_at ? $dimond->created_at->format('Y-m-d') : '' }}</td>
                    <td>{{ $pendingDays }}</td>
                    <td>{{ $designation }}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        @empty
        <div class="alert alert-info">
          No alert diamonds found.
        </div>
        @endforelse
        @endif

      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script>
  $(document).ready(function() {
    // Initialize DataTables for single party filter
    if ($('#datatable').length) {
      $('#datatable').DataTable();
    }

    // Initialize DataTables for all party grouped view
    $('table.datatable').each(function() {
      if (!$.fn.DataTable.isDataTable(this)) {
        $(this).DataTable({
          responsive: true,
          paging: true,
          searching: true,
          ordering: true
        });
      }
    });
  });
</script>
@endsection