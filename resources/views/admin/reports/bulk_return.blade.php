@extends('layouts.admin')
@section('content')
<style>
    #scannedDiamondListsReturn table {
        margin-top: 20px;
    }

    #scannedDiamondListsReturn th,
    #scannedDiamondListsReturn td {
        text-align: center;
        vertical-align: middle;
    }

    .remove-diamond {
        padding: 2px 8px;
    }

    .is-invalid {
        border-color: #dc3545 !important;
        background-color: #ffecec !important;
    }

    .invalid-feedback {
        font-size: 0.85em;
        color: #dc3545;
    }
</style>

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Bulk Return</h4>
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

            <div id="scannedDiamondListsReturn">

            </div>

            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-self-center">
                        <div class="gap-2">
                            <button type="button" id="button1" class="btn btn-success mt-2 w-md">Save</button>
                            <a class="btn btn-light mt-2 w-md" href="/admin/bulk-return">Clear</a>
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
                    url: '/admin/check-diamond-status-return',
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
            let table = $('#scannedDiamondListsReturn table');
            if (table.length === 0) {
                $('#scannedDiamondListsReturn').html(`
                <table class="table table-bordered align-middle text-center">
                    <thead class="table-primary">
                        <tr>
                            <th>Action</th>
                            <th>Barcode</th>
                            <th>Diamond Name</th>
                            <th>Issue Weight</th>
                            <th>Return Date</th>
                            <th>Return Weight</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            `);
                table = $('#scannedDiamondListsReturn table');
            }

            // Avoid duplicates
            if (table.find('tr[data-barcode="' + diamond.barcode_number + '"]').length > 0) {
                alert('This diamond already scanned.');
                return;
            }

            let today = new Date().toISOString().split('T')[0];

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
                <td>
                    <input type="date" name="return_dates[${diamond.id}]" value="${today}" class="form-control" required>
                </td>
                <td>
                    <input type="number" step="0.001" name="return_weights[${diamond.id}]" class="form-control" placeholder="Enter return weight" required>
                </td>

                <!-- Hidden inputs for form submission -->
                <input type="hidden" name="diamonds[]" value="${diamond.id}">
                <input type="hidden" name="issue_weights[${diamond.id}]" value="${diamond.issue_weight}">
                <input type="hidden" name="barcode_number[${diamond.id}]" value="${diamond.barcode_number}">
            </tr>
        `);
        }

        $(document).on('input', 'input[name^="return_weights"]', function() {
            let row = $(this).closest('tr');
            let returnWeight = parseFloat($(this).val()) || 0;
            let issueWeight = parseFloat(row.find('td:nth-child(4)').text()) || 0; // Issue Weight column

            if (returnWeight > issueWeight) {
                alert("Return weight cannot be greater than issue weight!");
                $(this).val('');
                $(this).focus();
            }
        });

        // 🔹 Handle remove button click (event delegation)
        $(document).on('click', '.remove-diamond', function() {
            $(this).closest('tr').remove();

            // if no rows left, remove the table
            if ($('#scannedDiamondListsReturn table tbody tr').length === 0) {
                $('#scannedDiamondListsReturn').empty();
            }
        });
    });

    // document.getElementById('button1').addEventListener('click', function() {
    //     var form = document.getElementById('myForm');

    //     if ($('input[name="diamonds[]"]').length === 0) {
    //         alert("Please scan at least one diamond");
    //         return false;
    //     }

    //     form.action = "{{ route('admin.add.bulk-return.store') }}";
    //     form.method = "POST";
    //     form.submit();
    // });

    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('myForm');
        var button1 = document.getElementById('button1');

        button1.addEventListener('click', function() {

            // ✅ Check if any diamond scanned
            if ($('input[name="diamonds[]"]').length === 0) {
                alert("Please scan at least one diamond");
                return false;
            }

            let valid = true;
            let missingWeight = false;
            let missingDate = false;

            // ✅ Loop through all rows and validate inputs
            $('#scannedDiamondListsReturn table tbody tr').each(function() {
                let barcode = $(this).data('barcode');
                let returnWeight = $(this).find('input[name^="return_weights"]').val();
                let returnDate = $(this).find('input[name^="return_dates"]').val();

                // check empty weight
                if (!returnWeight || parseFloat(returnWeight) <= 0) {
                    missingWeight = true;
                    $(this).find('input[name^="return_weights"]').addClass('is-invalid');
                    $(this).find('.invalid-feedback').remove();
                    $(this).find('input[name^="return_weights"]').after('<div class="invalid-feedback d-block">Enter valid return weight</div>');
                    valid = false;
                } else {
                    $(this).find('input[name^="return_weights"]').removeClass('is-invalid');
                    $(this).find('.invalid-feedback').remove();
                }

                // check empty date
                if (!returnDate) {
                    missingDate = true;
                    $(this).find('input[name^="return_dates"]').addClass('is-invalid');
                    $(this).find('.invalid-feedback').remove();
                    $(this).find('input[name^="return_dates"]').after('<div class="invalid-feedback d-block">Select return date</div>');
                    valid = false;
                } else {
                    $(this).find('input[name^="return_dates"]').removeClass('is-invalid');
                    $(this).find('.invalid-feedback').remove();
                }
            });

            // ✅ If any validation failed
            if (!valid) {
                if (missingDate || missingWeight) {
                    alert("Please fill all required fields: Return Date & Return Weight for each diamond.");
                }
                return false;
            }

            // ✅ If all good, submit form
            form.action = "{{ route('admin.add.bulk-return.store') }}";
            form.method = "POST";
            form.submit();
        });
    });
</script>
@endsection