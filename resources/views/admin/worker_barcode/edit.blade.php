@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Worker Barcode</h4>

            <!-- <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Edit Worker Barcode</li>
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
                <h4 class="card-title mb-4">Edit</h4>

                {!! Form::model($workerBarcode, [
                'method' => 'PATCH',
                'action' => ['AdminWorkerBarcodeController@update', $workerBarcode->id],
                'files' => true,
                'class' => 'form-horizontal',
                'name' => 'editbarcodeform',
                ]) !!}
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="worker_id">Worker name</label>
                            <input type="text" name="worker_id" class="form-control" id="worker_id"
                                value="{{ $workerBarcode->worker_id }}" readonly>
                            @if ($errors->has('worker_id'))
                            <div class="error text-danger">{{ $errors->first('worker_id') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="name">Barcode</label>
                            <input type="text" name="barcode" class="form-control" id="barcode"
                                value="{{ $workerBarcode->barcode }}" required pattern="\d{10}" maxlength="10"
                                minlength="10" title="Barcode must be exactly 10 digits">
                            @if ($errors->has('barcode'))
                            <div class="error text-danger">{{ $errors->first('barcode') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-md">Update</button>
                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/worker-barcode') }}">Back</a>
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

        $("form[name='editbarcodeform']").validate({
            rules: {
                worker_id: {
                    required: true,
                },
                barcode: {
                    required: true,
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });

    document.getElementById('barcode').addEventListener('input', function(e) {
        const value = e.target.value;
        if (value.length > 10) {
            e.target.value = value.slice(0, 10); // Limit to 10 digits
        }
    });
</script>
@endsection