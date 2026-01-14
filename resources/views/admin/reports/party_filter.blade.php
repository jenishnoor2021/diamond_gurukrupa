<?php

use Carbon\Carbon;
use App\Models\Process;
?>
@extends('layouts.admin')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Party Filter</h4>

                <!-- <div class="page-title-right">
                                                <ol class="breadcrumb m-0">
                                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                                                    <li class="breadcrumb-item active">Party Filter</li>
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

                    <form action="{{ route('party.filter') }}" method="GET">
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

                                <div class="mb-3 col-lg-2">
                                    <label for="start_date">Status</label>
                                    <div>
                                        <label class="form-check-label mb-2">
                                            <input type="checkbox" class="form-check-input" name="status[]" value="Pending"
                                                {{ in_array('Pending', (array) request()->status) ? 'checked' : '' }}>
                                            Pending
                                        </label><br />
                                        <label class="form-check-label mb-2">
                                            <input type="checkbox" name="status[]" class="form-check-input"
                                                value="Processing"
                                                {{ in_array('Processing', (array) request()->status) ? 'checked' : '' }}>
                                            Processing
                                        </label><br />
                                        <label class="form-check-label mb-2">
                                            <input type="checkbox" name="status[]" class="form-check-input"
                                                value="OutterProcessing"
                                                {{ in_array('OutterProcessing', (array) request()->status) ? 'checked' : '' }}>
                                            Outter Processing
                                        </label><br />
                                        <label class="form-check-label mb-2">
                                            <input type="checkbox" name="status[]" class="form-check-input"
                                                value="Completed"
                                                {{ in_array('Completed', (array) request()->status) ? 'checked' : '' }}>
                                            Completed
                                        </label><br />
                                        <label class="form-check-label mb-2">
                                            <input type="checkbox" name="status[]" class="form-check-input"
                                                value="Delivered"
                                                {{ in_array('Delivered', (array) request()->status) ? 'checked' : '' }}>
                                            Delivered
                                        </label>
                                    </div>
                                    @if ($errors->has('status'))
                                        <div class="error text-danger">{{ $errors->first('status') }}</div>
                                    @endif
                                </div>

                                <div class="mb-3 col-lg-2">
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
                                </div>

                                <div class="col-lg-2 mt-4">
                                    <div class="d-flex gap-2">
                                        <input type="submit" class="btn btn-success w-md" value="Submit" />
                                        <a class="btn btn-light w-md" href="/admin/party-filter">Clear</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @if (count($dimonds) > 0)
                <div>
                    Total Diamond = <?= count($dimonds) ?>
                </div>
                @foreach ($partyLists as $partyList)
                    <div class="card">
                        <div class="card-body">
                            <center>
                                <h3><u>{{ $partyList->fname }}&nbsp;{{ $partyList->lname }}&nbsp;({{ $partyList->party_code }})</u>
                                </h3>
                            </center>
                            @php
                                $dimondsForParty =
                                    count($dimonds) > 0 ? $dimonds->where('parties_id', $partyList->id) : [];
                            @endphp

                            @if (count($dimondsForParty) > 0)
                                <div>
                                    Total Weight = <?= $dimonds->where('parties_id', $partyList->id)->sum('weight') ?>
                                </div>
                                <table id="partyFTable"
                                    class="table table-bordered dt-responsive nowrap w-100 mt-3 partyFTable">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Action</th>
                                            <th>Party Code</th>
                                            <th>Dimond Name</th>
                                            <th>Row Weight</th>
                                            <th>Polished Weight</th>
                                            <th>Barcode</th>
                                            <th>Status</th>
                                            <th>Shap</th>
                                            <th>clarity</th>
                                            <th>color</th>
                                            <th>cut</th>
                                            <th>polish</th>
                                            <th>symmetry</th>
                                            <th>Created</th>
                                            <!-- <th>Last Modified</th> -->
                                            <th>Deliverd</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($dimondsForParty as $index => $dimond)
                                            <?php
                                            $processes = Process::where(['dimonds_barcode' => $dimond->barcode_number, 'dimonds_id' => $dimond->id])->get();
                                            ?>
                                            <tr>
                                                <td></td>
                                                <td>
                                                    <a href="javascript:void(0);"
                                                        onclick="viewProcesses({{ $dimond->id }}, '{{ $dimond->barcode_number }}')">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                </td>
                                                <td>{{ $dimond->parties->party_code }}</td>
                                                <td>{{ $dimond->dimond_name }}</td>
                                                <td>{{ $dimond->weight }}</td>
                                                <td>{{ $dimond->required_weight }}</td>
                                                <td>{!! $dimond->barcode_number !!}</td>
                                                <td>{!! $dimond->status !!}</td>
                                                <td>{{ $dimond->shape }}</td>
                                                <td>{{ $dimond->clarity }}</td>
                                                <td>{{ $dimond->color }}</td>
                                                <td>{{ $dimond->cut }}</td>
                                                <td>{{ $dimond->polish }}</td>
                                                <td>{{ $dimond->symmetry }}</td>
                                                <td>{{ \Carbon\Carbon::parse($dimond->created_at)->format('d-m-Y') }}</td>
                                                <!-- <td>{{ \Carbon\Carbon::parse($dimond->updated_at)->format('d-m-Y') }}</td> -->
                                                <td>{{ \Carbon\Carbon::parse($dimond->delevery_date)->format('d-m-Y') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <center>
                                    <h5 class="text-danger">No Record Found</h5>
                                </center>
                            @endif
                        </div>
                    </div>
                @endforeach
            @elseif(request()->party_id != '')
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

    <div class="modal fade" id="processModal" tabindex="-1" aria-labelledby="processModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Process Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="processModalBody" style="overflow-x:scroll">
                    <!-- AJAX content loads here -->
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $(".partyFTable").each(function() {
                $(this).DataTable({
                    dom: 'Blfrtip',
                    buttons: [{
                            extend: 'pdf'
                        },
                        {
                            extend: 'csv'
                        },
                        {
                            extend: 'excel'
                        }
                    ]
                });
            });
        });
    </script>
    <script>
        function viewProcesses(dimondId, barcode) {
            $.ajax({
                url: '/get-process-details', // define this route in your web.php
                method: 'POST',
                data: {
                    dimond_id: dimondId,
                    barcode_number: barcode,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#processModalBody').html(response);
                    $('#processModal').modal('show');
                },
                error: function() {
                    alert('Failed to load process details.');
                }
            });
        }
    </script>
@endsection
