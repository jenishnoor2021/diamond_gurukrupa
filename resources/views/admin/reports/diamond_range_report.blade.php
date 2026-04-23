<?php

use App\Models\Dimond;
use App\Models\Process;
?>
@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0 font-size-18">Diamond Range Report</h4>
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
        @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="mdi mdi-block-helper me-2"></i>
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <form action="{{ route('admin.diamond-range.report') }}" method="get" id="diamondRangeForm">
          @csrf

          <div class="row">
            <!-- Category Dropdown -->
            <div class="mb-3 col-lg-2">
              <label for="category">Category</label>
              <select name="category" id="category" class="form-select">
                <option value="">Select category</option>
                <option value="all" {{ request()->category == 'all' ? 'selected' : '' }}>ALL</option>
                <option value="Inner" {{ request()->category == 'Inner' ? 'selected' : '' }}>Inner Worker</option>
                <option value="Outter" {{ request()->category == 'Outter' ? 'selected' : '' }}>Outter Worker</option>
              </select>
            </div>

            <!-- Designation Dropdown -->
            <div class="mb-3 col-lg-2">
              <label for="designation">Designation <span class="text-danger">*</span></label>
              <select name="designation" id="designation" class="form-select" required>
                <option value="">Select designation</option>
                @foreach ($designations as $designation)
                <option value="{{ $designation->name }}"
                  {{ request()->designation == $designation->name ? 'selected' : '' }}>
                  {{ $designation->name }}
                </option>
                @endforeach
              </select>
              @if ($errors->has('designation'))
              <div class="error text-danger">{{ $errors->first('designation') }}</div>
              @endif
            </div>

            <!-- Worker Dropdown -->
            <div class="mb-3 col-lg-3">
              <label for="worker_name">Worker</label>
              <select name="worker_name" id="worker_name" class="form-select">
                <option value="">Select worker</option>
              </select>
            </div>

            <!-- Start Date -->
            <div class="mb-3 col-lg-2">
              <label for="start_date">Start Date <span class="text-danger">*</span></label>
              <input type="date" name="start_date" id="start_date" class="form-control"
                value="{{ request()->start_date }}" required>
              @if ($errors->has('start_date'))
              <div class="error text-danger">{{ $errors->first('start_date') }}</div>
              @endif
            </div>

            <!-- End Date -->
            <div class="mb-3 col-lg-2">
              <label for="end_date">End Date <span class="text-danger">*</span></label>
              <input type="date" name="end_date" id="end_date" class="form-control"
                value="{{ request()->end_date }}" required>
              @if ($errors->has('end_date'))
              <div class="error text-danger">{{ $errors->first('end_date') }}</div>
              @endif
            </div>
          </div>

          <div class="row">
            <!-- Min Weight Range -->
            <div class="mb-3 col-lg-3">
              <label for="min_range">Min Weight Range (Optional)</label>
              <input type="number" name="min_range" id="min_range" class="form-control"
                placeholder="e.g., 1.5" value="{{ request()->min_range }}" step="0.01">
            </div>

            <!-- Max Weight Range -->
            <div class="mb-3 col-lg-3">
              <label for="max_range">Max Weight Range (Optional)</label>
              <input type="number" name="max_range" id="max_range" class="form-control"
                placeholder="e.g., 5.0" value="{{ request()->max_range }}" step="0.01">
            </div>

            <!-- Apply Filter Button -->
            <div class="mb-3 col-lg-6 d-flex align-items-end">
              <button type="submit" class="btn btn-primary me-2">
                <i class="mdi mdi-magnify me-1"></i> Apply Filter
              </button>
              <a href="{{ route('admin.diamond-range.report') }}" class="btn btn-secondary">
                <i class="mdi mdi-refresh me-1"></i> Reset
              </a>
            </div>
          </div>
        </form>

        <!-- Results Table -->
        @if (count($diamonds) > 0)
        <div class="d-flex justify-content-end mt-3">
          <form action="{{ route('admin.diamond-range.export') }}" method="POST">
            @csrf
            <input type="hidden" name="category" value="{{ request()->category }}">
            <input type="hidden" name="designation" value="{{ request()->designation }}">
            <input type="hidden" name="worker_name" value="{{ request()->worker_name }}">
            <input type="hidden" name="start_date" value="{{ request()->start_date }}">
            <input type="hidden" name="end_date" value="{{ request()->end_date }}">
            <input type="hidden" name="min_range" value="{{ request()->min_range }}">
            <input type="hidden" name="max_range" value="{{ request()->max_range }}">
            <button type="submit" class="btn btn-success">
              <i class="mdi mdi-file-excel me-1"></i> Export Excel
            </button>
          </form>
        </div>
        <div class="table-responsive mt-4">
          <table class="table table-striped table-hover">
            <thead class="table-light">
              <tr>
                <th>#</th>
                <th>Barcode</th>
                <th>Diamond Name</th>
                <th>Weight</th>
                <th>Shape</th>
                <th>Color</th>
                <th>Clarity</th>
                <th>Cut</th>
                <th>Polish</th>
                <th>Symmetry</th>
                <th>Status</th>
                <!-- <th>Action</th> -->
              </tr>
            </thead>
            <tbody>
              @foreach ($diamonds as $index => $diamond)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td><strong>{{ $diamond->barcode_number ?? 'N/A' }}</strong></td>
                <td>{{ $diamond->dimond_name ?? 'N/A' }}</td>
                <td>
                  <span class="badge bg-info">{{ $diamond->weight }}</span>
                </td>
                <td>{{ $diamond->shape ?? 'N/A' }}</td>
                <td>{{ $diamond->color ?? 'N/A' }}</td>
                <td>{{ $diamond->clarity ?? 'N/A' }}</td>
                <td>{{ $diamond->cut ?? 'N/A' }}</td>
                <td>{{ $diamond->polish ?? 'N/A' }}</td>
                <td>{{ $diamond->symmetry ?? 'N/A' }}</td>
                <td>
                  <span class="badge bg-{{ $diamond->status == 'Completed' ? 'success' : ($diamond->status == 'Processing' ? 'warning' : 'secondary') }}">
                    {{ $diamond->status ?? 'N/A' }}
                  </span>
                </td>
                <!-- <td>
                                    <a href="{{ route('admin.dimond.show', $diamond->id) }}" class="btn btn-sm btn-info" title="View Details">
                                        <i class="mdi mdi-eye"></i>
                                    </a>
                                </td> -->
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="row mt-3">
          <div class="col-12">
            <div class="alert alert-info">
              <strong>Total Diamonds Found:</strong> {{ count($diamonds) }}
              @if (request()->filled('min_range') || request()->filled('max_range'))
              <br>
              <strong>Weight Range:</strong>
              @if (request()->filled('min_range')) {{ request()->min_range }} - @endif
              @if (request()->filled('max_range')) {{ request()->max_range }} @else ∞ @endif
              @endif
            </div>
          </div>
        </div>

        @elseif (request()->filled('designation') && request()->filled('start_date') && request()->filled('end_date'))
        <div class="alert alert-warning mt-4">
          <i class="mdi mdi-alert me-2"></i>
          <strong>No diamonds found</strong> matching the selected criteria. Please try different filter options.
        </div>
        @else
        <div class="alert alert-info mt-4">
          <i class="mdi mdi-information me-2"></i>
          <strong>Select filters above</strong> and click "Apply Filter" to see diamonds. Weight range filters are optional - if not specified, all diamonds will be shown.
        </div>
        @endif

      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  $(document).ready(function() {
    // Load designations when category changes
    $('#category').change(function() {
      var category = $(this).val();
      $('#designation').empty();
      $('#worker_name').empty();
      $('#worker_name').append('<option value="">Select worker</option>');

      if (category) {
        $.ajax({
          type: 'POST',
          url: '/admin/get-designation',
          data: {
            '_token': '{{ csrf_token() }}',
            'category': category,
          },
          success: function(data) {
            $('#designation').append('<option value="">Select designation</option>');
            $('#designation').append('<option value="all">ALL</option>');
            $.each(data, function(key, value) {
              $('#designation').append('<option value="' + value.name + '">' + value.name + '</option>');
            });
          }
        });
      } else {
        $('#designation').append('<option value="">Select designation</option>');
      }
    });

    // Load workers when designation changes
    $('#designation').change(function() {
      var designation = $(this).val();
      $('#worker_name').empty();

      if (designation) {
        $.ajax({
          type: 'POST',
          url: '/admin/get-workers',
          data: {
            '_token': '{{ csrf_token() }}',
            'designation': designation
          },
          success: function(data) {
            $('#worker_name').append('<option value="">Select worker</option>');
            $('#worker_name').append('<option value="all">ALL</option>');
            $.each(data, function(key, value) {
              $('#worker_name').append('<option value="' + value.fname + '">' + value.fname + ' ' + value.lname + '</option>');
            });
          },
          error: function(error) {
            console.error('Error loading workers:', error);
          }
        });
      } else {
        $('#worker_name').append('<option value="">Select worker</option>');
      }
    });
  });
</script>
@endsection