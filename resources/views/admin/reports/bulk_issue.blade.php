@extends('layouts.admin')
@section('content')
<style>
    #scannedDiamondLists table {
        margin-top: 20px;
    }

    #scannedDiamondLists th,
    #scannedDiamondLists td {
        text-align: center;
        vertical-align: middle;
    }

    .remove-diamond {
        padding: 2px 8px;
    }
</style>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Bulk Issue</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">

        <form id="myForm">
            @csrf

            <div class="card">
                <div class="card-body">

                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="mdi mdi-block-helper me-2"></i> Please fix the following errors:</strong>
                        <ul class="mt-2 mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

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

                    <div data-repeater-list="group-a">
                        <div data-repeater-item class="row">
                            <div class="mb-3 col-lg-2">
                                <label for="category">Category</label>
                                <select name="category" id="category" class="form-select" required>
                                    <option value="">Select category</option>
                                    <!-- <option value="all" {{ request()->category == 'all' ? 'selected' : '' }}>ALL
                                    </option> -->
                                    <option value="Inner" {{ request()->category == 'Inner' ? 'selected' : '' }}>Inner
                                        Worker</option>
                                    <option value="Outter" {{ request()->category == 'Outter' ? 'selected' : '' }}>
                                        Outter Worker</option>
                                </select>
                                @if ($errors->has('category'))
                                <div class="error text-danger">{{ $errors->first('category') }}</div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label for="designation">Designation</label>
                                <select name="designation" id="designation" class="form-select" required>
                                    <option value="">Select designation</option>
                                    <!-- <option value="all" {{ request()->designation == 'all' ? 'selected' : '' }}>ALL
                                    </option> -->
                                    @foreach ($designations as $designation)
                                    <option value="{{ $designation->name }}"
                                        {{ request()->designation == $designation->name ? 'selected' : '' }}>
                                        {{ $designation->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('designation'))
                                <div class="error text-danger">{{ $errors->first('designation') }}</div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label for="worker_name">Worker Name</label>
                                <select name="worker_name" id="worker_name" class="form-select" required>
                                    <option value="">Select worker</option>
                                    <!-- <option value="all" {{ request()->worker_name == 'all' ? 'selected' : '' }}>ALL
                                    </option>
                                    @foreach ($workerLists as $workerList)
                                    <option value="{{ $workerList->fname }}"
                                        {{ request()->worker_name == $workerList->fname ? 'selected' : '' }}>
                                        {{ $workerList->fname }}&nbsp;&nbsp;{{ $workerList->lname }}
                                    </option>
                                    @endforeach -->
                                </select>
                                @if ($errors->has('worker_name'))
                                <div class="error text-danger">{{ $errors->first('worker_name') }}</div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label for="issue_date">Issue Date</label>
                                <input type="date" name="issue_date" class="form-control" id="issue_date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" required>
                                @if($errors->has('issue_date'))
                                <div class="error text-danger">{{ $errors->first('issue_date') }}</div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-3">
                                <label for="find_barcode">Scann Diamond</label>
                                <input type="text" name="find_barcode" class="form-control" id="find_barcode" value="" placeholder="Scann barcode">
                                @if($errors->has('find_barcode'))
                                <div class="error text-danger">{{ $errors->first('find_barcode') }}</div>
                                @endif
                            </div>

                        </div>



                    </div>

                </div>
            </div>

            <div id="scannedDiamondLists">

            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-self-center">
                        <div class="gap-2">
                            <button type="button" id="button1" class="btn btn-success mt-2 w-md">Save</button>
                            <a class="btn btn-light mt-2 w-md" href="/admin/bulk-issue">Clear</a>
                        </div>
                    </div>

                </div>
            </div>

        </form>

    </div>
</div>
<!-- end row -->
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('#designation').change(function() {
            var designation = $(this).val();
            $('#worker_name').empty();

            if (designation == 'all') {
                $('#worker_name').append('<option value="">Select worker</option>');
                // $('#worker_name').append('<option value="all">ALL</option>');
            } else if (designation && designation != 'all') {
                $.ajax({
                    type: 'POST',
                    url: '/admin/get-workers',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'designation': designation,
                    },
                    success: function(data) {
                        $('#worker_name').append('<option value="">Select worker</option>');
                        // $('#worker_name').append('<option value="all">ALL</option>');
                        $.each(data, function(key, value) {
                            $('#worker_name').append('<option value="' + value
                                .fname + '">' + value.fname + ' ' + value
                                .lname + '</option>');
                        });
                    }
                });
            } else {
                $('#worker_name').append('<option value="">Select worker</option>');
            }
        });
        $('#category').change(function() {
            var category = $(this).val();
            $('#designation').empty();
            $('#worker_name').empty();
            $('#worker_name').append('<option value="">Select worker</option>');

            if (category) {
                $.ajax({
                    type: 'POST',
                    url: '/admin/get-designation',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'category': category,
                    },
                    success: function(data) {
                        $('#designation').append('<option value="">Select designation</option>');
                        /* $('#designation').append('<option value="all">ALL</option>'); */
                        $.each(data, function(key, value) {
                            $('#designation').append('<option value="' + value
                                .name + '">' + value.name + '</option>');
                        });
                    }
                });
            } else {
                $('#designation').append('<option value="">Select designation</option>');
            }
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('myForm');
        var button1 = document.getElementById('button1');

        button1.addEventListener('click', function() {
            // Change the form action for button 1
            if ($("#worker_name").val() == '') {
                alert("Please Select Worker");
                return false;
            }

            if ($("#issue_date").val() == '') {
                alert("Please Select issue date");
                return false;
            }

            if ($('input[name="diamonds[]"]').length === 0) {
                alert("Please scan at least one diamond");
                return false;
            }

            form.action = "{{ route('admin.add.bulk-issue.store') }}";
            form.method = "POST";
            form.submit();
        });
    });
</script>

<script>
    $(document).ready(function() {
        // When user scans barcode and presses Enter
        $('#find_barcode').on('keypress', function(e) {
            if (e.which === 13) { // Enter key pressed
                e.preventDefault();
                let barcode = $(this).val().trim();

                if (barcode === '') {
                    alert('Please scan a diamond barcode');
                    return false;
                }

                $.ajax({
                    url: '/admin/check-diamond-status',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        barcode: barcode
                    },
                    success: function(response) {
                        if (response.status === 'error') {
                            alert(response.message);
                        } else if (response.status === 'success') {
                            // Add diamond row to the table
                            appendDiamondToList(response.data);
                        }
                        $('#find_barcode').val('').focus(); // ready for next scan
                    },
                    error: function() {
                        alert('Something went wrong. Try again.');
                        $('#find_barcode').val('').focus();
                    }
                });
            }
        });

        // Function to append diamond data to scanned list table
        function appendDiamondToList(diamond) {
            let table = $('#scannedDiamondLists table');
            if (table.length === 0) {
                $('#scannedDiamondLists').html(`
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>Action</th>
                            <th>Barcode</th>
                            <th>Diamond Name</th>
                            <th>Weight</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            `);
                table = $('#scannedDiamondLists table');
            }

            // Avoid duplicates
            if (table.find('tr[data-barcode="' + diamond.barcode_number + '"]').length > 0) {
                alert('This diamond already scanned.');
                return;
            }

            table.find('tbody').append(`
            <tr data-barcode="${diamond.barcode_number}">
                <td>
                    <button type="button" class="btn btn-sm btn-danger remove-diamond">
                        <i class="mdi mdi-delete"></i>
                    </button>
                </td>
                <td>${diamond.barcode_number}</td>
                <td>${diamond.dimond_name ?? ''}</td>
                <td>${diamond.issue_weight ?? ''}</td>

                <!-- Hidden inputs for form submission -->
                <input type="hidden" name="diamonds[]" value="${diamond.id}">
                <input type="hidden" name="issue_weights[${diamond.id}]" value="${diamond.issue_weight}">
                <input type="hidden" name="barcode_number[${diamond.id}]" value="${diamond.barcode_number}">
            </tr>
        `);
        }

        // 🔹 Handle remove button click (event delegation)
        $(document).on('click', '.remove-diamond', function() {
            $(this).closest('tr').remove();

            // if no rows left, remove the table
            if ($('#scannedDiamondLists table tbody tr').length === 0) {
                $('#scannedDiamondLists').empty();
            }
        });
    });
</script>
@endsection