@extends('layouts.admin')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">ADD Worker</h4>

                <!-- <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                       <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                                       <li class="breadcrumb-item active">ADD Worker</li>
                                    </ol>
                                 </div> -->

            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title mb-4">ADD</h4>

                    {!! Form::open([
                        'method' => 'POST',
                        'action' => 'AdminWorkerController@store',
                        'files' => true,
                        'class' => 'form-horizontal',
                        'name' => 'addworkerform',
                    ]) !!}
                    @csrf

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="fname">First Name</label>
                                <input type="text" name="fname" class="form-control" id="fname"
                                    placeholder="Enter First Name" onkeypress='return (event.charCode != 32)'
                                    value="{{ old('fname') }}" required>
                                @if ($errors->has('fname'))
                                    <div class="error text-danger">{{ $errors->first('fname') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="lname">Last Name</label>
                                <input type="text" name="lname" class="form-control" id="lname"
                                    placeholder="Enter Last Name" onkeypress='return (event.charCode != 32)'
                                    value="{{ old('lname') }}" required>
                                @if ($errors->has('lname'))
                                    <div class="error text-danger">{{ $errors->first('lname') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="designation">Designation</label>
                                <select name="designation" id="designation" class="form-select" required>
                                    <option value="">Select designation</option>
                                    @foreach ($designations as $designation)
                                        <option value="{{ $designation->name }}" data-id="{{ $designation->id }}">
                                            {{ $designation->name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('designation'))
                                    <div class="error text-danger">{{ $errors->first('designation') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="remark">Remark / katori</label>
                        <textarea type="text" name="remark" class="form-control" id="remark" placeholder="Enter Remark / katori">{{ old('remark') }}</textarea>
                        @if ($errors->has('remark'))
                            <div class="error text-danger">{{ $errors->first('remark') }}</div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="address">Address</label>
                        <textarea type="text" name="address" class="form-control" id="address" placeholder="Enter Address">{{ old('address') }}</textarea>
                        @if ($errors->has('address'))
                            <div class="error text-danger">{{ $errors->first('address') }}</div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="mobile">Mobile no</label>
                                <input type="number" name="mobile" class="form-control" id="mobile"
                                    placeholder="Enter number" value="{{ old('mobile') }}">
                                @if ($errors->has('mobile'))
                                    <div class="error text-danger">{{ $errors->first('mobile') }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="mb-3">
                                <label for="aadhar_no">Aadhar Number</label>
                                <input type="text" name="aadhar_no" class="form-control" id="aadhar_no"
                                    oninput="formatAadharInput(this)" placeholder="Enter aadhar no"
                                    value="{{ old('aadhar_no') }}">
                                @if ($errors->has('aadhar_no'))
                                    <div class="error text-danger">{{ $errors->first('aadhar_no') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <label class="form-check-label" for="roundCheckbox">
                                <input class="form-check-input" type="checkbox" id="roundCheckbox">
                                Round Rate
                            </label>
                        </div>
                    </div>

                    <div id="roundDiv" style="display: none;">
                        <div class="row">
                            @foreach ($roundworkerrangs as $roundworkerrang)
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="{{ $roundworkerrang->key }}" class="form-label">Rate
                                            ({{ $roundworkerrang->min_value }} to {{ $roundworkerrang->max_value }})
                                        </label>
                                        <input type="number" name="{{ $roundworkerrang->key }}" class="form-control"
                                            id="{{ $roundworkerrang->key }}" placeholder="Enter amount"
                                            value="{{ $roundworkerrang->value }}">
                                        @if ($errors->has('$roundworkerrang->key'))
                                            <div class="error text-danger">{{ $errors->first('$roundworkerrang->key') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <label class="form-check-label" for="fancyCheckbox">
                                <input class="form-check-input" type="checkbox" id="fancyCheckbox">
                                Fancy Rate
                            </label>
                        </div>
                    </div>

                    <div id="fancyDiv" style="display: none;">
                        <div class="row">
                            @foreach ($otherworkerrangs as $otherworkerrang)
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="{{ $otherworkerrang->key }}" class="form-label">Rate
                                            ({{ $otherworkerrang->min_value }} to
                                            {{ $otherworkerrang->max_value }})
                                        </label>
                                        <input type="number" name="{{ $otherworkerrang->key }}" class="form-control"
                                            id="{{ $otherworkerrang->key }}" placeholder="Enter amount"
                                            value="{{ $otherworkerrang->value }}">
                                        @if ($errors->has('$otherworkerrang->key'))
                                            <div class="error text-danger">{{ $errors->first('$otherworkerrang->key') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <label class="form-check-label" for="myCheckbox">
                                <input class="form-check-input" type="checkbox" id="myCheckbox">
                                Add Bank detail
                            </label>
                        </div>
                    </div>

                    <div id="myDiv" style="display: none;">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="bank_name">Bank name</label>
                                    <input type="text" name="bank_name" class="form-control" id="bank_name"
                                        placeholder="Enter bank name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="ifsc_code">IFSC code</label>
                                    <input type="text" name="ifsc_code" class="form-control" id="ifsc_code"
                                        placeholder="Enter IFSC code">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="account_holder_name">Account Holder name</label>
                                    <input type="text" name="account_holder_name" class="form-control"
                                        id="account_holder_name" placeholder="Enter Account Holder name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="account_no">Account Number</label>
                                    <input type="number" name="account_no" class="form-control" id="account_no"
                                        placeholder="Enter Account number">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-md">Submit</button>
                        <a class="btn btn-light w-md" href="{{ URL::to('/admin/worker') }}">Back</a>
                    </div>
                    </form>
                </div>
                <!-- end card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <!-- end row -->
@endsection

@section('script')
    <script>
        $(function() {
            $("form[name='addworkerform']").validate({
                rules: {
                    fname: {
                        required: true,
                    },
                    lname: {
                        required: true,
                    },
                    // address: {
                    //    required: true,
                    // },
                    // mobile: {
                    //    required: true,
                    // },
                    designation: {
                        required: true,
                    },
                    // aadhar_no: {
                    //    required: true,
                    // }
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var checkbox = document.getElementById('myCheckbox');
            var div = document.getElementById('myDiv');

            var roundcheckbox = document.getElementById('roundCheckbox');
            var rounddiv = document.getElementById('roundDiv');

            var fancycheckbox = document.getElementById('fancyCheckbox');
            var fancydiv = document.getElementById('fancyDiv');

            checkbox.addEventListener('change', function() {
                div.style.display = checkbox.checked ? 'block' : 'none';
            });

            roundcheckbox.addEventListener('change', function() {
                rounddiv.style.display = roundcheckbox.checked ? 'block' : 'none';
            });

            fancycheckbox.addEventListener('change', function() {
                fancydiv.style.display = fancycheckbox.checked ? 'block' : 'none';
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#designation').change(function() {
                let designation = $(this).val();

                if (designation) {
                    $.ajax({
                        url: '{{ route('designation.getRates') }}', // adjust your route name
                        method: 'GET',
                        data: {
                            designation: designation
                        },
                        success: function(response) {
                            @foreach ($roundworkerrangs as $roundworkerrang)
                                {
                                    const rk = "{{ $roundworkerrang->key }}";
                                    const rv = response[rk] !== undefined ? response[rk] :
                                        "{{ $roundworkerrang->value }}";
                                    $('#' + rk).val(rv);
                                }
                            @endforeach

                            @foreach ($otherworkerrangs as $otherworkerrang)
                                {
                                    const ok = "{{ $otherworkerrang->key }}";
                                    const ov = response[ok] !== undefined ? response[ok] :
                                        "{{ $otherworkerrang->value }}";
                                    $('#' + ok).val(ov);
                                }
                            @endforeach
                        },
                        error: function() {
                            alert('Something went wrong while fetching rates.');
                        }
                    });
                }
            });
        });
    </script>
@endsection
