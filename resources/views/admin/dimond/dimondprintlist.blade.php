<?php

use App\Models\Dimond;
?>
@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Diamond Print List</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                    @endforeach
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form action="{{ route('admin.dimond-print.list') }}" id="myDiamondPrintList" method="get">
                    @csrf
                    <div data-repeater-list="group-a">
                        <div data-repeater-item class="row">
                            <div class="mb-3 col-lg-2">
                                <label for="start_date">Start Date:</label>
                                <input type="date" name="start_date" class="form-control"
                                    id="start_date"
                                    value="<?= isset(request()->start_date) ? request()->start_date : '' ?>" required>
                                @if ($errors->has('start_date'))
                                <div class="error text-danger">{{ $errors->first('start_date') }}</div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label for="end_date">End Date:</label>
                                <input type="date" name="end_date" class="form-control"
                                    id="end_date" value="<?= isset(request()->end_date) ? request()->end_date : '' ?>"
                                    required>
                                @if ($errors->has('end_date'))
                                <div class="error text-danger">{{ $errors->first('end_date') }}</div>
                                @endif
                            </div>

                            <div class="col-lg-1 align-self-center">
                                <div class="d-flex gap-2">
                                    <input type="submit" class="btn btn-success w-md" value="Show" />
                                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/diamondprintlist') }}">Back</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end row -->

@if (count($data) > 0)
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <form action="{{ route('downloadPDF') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success mb-3">Download Selected</button>

                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap mb-0" id="diamondprintTable">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        <div class="form-check font-size-16 align-middle">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                        </div>
                                    </th>
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
                                    <td>
                                        <div class="form-check font-size-16">
                                            <input class="form-check-input" type="checkbox"
                                                name="selected_diamonds[]" value="{{ $da->id }}">
                                        </div>
                                    </td>
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
                    <!-- end table-responsive -->
                </form>
            </div>
        </div>
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

@endsection

@section('script')
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script>
    document.getElementById('selectAll').addEventListener('change', function(e) {
        const checkboxes = document.querySelectorAll('input[name="selected_diamonds[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = e.target.checked;
        });
    });
    $(document).ready(function() {
        $("#diamondprintTable").DataTable();
    });
</script>
@endsection