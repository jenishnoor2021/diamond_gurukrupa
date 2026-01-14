<?php

use App\Models\Dimond;
use App\Models\Process;
?>
@extends('layouts.admin')
@section('style')
<style>
  .dt-button.buttons-html5 {
    background-color: aliceblue;
  }
</style>
@endsection
@section('content')
<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0 font-size-18">Worker Issue Report</h4>

      <!-- <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Worker Report</li>
                    </ol>
                </div> -->

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

        <form action="{{ route('generate-worker-pdf') }}" id="myWorkerForm" method="get">
          @csrf

          <div data-repeater-list="group-a">
            <div data-repeater-item class="row">
              <div class="mb-3 col-lg-2">
                <label for="category">Category</label>
                <select name="category" id="category" class="form-select" required>
                  <option value="">Select category</option>
                  <option value="all" {{ request()->category == 'all' ? 'selected' : '' }}>ALL</option>
                  <option value="Inner" {{ request()->category == 'Inner' ? 'selected' : '' }}>Inner Worker</option>
                  <option value="Outter" {{ request()->category == 'Outter' ? 'selected' : '' }}>Outter Worker</option>
                </select>
                @if($errors->has('designation'))
                <div class="error text-danger">{{ $errors->first('designation') }}</div>
                @endif
              </div>

              <div class="mb-3 col-lg-2">
                <label for="designation">Designation</label>
                <select name="designation" id="designation" class="form-select" required>
                  <option value="">Select designation</option>
                  <option value="all" {{ request()->designation == 'all' ? 'selected' : '' }}>ALL</option>
                  @foreach($designations as $designation)
                  <option value="{{$designation->name}}" {{ request()->designation == $designation->name ? 'selected' : '' }}>{{$designation->name}}</option>
                  @endforeach
                </select>
                @if($errors->has('designation'))
                <div class="error text-danger">{{ $errors->first('designation') }}</div>
                @endif
              </div>

              <div class="mb-3 col-lg-2">
                <label for="worker_name">Worker</label>
                <select name="worker_name" id="worker_name" class="form-select" required>
                  <option value="">Select worker</option>
                </select>
                @if($errors->has('worker_name'))
                <div class="error text-danger">{{ $errors->first('worker_name') }}</div>
                @endif
              </div>

              <div class="mb-3 col-lg-2">
                <label for="which_diamond">Type</label>
                <select name="which_diamond" id="which_diamond" class="form-select" required>
                  <option value="delevery_date" {{ request()->which_diamond == 'delevery_date' ? 'selected' : '' }}>Deliverd</option>
                  <option value="updated_at" {{ request()->which_diamond == 'updated_at' ? 'selected' : '' }}>Reguler</option>
                </select>
                @if ($errors->has('worker_name'))
                <div class="error text-danger">{{ $errors->first('worker_name') }}</div>
                @endif
              </div>

              <div class="mb-3 col-lg-2">
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" class="form-control" id="start_date" value="{{ request()->start_date }}" required>
                @if($errors->has('start_date'))
                <div class="error text-danger">{{ $errors->first('start_date') }}</div>
                @endif
              </div>

              <div class="mb-3 col-lg-2">
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" class="form-control" id="end_date" value="{{ request()->end_date }}" required>
                @if($errors->has('end_date'))
                <div class="error text-danger">{{ $errors->first('end_date') }}</div>
                @endif
              </div>
            </div>

            <div class="d-flex">
              <div class="gap-2">
                <button type="button" id="get_list" class="btn btn-success mt-2 w-md">List</button>
                <!-- <button type="button" id="download_list" class="btn btn-info mt-2 w-md">Download</button> -->
                <a class="btn btn-light mt-2 w-md" href="/admin/worker_issue_report">Clear</a>
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>

    @if(count($data) > 0)
    @foreach ($worker_detail as $worker)
    <div class="card">
      <div class="card-body">

        <center>
          <h4>{{$worker->fname}}&nbsp;{{$worker->lname}}</h4>
        </center>

        <table id="exportworkerTable_{{ $worker->id }}"
          class="table table-bordered dt-responsive nowrap w-100 mt-3">
          <thead>
            <tr>
              <th>Sr.</th>
              <th>Dimond Name</th>
              <th>Dimond barcode</th>
              <th>Issues Date</th>
              <th>Return Date</th>
              <th>Shape</th>
              <th>clarity</th>
              <th>color</th>
              <th>cut</th>
              <th>polish</th>
              <th>symmetry</th>
              <th>Issues Weight</th>
              <th>Return Weight</th>
              <th>Required Weight</th>
              <th width="20%">Amount</th>
              <th>Created date</th>
              <th>Delivery date</th>
            </tr>
          </thead>
          @php
          $p = 1;
          @endphp
          <tbody>
            <?php
            $dimondsBarcodeArray = [];
            ?>
            @foreach($data as $key=>$da)
            @if($worker->fname == $da->worker_name)
            <?php
            $category = $_GET['category'];
            $getdimond = Dimond::where('barcode_number', $da->dimonds_barcode)->first();
            $rw = $da->return_weight;

            $lastrecord = Process::where('dimonds_barcode', $da->dimonds_barcode)->where('worker_name', $worker->fname)->latest()->first();
            $lastReturnWeight = $lastrecord->return_weight;

            if (isset($getdimond) && ($da->price != 0) && ($category == "Inner")) { ?>
              <tr>
                <td>{{$p}}</td>
                <td>{{ $da->dimonds->dimond_name }}</td>
                <td>{{ $da->dimonds_barcode }}</td>
                <td>{{ \Carbon\Carbon::parse($da->issue_date)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($da->return_date)->format('d-m-Y') }}</td>
                <td>{{ $getdimond->shape }}</td>
                <td>{{$getdimond->clarity}}</td>
                <td>{{$getdimond->color}}</td>
                <td>{{$getdimond->cut}}</td>
                <td>{{$getdimond->polish}}</td>
                <td>{{$getdimond->symmetry}}</td>
                <td>{{ $da->issue_weight }}</td>
                <td>{{ isset($lastReturnWeight) ? $lastReturnWeight : '' }}</td>
                <td>{{ $getdimond->required_weight }}</td>
                <td>{{ $da->price }}</td>
                <td>{{ \Carbon\Carbon::parse($getdimond->created_at)->format('d-m-Y')}}</td>
                <td>{{ \Carbon\Carbon::parse($getdimond->delevery_date)->format('d-m-Y') }}</td>
                @php
                $p += 1;
                @endphp
              </tr>
            <?php } elseif ($category == "Outter" && !in_array($da->dimonds_barcode, $dimondsBarcodeArray)) {
              $dimondsBarcodeArray[] = $da->dimonds_barcode;
            ?>
              <tr>
                <td>{{$p}}</td>
                <td>{{ $da->dimonds->dimond_name }}</td>
                <td>{{ $da->dimonds_barcode }}</td>
                <td>{{ \Carbon\Carbon::parse($da->issue_date)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($da->return_date)->format('d-m-Y') }}</td>
                <td>{{ $getdimond->shape }}</td>
                <td>{{$getdimond->clarity}}</td>
                <td>{{$getdimond->color}}</td>
                <td>{{$getdimond->cut}}</td>
                <td>{{$getdimond->polish}}</td>
                <td>{{$getdimond->symmetry}}</td>
                <td>{{ $da->issue_weight }}</td>
                <td>{{ isset($lastReturnWeight) ? $lastReturnWeight : '' }}</td>
                <td>{{ $getdimond->required_weight }}</td>
                <td>{{ $da->price }}</td>
                <td>{{ \Carbon\Carbon::parse($getdimond->created_at)->format('d-m-Y')}}</td>
                <td>{{ \Carbon\Carbon::parse($getdimond->delevery_date)->format('d-m-Y') }}</td>
                @php
                $p += 1;
                @endphp
              </tr>
              <?php } else {
              if ($da->price != 0) { ?>
                <tr>
                  <td>{{$p}}</td>
                  <td>{{ $da->dimonds->dimond_name }}</td>
                  <td>{{ $da->dimonds_barcode }}</td>
                  <td>{{ \Carbon\Carbon::parse($da->issue_date)->format('d-m-Y') }}</td>
                  <td>{{ \Carbon\Carbon::parse($da->return_date)->format('d-m-Y') }}</td>
                  <td>{{ $getdimond->shape }}</td>
                  <td>{{$getdimond->clarity}}</td>
                  <td>{{$getdimond->color}}</td>
                  <td>{{$getdimond->cut}}</td>
                  <td>{{$getdimond->polish}}</td>
                  <td>{{$getdimond->symmetry}}</td>
                  <td>{{ $da->issue_weight }}</td>
                  <td>{{ isset($lastReturnWeight) ? $lastReturnWeight : '' }}</td>
                  <td>{{ $getdimond->required_weight }}</td>
                  <td>{{ $da->price }}</td>
                  <td>{{ \Carbon\Carbon::parse($getdimond->created_at)->format('d-m-Y')}}</td>
                  <td>{{ \Carbon\Carbon::parse($getdimond->delevery_date)->format('d-m-Y') }}</td>
                  @php
                  $p += 1;
                  @endphp
                </tr>
            <?php }
            } ?>
            @endif
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    @endforeach
    @elseif(request()->category != '')
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <span class="text-danger">No record found</span>
          </div>
        </div>
      </div>
    </div>
    @endif

  </div>
</div>
<!-- end row -->
@endsection

@section('script')
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

<script>
  $(document).ready(function() {
    @foreach($worker_detail as $worker)
    $("#exportworkerTable_{{ $worker->id }}").DataTable({
      dom: 'Blfrtip',
      buttons: [{
          extend: 'pdf',
        },
        {
          extend: 'csv',
        },
        {
          extend: 'excel',
        }
      ]
    });
    @endforeach
  });
</script>

<script>
  $(document).ready(function() {
    $('#designation').change(function() {
      var designation = $(this).val();
      $('#worker_name').empty();

      if (designation == 'all') {
        $('#worker_name').append('<option value="">Select worker</option>');
        $('#worker_name').append('<option value="all">ALL</option>');
      } else if (designation && designation != 'all') {
        $.ajax({
          type: 'POST',
          url: '/admin/get-workers',
          data: {
            '_token': '{{ csrf_token() }}',
            'designation': designation,
          },
          success: function(data) {
            $('#worker_name').append('<option value="">Select worker</option>');
            $('#worker_name').append('<option value="all">ALL</option>');
            $.each(data, function(key, value) {
              $('#worker_name').append('<option value="' + value.fname + '">' + value.fname + ' ' + value.lname + '</option>');
            });
          }
        });
      } else {
        $('#worker_name').append('<option value="">Select worker</option>');
      }
    });
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
  });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('myWorkerForm');
    var button1 = document.getElementById('get_list');
    var button2 = document.getElementById('download_list');

    button1.addEventListener('click', function() {
      if (document.getElementById('start_date').value == '') {
        alert("Enter Start date");
        return false;
      }

      if (document.getElementById('end_date').value == '') {
        alert("Enter End date");
        return false;
      }
      // Change the form action for button 1
      form.action = "{{ route('admin.workerissue.report') }}";
      // Submit the form
      form.submit();
    });

    button2.addEventListener('click', function() {
      if (document.getElementById('start_date').value == '') {
        alert("Enter Start date");
        return false;
      }

      if (document.getElementById('end_date').value == '') {
        alert("Enter End date");
        return false;
      }
      // Change the form action for button 2
      form.action = "{{ route('generate-worker-issue-pdf') }}";
      // Submit the form
      form.submit();
    });
  });
</script>
@endsection