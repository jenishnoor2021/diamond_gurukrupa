@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">ADD Invoice</h4>

            <!-- <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">ADD Invoice</li>
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
                'action' => 'AdminInvoiceController@store',
                'files' => true,
                'class' => 'form-horizontal',
                'name' => 'addinvoiceform',
                ]) !!}
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="parties_id">Party Name</label>
                            <select name="parties_id" id="parties_id" class="form-select" required>
                                <option value="">Select Party</option>
                                @foreach ($partyes as $party)
                                <option value="{{ $party->id }}">{{ $party->fname }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('parties_id'))
                            <div class="error text-danger">{{ $errors->first('parties_id') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="companies_id">Company Name</label>
                            <select name="companies_id" id="companies_id" class="form-select" required>
                                <option value="">Select Company</option>
                                @foreach ($companyes as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('companies_id'))
                            <div class="error text-danger">{{ $errors->first('companies_id') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="invoice_no">Invoice number</label>
                            <input type="text" name="invoice_no" class="form-control"
                                id="invoice_no" placeholder="Enter Invoice number" value="{{ old('invoice_no') }}"
                                required>
                            @if ($errors->has('invoice_no'))
                            <div class="error text-danger">{{ $errors->first('invoice_no') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="invoice_date">Invoice Date</label>
                            <input type="date" name="invoice_date" class="form-control"
                                id="invoice_date" placeholder="Enter invoice date" value="{{ old('invoice_date') }}"
                                required>
                            @if ($errors->has('invoice_date'))
                            <div class="error text-danger">{{ $errors->first('invoice_date') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="place_to_supply">Place To Supply</label>
                            <input type="text" name="place_to_supply" class="form-control"
                                id="place_to_supply" placeholder="Enter Place To Supply"
                                value="{{ old('place_to_supply') }}" required>
                            @if ($errors->has('place_to_supply'))
                            <div class="error text-danger">{{ $errors->first('place_to_supply') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="due_date">due_date</label>
                            <input type="date" name="due_date" class="form-control"
                                id="due_date" required>
                            @if ($errors->has('due_date'))
                            <div class="error text-danger">{{ $errors->first('due_date') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-md">Submit</button>
                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/invoice') }}">Back</a>
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
        $("form[name='addinvoiceform']").validate({
            rules: {
                parties_id: {
                    required: true,
                },
                companies_id: {
                    required: true,
                },
                invoice_no: {
                    required: true,
                },
                invoice_date: {
                    required: true,
                },
                place_to_supply: {
                    required: true,
                },
                due_date: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
@endsection