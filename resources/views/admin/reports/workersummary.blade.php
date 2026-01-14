<?php

use Carbon\Carbon;
use App\Models\Dimond;
use App\Models\Process;
?>
@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Worker Summary</h4>

            <!-- <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Worker Summary</li>
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

                <form id="myForm" action="{{ route('admin.workersummary') }}" method="GET">
                    @csrf
                    <div data-repeater-list="group-a">
                        <div data-repeater-item class="row">
                            <div class="mb-3 col-lg-2">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-select" required>
                                    <option value="">Select category</option>
                                    <option value="all" {{ request()->category == 'all' ? 'selected' : '' }}>ALL
                                    </option>
                                    <option value="Inner" {{ request()->category == 'Inner' ? 'selected' : '' }}>Inner
                                        Worker</option>
                                    <option value="Outter" {{ request()->category == 'Outter' ? 'selected' : '' }}>
                                        Outter Worker</option>
                                </select>
                                @if ($errors->has('category'))
                                <div class="error text-danger">{{ $errors->first('category') }}</div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label for="designation">Designation</label>
                                <select name="designation" id="designation" class="form-select" required>
                                    <option value="">Select designation</option>
                                    <option value="all" {{ request()->designation == 'all' ? 'selected' : '' }}>ALL
                                    </option>
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

                            <div class="mb-3 col-lg-2">
                                <label for="worker_name">Worker Name</label>
                                <select name="worker_name" id="worker_name" class="form-select" required>
                                    <option value="">Select worker</option>
                                    <option value="all" {{ request()->worker_name == 'all' ? 'selected' : '' }}>ALL
                                    </option>
                                    <!-- @foreach ($workerLists as $workerList)
                                    <option value="{{ $workerList->fname }}"
                                        {{ request()->worker_name == $workerList->fname ? 'selected' : '' }}>
                                        {{ $workerList->fname }}&nbsp;&nbsp;{{ $workerList->lname }}
                                    </option>
                                    @endforeach -->
                                </select>
                                @if ($errors->has('worker_name'))
                                <div class="error text-danger">{{ $errors->first('worker_name') }}</div>
                                @endif
                            </div>

                            {{-- <div class="mb-3 col-lg-2">
                                    <label for="start_date">Start Date:</label>
                                    <input type="date" name="start_date" class="form-control" id="start_date"
                                        value="{{ request()->start_date }}">
                            @if ($errors->has('start_date'))
                            <div class="error text-danger">{{ $errors->first('start_date') }}</div>
                            @endif
                        </div>

                        <div class="mb-3 col-lg-2">
                            <label for="end_date">End Date:</label>
                            <input type="date" name="end_date" class="form-control" id="end_date"
                                value="{{ request()->end_date }}">
                            @if ($errors->has('end_date'))
                            <div class="error text-danger">{{ $errors->first('end_date') }}</div>
                            @endif
                        </div> --}}
                    </div>

                    <div class="d-flex align-self-center">
                        <div class="gap-2">
                            <button type="button" id="button1" class="btn btn-success mt-2 w-md">Report</button>
                            <button type="button" id="button2" class="btn btn-info mt-2 w-md">Export Report</button>
                            <a class="btn btn-light mt-2 w-md" href="/admin/worker_summary">Clear</a>
                        </div>
                    </div>

            </div>
            </form>
        </div>
    </div>

    @foreach ($workers as $worker)
    <div class="card">
        <div class="card-body">
            <center>
                <h4 style="margin-top:10px">{{ $worker->fname }}&nbsp;{{ $worker->lname }}</h4>
            </center>
            <?php
            $workerprocess = Process::where('worker_name', $worker->fname)->where('return_weight', null)->get();
            ?>

            @if(count($workerprocess)>0)
            <table id="" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                <thead>
                    <tr>
                        <th>Show</th>
                        <th>Party Name</th>
                        <th>Diamond Name</th>
                        <th>Issue Date</th>
                        <th>Diamond Barcode</th>
                        <th>Created Date</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($workerprocess as $workerpro)
                    <?php
                    $dimond = Dimond::where('barcode_number', $workerpro->dimonds_barcode)->first();
                    ?>
                    <tr>
                        <td>
                            <a href="{{ route('admin.dimond.show', $workerpro->dimonds_barcode) }}"
                                class="btn btn-outline-info"><i class="fa fa-eye"></i></a>
                        </td>
                        <td>{{ $dimond->parties->fname }}</td>
                        <td>{{ $dimond->dimond_name }}</td>
                        <td>{{ \Carbon\Carbon::parse($workerpro->issue_date)->format('d-m-Y') }}</td>
                        <td>{{ $workerpro->dimonds_barcode }}</td>
                        <td>{{ \Carbon\Carbon::parse($dimond->created_at)->format('d-m-Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>

            </table>
            @elseif(request()->category != '')
            <center><span class="text-danger">No record found</span></center>
            @endif

        </div>
    </div>
    @endforeach

</div>
</div>
<!-- end row -->
@endsection

@section('script')
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
                            $('#worker_name').append('<option value="' + value
                                .fname + '">' + value.fname + ' ' + value
                                .lname + '</option>');
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
                            $('#designation').append('<option value="' + value
                                .name + '">' + value.name + '</option>');
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
        var form = document.getElementById('myForm');
        var button1 = document.getElementById('button1');
        var button2 = document.getElementById('button2');

        button1.addEventListener('click', function() {
            // Change the form action for button 1
            if ($("#worker_name").val() == '') {
                alert("Please Select Worker");
                return false;
            }


            form.action = "{{ route('admin.workersummary') }}";
            // Submit the form
            form.submit();
        });

        button2.addEventListener('click', function() {
            if ($("#worker_name").val() == '') {
                alert("Please Select Worker");
                return false;
            }
            // Change the form action for button 2
            form.action = "{{ route('admin.workersummary.export') }}";
            // Submit the form
            form.submit();
        });
    });

    $(document).ready(function() {
        $("#workersummaryTable").DataTable({
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
    });
</script>
@endsection