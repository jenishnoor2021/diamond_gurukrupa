<?php

use App\Models\Process;
?>
@extends('layouts.admin')
@section('content')

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Diamond List</h4>

            <!-- <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                    <li class="breadcrumb-item active">Diamond List</li>
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

                <div id="right">
                    <div id="menu" class="mb-3">

                        <span id="menu-navi"
                            class="d-sm-flex flex-wrap text-center text-sm-start justify-content-sm-between">
                            <div class="gap-1">
                                <a class="btn btn-info waves-effect waves-light" href="{{ route('admin.dimond.create') }}">
                                    <i class="fa fa-plus editable" style="font-size:15px;">&nbsp;ADD</i>
                                </a>
                                <a class="btn btn-primary waves-effect waves-light" href="{{ route('admin.dimond.import') }}" style="font-size:15px;">
                                    Import
                                </a>
                            </div>

                            <h4 id="renderRange" class="render-range fw-bold pt-1 mx-3">
                                <form method="GET" action="{{ route('dimond.detail') }}" class="mx-auto">
                                    @csrf
                                    <input type="text" id="inputField1" class="form-control" name="inputField"
                                        placeholder="Search barcode" required>
                                </form>
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                                @endif
                            </h4>

                            <div class="align-self-start mt-3 mt-sm-0 mb-2">
                            </div>
                        </span>

                    </div>
                </div>

                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>PArty Name</th>
                            <th>Dimond Name</th>
                            <th>Row Weight</th>
                            <th>Polished Weight</th>
                            <th>Barcode</th>
                            <!-- <th>Barcode show</th> -->
                            <th>Detail</th>
                            <th>Status</th>
                            <th>Process</th>
                            <!-- <th>Shap</th>
                                     <th>clarity</th>
                                     <th>color</th>
                                     <th>cut</th>
                                     <th>polish</th>
                                     <th>symmetry</th> -->
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($dimonds as $index => $dimond)
                        @php
                        $process = Process::where('dimonds_id', $dimond->id)->latest()->first();
                        $designation = isset($process) ? $process->designation : '';
                        @endphp
                        <tr>
                            <td>
                                <a href="/admin/print-image/{{ $dimond->id }}" target="_blank"
                                    class="btn btn-primary">Print</a>
                                <a href="{{ route('admin.dimond.show', $dimond->barcode_number) }}" class="btn btn-info"><i class="fa fa-eye"></i></a>
                                <a href="{{ route('admin.dimond.edit', $dimond->id) }}" class="btn btn-outline-primary waves-effect waves-light"><i class="fa fa-edit"></i></a>
                                <a href="{{ route('admin.dimond.destroy', $dimond->id) }}" onclick="return confirm('Sure ! You want to delete ?');" class="btn btn-outline-danger waves-effect waves-light"><i class="fa fa-trash"></i></a>
                            </td>
                            <td>{{ $dimond->parties->party_code }}</td>
                            <td>{{ $dimond->dimond_name }}</td>
                            <td>{{ $dimond->weight }}</td>
                            <td>{{ $dimond->required_weight }}</td>
                            <td>{!! $dimond->barcode_number !!}</td>
                            <!-- <td><svg id="barcode_{{ $index }}" style="display:none"></svg>
                                    <button id="{{ $index }}" onclick="getbarcode(this.id,<?php echo $dimond->barcode_number; ?>)">show</button>
                                 </td> -->
                            <td>
                                <div id="animalstatus{{ $dimond->id }}" onclick="addappdata(this.id)"
                                    style="border:0px solid;"><i class="fa fa-plus-circle mt-2 text-warning"
                                        aria-hidden="true"></i>show
                                </div>
                                <div id="showsolddetailsanimalstatus{{ $dimond->id }}" style="display:none">
                                    <p><span class="text-warning">Shap :</span> {{ $dimond->shape }}</p>
                                    <p><span class="text-warning">clarity :</span> {{ $dimond->clarity }}</p>
                                    <p><span class="text-warning">color :</span> {{ $dimond->color }}</p>
                                    <p><span class="text-warning">cut :</span> {{ $dimond->cut }}</p>
                                    <p><span class="text-warning">polish :</span> {{ $dimond->polish }}</p>
                                    <p><span class="text-warning">symmetry :</span> {{ $dimond->symmetry }}</p>
                                </div>
                            </td>
                            <td>{!! $dimond->status !!}</td>
                            <td>{{ $designation }}</td>
                            <!-- <td>{{ $dimond->shape }}</td>
                                            <td>{{ $dimond->clarity }}</td>
                                            <td>{{ $dimond->color }}</td>
                                            <td>{{ $dimond->cut }}</td>
                                            <td>{{ $dimond->polish }}</td>
                                            <td>{{ $dimond->symmetry }}</td> -->
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
    function getbarcode(index, value) {
        document.getElementById('barcode_' + index).style.display = "block";
        JsBarcode("#barcode_" + index, value, {
            format: "CODE128",
            displayValue: true,
            height: 100,
            width: 4,
            fontOptions: "bold",
            fontSize: 40,
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-focus on the input field when the page loads
        document.getElementById('inputField1').focus();
    });

    function addappdata(cli_id) {
        // $("#showsolddetails"+cli_id).show();
        var div = document.getElementById("showsolddetails" + cli_id);
        if (div.style.display !== "block") {
            div.style.display = "block";
        } else {
            div.style.display = "none";
        }
    }
</script>
@endsection