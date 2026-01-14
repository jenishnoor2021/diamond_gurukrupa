<?php

use App\Models\Dimond;
?>
@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Party Diamond Transfer</h4>

            <!-- <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                    <li class="breadcrumb-item active">Party Diamond Transfer</li>
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

                <form action="{{ route('admin.transfer.list') }}" name="dimondtransferform" method="get">
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

                            <div class="mb-3 col-lg-3">
                                <label for="diamond_status">Type</label>
                                <select name="diamond_status" id="diamond_status" class="form-select" required>
                                    <option value="">Select Status</option>
                                    <option value="Completed" {{ request()->diamond_status == 'Completed' ? 'selected' : '' }}>Ready
                                    </option>
                                    <option value="Processing" {{ request()->diamond_status == 'Processing' ? 'selected' : '' }}>Processing
                                    </option>
                                    <option value="OutterProcessing" {{ request()->diamond_status == 'OutterProcessing' ? 'selected' : '' }}>Outter Processing
                                    </option>
                                    <option value="Pending" {{ request()->diamond_status == 'Pending' ? 'selected' : '' }}>Pending
                                    </option>
                                    <option value="Repair" {{ request()->diamond_status == 'Repair' ? 'selected' : '' }}>Repair
                                    </option>
                                </select>
                                @if ($errors->has('diamond_status'))
                                <div class="error text-danger">{{ $errors->first('diamond_status') }}</div>
                                @endif
                            </div>

                            <!-- <div class="mb-3 col-lg-2">
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
                            </div> -->

                            <div class="col-lg-1 align-self-center">
                                <div class="d-flex gap-2">
                                    <input type="submit" class="btn btn-success w-md" value="Show" />
                                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/transfer') }}">Back</a>
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

                <form action="{{ route('transferdiamonds') }}" method="POST" id="transferDiamondForm">
                    @csrf

                    @if(request()->diamond_status == 'Completed')
                    <button type="submit" id="just_delivered" class="btn btn-success mb-3">Delivered Diamonds</button>
                    <button type="submit" id="delivered_and_print" class="btn btn-info mb-3">Delivered & print Diamonds</button>
                    @endif

                    <input type="hidden" name="del_and_pri" id="del_and_pri" value="">

                    <div class="table-responsive">
                        <table class="table align-middle table-nowrap mb-0" id="diamondprintTable">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        @if(request()->diamond_status == 'Completed')
                                        <div class="form-check font-size-16 align-middle">
                                            <input class="form-check-input" type="checkbox" id="selectAll">
                                        </div>
                                        @endif
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
                                        @if(request()->diamond_status == 'Completed')
                                        <div class="form-check font-size-16">
                                            <input class="form-check-input" type="checkbox"
                                                name="selected_diamonds[]" value="{{ $da->id }}">
                                        </div>
                                        @else
                                        <a href="{{ route('admin.dimond.show', $da->barcode_number) }}" class="btn btn-info"><i class="fa fa-eye"></i></a>
                                        @endif
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

@endsection

@section('script')

<script>
    $(document).ready(function() {
        $("form[name='dimondtransferform']").validate({
            ignore: [],
            rules: {
                party_id: {
                    required: true,
                },
                diamond_status: {
                    required: true,
                },
            },
            messages: {
                party_id: "Please select a party.",
                diamond_status: "Please select a status."
            },
            errorPlacement: function(error, element) {
                error.appendTo(element.parent());
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>

<!-- <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script> -->
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

<script>
    document.getElementById('just_delivered').addEventListener('click', function() {
        document.getElementById('del_and_pri').value = '';
    });

    document.getElementById('delivered_and_print').addEventListener('click', function() {
        document.getElementById('del_and_pri').value = '1';
    });
</script>
@endsection