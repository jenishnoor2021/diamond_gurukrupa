@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Worker Range List</h4>

            <!-- <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                    <li class="breadcrumb-item active">Worker Range List</li>
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

                {!! Form::open(['method'=>'POST', 'action'=> 'AdminWorkerRangeController@store','files'=>true,'class'=>'form-horizontal','name'=>'addworkerrangeform']) !!}
                @csrf

                <div class="row">
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="shape" class="form-label">Shape</label>
                            <select name="shape" id="shape" class="form-select" required>
                                <option value="">Select shape</option>
                                <option value="Round">Round</option>
                                <option value="Other">Other</option>
                            </select>
                            @if($errors->has('shape'))
                            <div class="error text-danger">{{ $errors->first('shape') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="min_value" class="form-label">Min</label>
                            <input type="text" name="min_value" class="form-control" style="background-color:#ccc;" id="min_value" placeholder="Enter min value" value="{{ old('min_value') }}" readonly required>
                            @if($errors->has('min_value'))
                            <div class="error text-danger">{{ $errors->first('min_value') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="max_value" class="form-label">Max</label>
                            <input type="text" name="max_value" class="form-control" id="max_value" placeholder="Enter max value" value="{{ old('max_value') }}" oninput="formatMaxValue(this);" required>
                            @if($errors->has('max_value'))
                            <div class="error text-danger">{{ $errors->first('max_value') }}</div>
                            @endif
                            <div class="error text-danger" id="max_value_error" style="display:none;">
                                Max value must be greater than Min value!
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="value" class="form-label">Default Value</label>
                            <input type="text" name="value" class="form-control" id="value" placeholder="Enter value" value="{{ old('value') }}" oninput="formatValue(this);" onkeypress="return isNumberKey(event)" required>
                            <span style="color:red;font-size:12px;">Default value Enter 1</span>
                            @if($errors->has('value'))
                            <div class="error text-danger">{{ $errors->first('value') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary w-md">Submit</button>
                            <a class="btn btn-light w-md" href="{{ URL::to('/admin/worker_range') }}">Back</a>
                        </div>
                    </div>
                </div>
                </form>

                <hr style="border:1px solid #000;">
                <table id="" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Key</th>
                            <th>Shape</th>
                            <th>Min</th>
                            <th>Max</th>
                            <th>Default Value</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($workerranges as $workerrange)
                        <tr>
                            <td>
                                <!-- <a href="{{ route('admin.worker_range.edit', $workerrange->id) }}"
                                    class="btn btn-outline-primary waves-effect waves-light"><i
                                        class="fa fa-edit"></i></a> -->
                                <a href="{{ route('admin.worker_range.destroy', $workerrange->id) }}"
                                    onclick="return confirm('Sure ! You want to delete ?');"
                                    class="btn btn-outline-danger waves-effect waves-light"><i
                                        class="fa fa-trash"></i></a>
                            </td>
                            <td>{{$workerrange->key}}</td>
                            <td>{{$workerrange->shape}}</td>
                            <td>{{$workerrange->min_value}}</td>
                            <td>{{$workerrange->max_value}}</td>
                            <td>{{$workerrange->value}}</td>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function() {
        $("form[name='addworkerrangeform']").validate({
            rules: {
                shape: {
                    required: true,
                },
                min_value: {
                    required: true,
                },
                max_value: {
                    required: true,
                },
                value: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });

    function formatMaxValue(input) {
        // Remove any non-numeric characters
        var cleanedValue = input.value.replace(/[^0-9.]/g, '');

        // Ensure valid pattern: either empty, '0.00', or '00.00'
        var match = cleanedValue.match(/^(\d{0,2}(\.\d{0,2})?)?$/);

        // Update the input value with the formatted result
        input.value = match ? match[1] || '' : '';

        // Get min and max values
        var minValue = parseFloat(document.getElementById('min_value').value) || 0;
        var maxValue = parseFloat(input.value) || 0;
        var errorDiv = document.getElementById('max_value_error');

        // Check if max_value is greater than min_value
        if (maxValue < minValue) {
            errorDiv.style.display = 'block'; // Show error message
        } else {
            errorDiv.style.display = 'none'; // Hide error message
        }
    }

    function isNumberKey(evt) {
        const charCode = evt.which ? evt.which : evt.keyCode;
        const inputValue = evt.target.value;

        // Allow: numbers (0-9)
        if (charCode >= 48 && charCode <= 57) {
            return true;
        }

        // Allow: only one dot (.)
        if (charCode === 46 && !inputValue.includes('.')) {
            return true;
        }

        // Block everything else
        return false;
    }

    function formatValue(input) {
        let value = input.value;

        // Allow only 2 digits after dot
        if (value.includes('.')) {
            const [integerPart, decimalPart] = value.split('.');
            input.value = integerPart + '.' + decimalPart.substring(0, 2);
        }
    }

    $(document).ready(function() {
        $('#shape').change(function() {
            var selectedShape = $(this).val();

            if (selectedShape) {
                $.ajax({
                    url: '{{ route("getWorkerMinValue") }}',
                    type: 'GET',
                    data: {
                        shape: selectedShape
                    },
                    success: function(response) {
                        $('#min_value').val(response.min_value);
                    }
                });
            }
        });
    });
</script>
@endsection