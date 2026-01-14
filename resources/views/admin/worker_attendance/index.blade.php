@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Attendance List</h4>

            <!-- <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                        <li class="breadcrumb-item active">Attendance List</li>
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
                                <h4>Attendance List</h4>
                            </div>

                            <div>
                                {!! Form::open([
                                'method' => 'GET',
                                'action' => 'AdminWorkerAttendanceController@index',
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
                                    <select name="worker_id" id="worker_id" class="form-select"
                                        onchange="return this.form.submit();">
                                        <option value="">Select worker</option>
                                        @foreach ($workers as $worker)
                                        <option value="{{ $worker->id }}"
                                            {{ $worker->id == request()->worker_id ? 'selected' : '' }}>
                                            {{ $worker->fname }}&nbsp;{{ $worker->lname }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                {!! Form::close() !!}
                            </div>

                            <div class="align-self-start mt-3 mt-sm-0 mb-2">
                                <a href="{{ route('admin.attendance.create') }}" class="btn btn-primary text-white"><i
                                        class="fa fa-plus" style="font-size:15px;">&nbsp;ADD</i>
                                </a>
                            </div>
                        </span>

                    </div>
                </div>

                <table id="workerattendancetable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Worker Name</th>
                            <th>Date</th>
                            <th>Check In</th>
                            <th>Check Out</th>
                            <th>Duration</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($todayattendanceRecords as $index => $todayattendanceRecord)
                        <tr>
                            <td>
                                <a href="{{ route('admin.attendance.edit', $todayattendanceRecord->id) }}"
                                    class="btn btn-outline-primary waves-effect waves-light"><i
                                        class="fa fa-edit"></i></a>
                                <a href="{{ route('admin.attendance.destroy', $todayattendanceRecord->id) }}"
                                    onclick="return confirm('Sure ! You want to delete ?');"
                                    class="btn btn-outline-danger waves-effect waves-light"><i
                                        class="fa fa-trash"></i></a>
                            </td>
                            <td>{{ $todayattendanceRecord->worker->fname }}&nbsp;{{ $todayattendanceRecord->worker->lname }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($todayattendanceRecord->date)->format('d-m-Y') }}</td>
                            <td>
                                {{ $todayattendanceRecord->check_in ? \Carbon\Carbon::parse($todayattendanceRecord->check_in)->format('g:i A') : '' }}
                            </td>
                            <td>{{ $todayattendanceRecord->check_out ? \Carbon\Carbon::parse($todayattendanceRecord->check_out)->format('g:i A') : '' }}
                            </td>
                            <td>{{ $todayattendanceRecord->duration }}</td>
                        </tr>
                        @endforeach
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
        $("#workerattendancetable").DataTable({
            dom: 'Blfrtip',
            buttons: [{
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