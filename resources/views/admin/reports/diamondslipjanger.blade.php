@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Slip By Janger No.</h4>
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

                <form action="{{ route('admin.diamondslipjanger') }}" method="GET">
                    @csrf
                    <div data-repeater-list="group-a">
                        <div data-repeater-item class="row">

                            <div class="mb-3 col-lg-2">
                                <label for="party_id">Party Name</label>
                                <select name="party_id" id="party_id" class="form-select" required>
                                    <option value="">Select party</option>
                                    <!-- <option value="All" {{ request()->party_id == 'All' ? 'selected' : '' }}>ALL
                                    </option> -->
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
                                <label for="janger_no">Janger No</label>
                                <select name="janger_no" id="janger_no" class="form-select" required>
                                    <option value="">Select janger no</option>
                                </select>
                                @if ($errors->has('janger_no'))
                                <div class="error text-danger">{{ $errors->first('janger_no') }}</div>
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

                            <div class="col-lg-2 align-self-center">
                                <div class="d-flex gap-2">
                                    <input type="submit" class="btn btn-success w-md" value="Submit" />
                                    <a class="btn btn-light w-md" href="/admin/diamond_slip_janger">Clear</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        @if (count($dimonds) > 0)
        <div class="card">
            <div class="card-body">

                <form id="checkboxForm" action="{{ route('admin.diamondslipjangerpdf') }}" method="POST">
                    @csrf
                    <table id="diamondslipJangerTable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selectAll"></th>
                                <th>Dimond Name</th>
                                <th>Janger No</th>
                                <th>Row Weight</th>
                                <th>Polished Weight</th>
                                <!-- <th>Barcode</th> -->
                                <!-- <th>Status</th> -->
                                <th>Shap</th>
                                <!-- <th>clarity</th> -->
                                <!-- <th>color</th> -->
                                <th>cut</th>
                                <!-- <th>polish</th> -->
                                <!-- <th>symmetry</th> -->
                                <th>Amount</th>
                                <th>Deliverd</th>
                            </tr>
                        </thead>

                        <button type="submit" class="btn btn-outline-warning waves-effect waves-light">Generate PDF</button>
                        <input type="hidden" id="selectedIds" name="selectedIds">
                        <button type="submit" class="btn btn-outline-info waves-effect waves-light ms-3" formaction="{{ route('admin.diamondslipjangerexcel') }}">Generate Excel</button>

                        <tbody>
                            @foreach ($dimonds as $index => $dimond)
                            <tr>
                                <input type="hidden" id="parties_id" name="parties_id"
                                    value="{{ $dimond->parties_id }}">
                                <td><input type="checkbox" class="checkbox form-check-input" name="selected[]"
                                        value="{{ $dimond->id }}"></td>
                                <td>{{ $dimond->dimond_name }}</td>
                                <td>{{ $dimond->janger_no }}</td>
                                <td>{{ $dimond->weight }}</td>
                                <td>{{ $dimond->required_weight }}</td>
                                <!-- <td>{!! $dimond->barcode_number !!}</td> -->
                                <!-- <td>{!! $dimond->status !!}</td> -->
                                <td>{{ $dimond->shape }}</td>
                                <!-- <td>{{ $dimond->clarity }}</td>
                                <td>{{ $dimond->color }}</td> -->
                                <td>{{ $dimond->cut }}</td>
                                <!-- <td>{{ $dimond->polish }}</td> -->
                                <!-- <td>{{ $dimond->symmetry }}</td> -->
                                <td>{{$dimond->amount}}</td>
                                <td>{{ \Carbon\Carbon::parse($dimond->delevery_date)->format('d-m-Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </form>

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

    </div>
</div>
<!-- end row -->
@endsection

@section('script')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var selectedIds = [];
        var table = $('#diamondslipJangerTable').DataTable(); // Replace with your actual table ID

        // Handle individual checkbox change
        $('#diamondslipJangerTable').on('change', '.checkbox', function() {
            var value = this.value;
            if (this.checked) {
                if (!selectedIds.includes(value)) {
                    selectedIds.push(value);
                }
            } else {
                selectedIds = selectedIds.filter(id => id !== value);
            }

            document.getElementById('selectedIds').value = selectedIds.join(',');
        });
        $('#selectAll').on('change', function() {
            var isChecked = this.checked;

            // Loop through all rows (even those not visible)
            table.rows().every(function() {
                var row = this.node();
                var checkbox = $(row).find('input[name="selected[]"]');

                if (checkbox.length) {
                    checkbox.prop('checked', isChecked);
                    checkbox.trigger('change'); // triggers the logic above
                }
            });
        });
        $('#diamondslipJangerTable').on('change', '.checkbox', function() {
            var value = this.value;

            if (this.checked) {
                if (!selectedIds.includes(value)) {
                    selectedIds.push(value); // ✅ Added to array
                }
            } else {
                selectedIds = selectedIds.filter(id => id !== value); // ✅ Removed from array
            }

            document.getElementById('selectedIds').value = selectedIds.join(',');
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#party_id').change(function() {
            var party = $(this).val();
            $('#janger_no').empty();

            if (party) {
                $.ajax({
                    type: 'POST',
                    url: '/admin/get-janger-list',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'party': party,
                    },
                    success: function(data) {
                        $('#janger_no').append('<option value="">Select janger no</option><option value="all">ALL</option>');
                        $.each(data, function(key, value) {
                            $('#janger_no').append('<option value="' + value
                                .janger_no + '">' + value.janger_no + '</option>');
                        });
                    }
                });
            } else {
                $('#janger_no').append('<option value="">Select janger no</option>');
            }
        });
    });
</script>
@endsection