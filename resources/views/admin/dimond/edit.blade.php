@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Diamond</h4>

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit</h4>

                {!! Form::model($dimond, ['method'=>'PATCH', 'action'=> ['AdminDimondController@update', $dimond->id],'files'=>true,'class'=>'form-horizontal', 'name'=>'editdimondform']) !!}
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="parties_id">Party</label>
                            <select name="parties_id" id="parties_id" class="form-select" required>
                                <option value="">Select Party</option>
                                @foreach ($partys as $party)
                                <option value="{{$party->id}}" {{$party->id == $dimond->parties_id ? 'selected' : ''}}>{{$party->party_code}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('parties_id'))
                            <div class="error text-danger">{{ $errors->first('parties_id') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="janger_no">Janger no</label>
                            <input type="text" name="janger_no" class="form-control" id="janger_no"
                                placeholder="Enter Janger no" value="{{$dimond->janger_no}}" required>
                            @if ($errors->has('janger_no'))
                            <div class="error text-danger">{{ $errors->first('janger_no') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="dimond_name">Stone Id</label>
                            <input type="text" name="dimond_name" class="form-control" id="dimond_name"
                                placeholder="Enter Stone Id" value="{{$dimond->dimond_name}}" required readonly>
                            @if ($errors->has('dimond_name'))
                            <div class="error text-danger">{{ $errors->first('dimond_name') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="weight">Row Weight</label>
                            <input type="text" name="weight" class="form-control" id="weight" placeholder="00.00"
                                oninput="formatWeight(this);" value="{{$dimond->weight}}" required>
                            @if ($errors->has('weight'))
                            <div class="error text-danger">{{ $errors->first('weight') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="required_weight">Polished Weight</label>
                            <input type="text" name="required_weight" class="form-control" id="required_weight"
                                placeholder="00.00" value="{{$dimond->required_weight}}" oninput="formatWeight(this);"
                                required>
                            @if ($errors->has('required_weight'))
                            <div class="error text-danger">{{ $errors->first('required_weight') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="shape">Shape</label>
                            <select name="shape" id="shape" class="form-select" required>
                                <!-- <option value="">Select shape</option> -->
                                @foreach ($shapes as $shape)
                                <option value="{{$shape->shape_type}}" {{$dimond->shape == $shape->shape_type ? 'selected' : ''}}>{{$shape->shape_type}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('shape'))
                            <div class="error text-danger">{{ $errors->first('shape') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="clarity">Clarity</label>
                            <select name="clarity" id="clarity" class="form-select" required>
                                <!-- <option value="">Select clarity</option> -->
                                @foreach ($claritys as $clarity)
                                <option value="{{$clarity->name}}" {{$dimond->clarity == $clarity->name ? 'selected' : ''}}>{{$clarity->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('clarity'))
                            <div class="error text-danger">{{ $errors->first('clarity') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="color">Color</label>
                            <select name="color" id="color" class="form-select" required>
                                <!-- <option value="">Select color</option> -->
                                @foreach ($colors as $color)
                                <option value="{{$color->c_name}}" {{$dimond->color == $color->c_name ? 'selected' : ''}}>{{$color->c_name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('color'))
                            <div class="error text-danger">{{ $errors->first('color') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="cut">Cut</label>
                            <select name="cut" id="cut" class="form-select" required>
                                @foreach ($cuts as $cut)
                                <option value="{{$cut->name}}" {{$dimond->cut == $cut->name ? 'selected' : ''}}>{{$cut->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('cut'))
                            <div class="error text-danger">{{ $errors->first('cut') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="polish">Polish</label>
                            <select name="polish" id="polish" class="form-select" required>
                                @foreach ($polishes as $polish)
                                <option value="{{$polish->name}}" {{$dimond->polish == $polish->name ? 'selected' : ''}}>{{$polish->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('polish'))
                            <div class="error text-danger">{{ $errors->first('polish') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="symmetry">Symmetry</label>
                            <select name="symmetry" id="symmetry" class="form-select" required>
                                @foreach ($symmetrys as $symmetry)
                                <option value="{{$symmetry->name}}" {{$dimond->symmetry == $symmetry->name ? 'selected' : ''}}>{{$symmetry->name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('symmetry'))
                            <div class="error text-danger">{{ $errors->first('symmetry') }}</div>
                            @endif
                        </div>
                    </div>
                    <!-- <div class="col-md-4">
                        <div class="mb-3">
                            <label for="created_at">Created Date</label>
                            <input type="date" name="created_at" class="form-control" id="created_at" placeholder="00.00" value="{{ old('created_at', $dimond->created_at ? \Carbon\Carbon::parse($dimond->created_at)->format('Y-m-d') : '') }}" required>
                            @if($errors->has('created_at'))
                            <div class="error text-danger">{{ $errors->first('created_at') }}</div>
                            @endif
                        </div>
                    </div> -->
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-md">Update</button>
                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/dimond') }}">Back</a>
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

        $("form[name='adddimondform']").validate({
            rules: {
                parties_id: {
                    required: true,
                },
                dimond_name: {
                    required: true,
                },
                janger_no: {
                    required: true,
                },
                shape: {
                    required: true,
                },
                weight: {
                    required: true,
                },
                clarity: {
                    required: true,
                },
                color: {
                    required: true,
                },
                cut: {
                    required: true,
                },
                polish: {
                    required: true,
                },
                symmetry: {
                    required: true,
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
<script>
    function formatWeight(input) {
        // Remove any non-numeric characters
        var cleanedValue = input.value.replace(/[^0-9.]/g, '');

        // Ensure valid pattern: either empty, '0.00', or '00.00'
        var match = cleanedValue.match(/^(\d{0,2}(\.\d{0,2})?)?$/);

        // Update the input value with the formatted result
        input.value = match ? match[1] || '' : '';
    }
</script>
@endsection