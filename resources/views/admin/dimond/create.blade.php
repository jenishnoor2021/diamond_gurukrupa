@extends('layouts.admin')
@section('style')
<style>
    @media (min-width: 768px) {
        .w-md {
            min-width: 110px;
        }
    }
</style>
@endsection
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">ADD Diamond</h4>
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
                'action' => 'AdminDimondController@store',
                'files' => true,
                'class' => 'form-horizontal',
                'name' => 'adddimondform',
                'id' => 'adddimondform',
                ]) !!}
                @csrf

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="parties_id">Party</label>
                            <select name="parties_id" id="parties_id" class="form-select" required>
                                <option value="">Select Party</option>
                                @foreach ($partys as $party)
                                <option value="{{ $party->id }}">{{ $party->party_code }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('parties_id'))
                            <div class="error text-danger">{{ $errors->first('parties_id') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div id="section-container">
                    <div class="section" style="padding-top:0px; padding-bottom:0px;">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="janger_no">Janger no</label>
                                    <input type="text" name="janger_no[]" class="form-control" id="janger_no"
                                        placeholder="Enter Janger no" value="" required>
                                    @if ($errors->has('janger_no'))
                                    <div class="error text-danger">{{ $errors->first('janger_no') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="dimond_name">Stone Id</label>
                                    <input type="text" name="dimond_name[]" class="form-control dimond_name" id="dimond_name" placeholder="Enter Stone Id" readonly>
                                    @if ($errors->has('dimond_name'))
                                    <div class="error text-danger">{{ $errors->first('dimond_name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="weight">Row Weight</label>
                                    <input type="text" name="weight[]" class="form-control" id="weight" placeholder="00.00"
                                        oninput="formatWeight(this);" value="" required>
                                    @if ($errors->has('weight'))
                                    <div class="error text-danger">{{ $errors->first('weight') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="required_weight">Polished Weight</label>
                                    <input type="text" name="required_weight[]" class="form-control" id="required_weight"
                                        placeholder="00.00" value="" oninput="formatWeight(this);"
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
                                    <select name="shape[]" id="shape" class="form-select" required>
                                        <!-- <option value="">Select shape</option> -->
                                        @foreach ($shapes as $shape)
                                        <option value="{{$shape->shape_type}}">{{$shape->shape_type}}</option>
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
                                    <select name="clarity[]" id="clarity" class="form-select" required>
                                        <!-- <option value="">Select clarity</option> -->
                                        @foreach ($claritys as $clarity)
                                        <option value="{{$clarity->name}}">{{$clarity->name}}</option>
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
                                    <select name="color[]" id="color" class="form-select" required>
                                        <!-- <option value="">Select color</option> -->
                                        @foreach ($colors as $color)
                                        <option value="{{$color->c_name}}">{{$color->c_name}}</option>
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
                                    <select name="cut[]" id="cut" class="form-select" required>
                                        @foreach ($cuts as $cut)
                                        <option value="{{$cut->name}}">{{$cut->name}}</option>
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
                                    <select name="polish[]" id="polish" class="form-select" required>
                                        @foreach ($polishes as $polish)
                                        <option value="{{$polish->name}}">{{$polish->name}}</option>
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
                                    <select name="symmetry[]" id="symmetry" class="form-select" required>
                                        @foreach ($symmetrys as $symmetry)
                                        <option value="{{$symmetry->name}}">{{$symmetry->name}}</option>
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
                                    <input type="date" name="created_at" class="form-control" id="created_at" placeholder="00.00" value="" required>
                                    @if($errors->has('created_at'))
                                    <div class="error text-danger">{{ $errors->first('created_at') }}</div>
                                    @endif
                                </div>
                            </div> -->
                        </div>

                        <button type="button" class="btn btn-danger remove-section" style="display:none;">Remove</button>
                        <hr style="border:1px solid #000;">
                    </div>
                </div>

                <button type="button" class="btn btn-success mb-3" id="add-section">Add</button>

                <input type="hidden" name="action_type" id="action_type" value="save">

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-primary w-md">Save</button>
                    <button type="button" class="btn btn-success w-md" id="saveAndPrintBtn">Save & Print</button>
                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/dimond') }}">Back</a>
                </div>
                {!! Form::close() !!}
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
<script>
    let seriesYear = "{{ $year }}";
    let seriesMonth = "{{ $month }}";
    let seriesNo = <?= $startSeries ?>;
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let sectionContainer = document.getElementById("section-container");
        let addSectionBtn = document.getElementById("add-section");

        function generateStoneId(no) {
            return `GKD${seriesYear}-${seriesMonth}-${no}`;
        }

        function setStoneIds() {
            let rows = document.querySelectorAll(".section");
            let current = seriesNo;

            rows.forEach(row => {
                row.querySelector(".dimond_name").value = generateStoneId(current);
                current++;
            });
        }

        function updateRemoveButtons() {
            let removeButtons = document.querySelectorAll(".remove-section");
            removeButtons.forEach(btn => btn.style.display = removeButtons.length > 1 ? "block" : "none");
        }

        setStoneIds();
        updateRemoveButtons();

        addSectionBtn.addEventListener("click", function() {
            let originalSection = document.querySelector(".section");
            let newSection = originalSection.cloneNode(true);

            newSection.querySelectorAll("input, select").forEach(input => {
                if (input.tagName === "SELECT") {
                    input.selectedIndex = 0;
                } else {
                    input.value = "";
                }
            });

            sectionContainer.appendChild(newSection);
            setStoneIds();
            updateRemoveButtons();
        });

        sectionContainer.addEventListener("click", function(event) {
            if (event.target.classList.contains("remove-section")) {
                event.target.parentElement.remove();
                setStoneIds();
                updateRemoveButtons();
            }
        });
    });
</script>

<script>
    document.getElementById('saveAndPrintBtn').addEventListener('click', function() {
        document.getElementById('action_type').value = 'save_and_print';
        document.getElementById('adddimondform').submit();
    });
</script>
@endsection