@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Worker Barcode</h4>

            <!-- <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                        <li class="breadcrumb-item active">Worker Barcode</li>
                    </ol>
                </div> -->

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">
                    Generate Worker Barcode
                </h4>

                @if (session()->has('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>
                    {{ session()->get('message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                {!! Form::open([
                'method' => 'POST',
                'action' => 'AdminWorkerBarcodeController@store',
                'files' => true,
                'class' => 'form-horizontal',
                'name' => 'workerbarcodeform',
                ]) !!}
                @csrf

                <div class="row">
                    <div class="mb-3">
                        <label for="category">Worker List</label>
                        <select name="worker_id" id="worker_id" class="form-select" required>
                            <option value="">Select worker</option>
                            @foreach ($workers as $worker)
                            <option value="{{ $worker->id }}">{{ $worker->fname }} {{ $worker->lname }}
                            </option>
                            @endforeach
                        </select>
                        @if ($errors->has('worker_id'))
                        <div class="error text-danger">{{ $errors->first('worker_id') }}</div>
                        @endif
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary w-md">Submit</button>
                    </div>

                </div>
                </form>

            </div>
        </div>
    </div> <!-- end col -->

    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Worker Barcode List</h4>

                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <table id="workerbarcodelist" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Print</th>
                            <th>Worker Name</th>
                            <th>Barcode</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($barcodeLists as $barcodeList)
                        <tr>
                            <td>
                                <a href="{{ route('admin.worker-barcode.edit', $barcodeList->id) }}"
                                    class="btn btn-outline-primary waves-effect waves-light"><i
                                        class="fa fa-edit"></i></a>
                                <a href="{{ route('admin.worker-barcode.destroy', $barcodeList->id) }}"
                                    onclick="return confirm('Sure ! You want to delete ?');"
                                    class="btn btn-outline-danger waves-effect waves-light"><i
                                        class="fa fa-trash"></i></a>
                            </td>
                            <td>
                                <a href="/admin/print-worker-barcode/{{ $barcodeList->id }}" target="_blank"
                                    class="btn btn-outline-info waves-effect waves-light">Print</a>
                            </td>
                            <td>{{ $barcodeList->worker->fname }}&nbsp;{{ $barcodeList->worker->lname }}</td>
                            <td>{{ $barcodeList->barcode }}</td>
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
<script>
    $(function() {
        $("form[name='workerbarcodeform']").validate({
            rules: {
                worker_id: {
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