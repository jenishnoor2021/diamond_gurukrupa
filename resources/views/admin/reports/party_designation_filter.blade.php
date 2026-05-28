<?php

use App\Models\Dimond;
use App\Models\Process;
?>
@extends('layouts.admin')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0 font-size-18">Party Designation Filter</h4>
    </div>
  </div>
</div>

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
        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="mdi mdi-block-helper me-2"></i>
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form action="{{ route('party.designation.filter') }}" method="GET">
          <div class="row">
            <div class="mb-3 col-lg-3">
              <label for="party_id">Party Name</label>
              <select name="party_id" id="party_id" class="form-select">
                <option value="">Select party</option>
                <option value="All" {{ request()->party_id == 'All' ? 'selected' : '' }}>ALL</option>
                @foreach ($partyLists as $partyList)
                <option value="{{ $partyList->id }}"
                  {{ request()->party_id == $partyList->id ? 'selected' : '' }}>
                  {{ $partyList->fname }}&nbsp;&nbsp;{{ $partyList->lname }}
                </option>
                @endforeach
              </select>
            </div>

            <div class="mb-3 col-lg-3">
              <label for="designation">Designation</label>
              <select name="designation" id="designation" class="form-select">
                <option value="">Select designation</option>
                @foreach ($designations as $designation)
                <option value="{{ $designation->name }}"
                  {{ request()->designation == $designation->name ? 'selected' : '' }}>
                  {{ $designation->name }}
                </option>
                @endforeach
              </select>
            </div>

            <div class="mb-3 col-lg-2">
              <label for="start_date">Start Date</label>
              <input type="date" name="start_date" class="form-control" id="start_date"
                value="{{ request()->start_date }}">
            </div>

            <div class="mb-3 col-lg-2">
              <label for="end_date">End Date</label>
              <input type="date" name="end_date" class="form-control" id="end_date"
                value="{{ request()->end_date }}">
            </div>

            <div class="mb-3 col-lg-2 d-flex align-items-end">
              <div class="d-flex gap-2 w-100">
                <button type="submit" class="btn btn-success w-md">Filter</button>
                <a class="btn btn-light w-md" href="/admin/party-designation-filter">Clear</a>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    @if (count($processes) > 0)
    @php
    $totalIssueWeight = $processes->sum('issue_weight');
    $totalReturnWeight = $processes->sum('return_weight');
    $totalPrice = $processes->sum('price');
    @endphp

    <!-- <div class="row mb-3">
      <div class="col-md-3">
        <div class="card border-success">
          <div class="card-body">
            <h6 class="mb-2">Total Records</h6>
            <h3 class="mb-0">{{ count($processes) }}</h3>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-info">
          <div class="card-body">
            <h6 class="mb-2">Issue Weight</h6>
            <h3 class="mb-0">{{ number_format($totalIssueWeight, 2) }}</h3>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-warning">
          <div class="card-body">
            <h6 class="mb-2">Return Weight</h6>
            <h3 class="mb-0">{{ number_format($totalReturnWeight, 2) }}</h3>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-danger">
          <div class="card-body">
            <h6 class="mb-2">Amount</h6>
            <h3 class="mb-0">{{ number_format($totalPrice, 2) }}</h3>
          </div>
        </div>
      </div>
    </div> -->

    <div class="card">
      <div class="card-body">
        <table class="table table-bordered dt-responsive nowrap w-100 partyDesignationTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Party</th>
              <th>Party Code</th>
              <th>Diamond</th>
              <th>Barcode</th>
              <th>Designation</th>
              <th>Worker</th>
              <th>Issue Date</th>
              <th>Return Date</th>
              <th>Issue Weight</th>
              <th>Return Weight</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            @foreach($processes as $index => $process)
            @php
            $diamond = optional($process->dimonds);
            $party = optional($diamond->parties);
            @endphp
            <tr>
              <td>{{ $index + 1 }}</td>
              <td>{{ $party->fname ?? '-' }} {{ $party->lname ?? '' }}</td>
              <td>{{ $party->party_code ?? '-' }}</td>
              <td>{{ $diamond->dimond_name ?? '-' }}</td>
              <td>{{ $process->dimonds_barcode ?? '-' }}</td>
              <td>{{ $process->designation ?? '-' }}</td>
              <td>{{ $process->worker_name ?? '-' }}</td>
              <td>{{ $process->issue_date ? \Carbon\Carbon::parse($process->issue_date)->format('d-m-Y') : '-' }}</td>
              <td>{{ $process->return_date ? \Carbon\Carbon::parse($process->return_date)->format('d-m-Y') : '-' }}</td>
              <td>{{ $process->issue_weight ?? '-' }}</td>
              <td>{{ $process->return_weight ?? '-' }}</td>
              <td>{{ $process->price ?? '0' }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @else
    <div class="card">
      <div class="card-body">
        <div class="alert alert-warning mb-0">No records found for the selected filters.</div>
      </div>
    </div>
    @endif
  </div>
</div>
@endsection

@section('script')
<script>
  $(document).ready(function() {
    $('.partyDesignationTable').DataTable({
      dom: 'Blfrtip',
      buttons: ['pdf', 'csv', 'excel']
    });
  });
</script>
@endsection