@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Diamond Report</h4>

            <!-- <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Diamond Report</li>
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

                <form action="{{ route('admin.add-dimond.list') }}" id="myDiamondList" method="get">
                    @csrf
                    <div data-repeater-list="group-a">
                        <div data-repeater-item class="row">
                            <div class="mb-3 col-lg-2">
                                <label for="start_date">Start Date:</label>
                                <input type="date" name="start_date" class="form-control" id="start_date"
                                    value="<?= isset(request()->start_date) ? request()->start_date : '' ?>" required>
                                @if ($errors->has('start_date'))
                                <div class="error text-danger">{{ $errors->first('start_date') }}</div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label for="end_date">End Date:</label>
                                <input type="date" name="end_date" class="form-control" id="end_date"
                                    value="<?= isset(request()->end_date) ? request()->end_date : '' ?>" required>
                                @if ($errors->has('end_date'))
                                <div class="error text-danger">{{ $errors->first('end_date') }}</div>
                                @endif
                            </div>

                            <div class="col-lg-2 align-self-center">
                                <div class="d-flex gap-2">
                                    <input type="submit" class="btn btn-success w-md" value="Submit" />
                                    <a class="btn btn-light w-md" href="/admin/dimond_list">Clear</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        @if (count($data) > 0)
        <div class="card">
            <div class="card-body">

                <table id="exportworkerTable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Sr.</th>
                            <th>Dimond Name</th>
                            <th>Dimond barcode</th>
                            <th>Created Date</th>
                            <th>Delivery Date</th>
                        </tr>
                    </thead>
                    @php
                    $p = 1;
                    @endphp
                    <tbody>
                        @foreach ($data as $key => $da)
                        <tr>
                            <td>{{ $p }}</td>
                            <td>{{ $da->dimond_name }}</td>
                            <td>{{ $da->barcode_number }}</td>
                            <td>{{ \Carbon\Carbon::parse($da->created_at)->format('d-m-Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($da->delevery_date)->format('d-m-Y') }}</td>
                            @php
                            $p += 1;
                            @endphp
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
        @elseif(request()->start_date != '')
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
<script>
    $(document).ready(function() {
        $("#exportworkerTable").DataTable({
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