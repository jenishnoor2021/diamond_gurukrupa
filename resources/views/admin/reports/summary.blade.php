<?php

use App\Models\Dimond;
?>
@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Summary</h4>

            <!-- <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Summary</li>
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

                <form id="myForm" action="{{ route('admin.summary') }}" method="GET">
                    @csrf
                    <div data-repeater-list="group-a">
                        <div data-repeater-item class="row">
                            <div class="mb-3 col-lg-3">
                                <label for="party_id">Party Name</label>
                                <select name="party_id" id="party_id" class="form-select" required>
                                    <option value="">Select party</option>
                                    <option value="All" {{ request()->party_id == 'All' ? 'selected' : '' }}>ALL
                                    </option>
                                    @foreach ($partyLists as $partyList)
                                    <option value="{{ $partyList->id }}"
                                        {{ request()->party_id == $partyList->id ? 'selected' : '' }}>
                                        {{ $partyList->fname }}&nbsp;&nbsp;{{ $partyList->lname }}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('party_id'))
                                <div class="error text-danger">{{ $errors->first('party_id') }}</div>
                                @endif
                            </div>

                            <div class="col-lg-6 d-flex">
                                <div class="gap-2">
                                    <button type="button" id="button1" class="btn btn-success mt-2  w-md">Report</button>
                                    <button type="button" id="button2" class="btn btn-info mt-2  w-md">Export</button>
                                    <a class="btn btn-light mt-2 w-md" href="/admin/summary">Clear</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php if (isset($_GET['party_id'])) { ?>
            <div class="card">
                <div class="card-body">
                    <?php if ($_GET['party_id'] != 'All') { ?>
                        <table id="summaryTable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                    <th>Dimond Name</th>
                                    <th>Barcode</th>
                                    <th>Created Date</th>
                                    <th>Updated Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($partyes as $partyList)
                                <?php
                                $allDimonds = Dimond::where('parties_id', $partyList->id)->where('status', '!=', 'Delivered')->get();
                                ?>
                                @foreach ($allDimonds as $allDimond)
                                <tr>
                                    <td><a href="{{ route('admin.dimond.show', $allDimond->barcode_number) }}"
                                            class="btn btn-outline-info waves-effect waves-light"><i
                                                class="fa fa-eye"></i>
                                        </a>
                                    </td>
                                    <td>{{ $allDimond->dimond_name }}</td>
                                    <td>{{ $allDimond->barcode_number }}</td>
                                    <td>{{ \Carbon\Carbon::parse($allDimond->created_at)->format('d-m-Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($allDimond->updated_at)->format('d-m-Y') }}</td>
                                    <td>{{ $allDimond->status }}</td>
                                </tr>
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    <?php } else { ?>
                        <table id="" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                            <thead>
                                <tr>
                                    <th>Party Name</th>
                                    <th>Pending</th>
                                    <th>Outter</th>
                                    <th>Processing</th>
                                    <th>Completed</th>
                                    <th>Delivered</th>
                                    <th>Total Dimond</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($partyes as $partyList)
                                <?php
                                $totalDimond = Dimond::where('parties_id', $partyList->id)->count();
                                $outterDimond = Dimond::where(['parties_id' => $partyList->id, 'status' => 'OutterProcessing'])->count();
                                $pendingDimond = Dimond::where(['parties_id' => $partyList->id, 'status' => 'Pending'])->count();
                                $processingDimond = Dimond::where('parties_id', $partyList->id)->where('status', 'Processing')->count();
                                $completedDimond = Dimond::where(['parties_id' => $partyList->id, 'status' => 'Completed'])->count();
                                $deliveredDimond = Dimond::where(['parties_id' => $partyList->id, 'status' => 'Delivered'])->count();
                                ?>
                                <tr>
                                    <td>{{ $partyList->fname }}&nbsp;{{ $partyList->lname }}</td>
                                    <td>{{ $pendingDimond }}</td>
                                    <td>{{ $outterDimond }}</td>
                                    <td>{{ $processingDimond }}</td>
                                    <td>{{ $completedDimond }}</td>
                                    <td>{{ $deliveredDimond }}</td>
                                    <td>{{ $totalDimond }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>

    </div>
</div>
<!-- end row -->
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('myForm');
        var button1 = document.getElementById('button1');
        var button2 = document.getElementById('button2');

        button1.addEventListener('click', function() {
            // Change the form action for button 1
            if ($("#party_id").val() == '') {
                alert("Please Select Party");
                return false;
            }

            form.action = "{{ route('admin.summary') }}";
            // Submit the form
            form.submit();
        });

        button2.addEventListener('click', function() {
            if ($("#party_id").val() == '') {
                alert("Please Select Party");
                return false;
            }

            // Change the form action for button 2
            form.action = "{{ route('admin.summary.export') }}";
            // Submit the form
            form.submit();
        });
    });

    $(document).ready(function() {
        $("#summaryTable").DataTable({
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