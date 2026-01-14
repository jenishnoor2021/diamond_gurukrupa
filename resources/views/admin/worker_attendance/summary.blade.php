@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Attendance Summary</h4>

            <!-- <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                        <li class="breadcrumb-item active">Attendance Summary</li>
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

                <div id="right">
                    <div id="menu" class="mb-3">



                        <span id="menu-navi"
                            class="d-sm-flex flex-wrap text-center text-sm-start justify-content-sm-between">
                            <div class="">
                                <h4>Attendance Summary</h4>
                            </div>

                            <div class="d-sm-flex flex-wrap gap-1">
                                {!! Form::open([
                                'method' => 'GET',
                                'action' => 'AdminWorkerAttendanceController@attendanceSummary',
                                'class' => 'form-horizontal',
                                ]) !!}
                                @csrf
                                <div class="d-flex gap-1">
                                    <input type="date" name="start_date" id="start_date" class="form-control"
                                        value="{{ isset(request()->start_date) ? request()->start_date : date('Y-m-d') }}"
                                        onchange="return this.form.submit();">
                                    <input type="date" name="end_date" id="end_date" class="form-control"
                                        value="{{ isset(request()->end_date) ? request()->end_date : date('Y-m-d') }}"
                                        onchange="return this.form.submit();">
                                </div>
                                {!! Form::close() !!}
                            </div>

                            <div class="align-self-start mt-3 mt-sm-0 mb-2">
                            </div>
                        </span>

                    </div>
                </div>

                <table id="attendancesummarytable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Worker Name</th>
                            <th>Total Days</th>
                            <th>Present Days</th>
                            <th>Absent Days</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if (count($todayattendanceRecords) > 0)
                        @foreach ($workers as $index => $worker)
                        <?php
                        $presentdays = $absentdays = 0;
                        $presentdays = $todayattendanceRecords->where('worker_id', $worker->id)->count();
                        $absentdays = $totaldays - $presentdays;
                        ?>
                        <tr>
                            <td>{{ $worker->fname }}&nbsp;{{ $worker->lname }}</td>
                            <td>{{ $totaldays }}</td>
                            <td>{{ $presentdays }}</td>
                            <td>{{ $absentdays }}</td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $("#attendancesummarytable").DataTable({
            dom: 'Blfrtip',
            buttons: [{
                extend: 'csv',
            }]
        });
    });
</script>
@endsection