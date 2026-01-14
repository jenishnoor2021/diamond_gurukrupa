@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Check Out</h4>

            <!-- <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                    <li class="breadcrumb-item active">Check Out</li>
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
                            <div class="d-sm-flex flex-wrap gap-1">
                                <h4>Check Out</h4>
                            </div>

                            <h4 id="" class="render-range fw-bold">
                                <form method="POST" action="{{ route('admin.check-out.store') }}">
                                    @csrf
                                    <input type="number" id="inputField" class="form-control" name="inputField"
                                        placeholder="Check Out barcode" required>
                                </form>
                            </h4>

                            <div class="align-self-start mt-3 mt-sm-0 mb-2">
                            </div>

                        </span>

                    </div>
                </div>

                <table id="dailytable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Worker Name</th>
                            <th>Date</th>
                            <th>check In</th>
                            <th>check Out</th>
                            <th>Duration</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($todayattendanceRecords as $index => $todayattendanceRecord)
                        <tr>
                            <td>{{ $todayattendanceRecord->worker->fname }}&nbsp;{{ $todayattendanceRecord->worker->lname }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($todayattendanceRecord->date)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($todayattendanceRecord->check_in)->format('g:i A') }}</td>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-focus on the input field when the page loads
        document.getElementById('inputField').focus();
    });
</script>
@endsection