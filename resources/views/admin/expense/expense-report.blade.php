@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Expense Report</h4>

            <!-- <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Expense Report</li>
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

                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-block-helper me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form action="{{ route('admin.report.index') }}" method="GET" class="repeater">
                    @csrf
                    <div data-repeater-list="group-a">
                        <div data-repeater-item class="row">
                            <div class="mb-3 col-lg-2">
                                <label for="start_date">Start Date</label>
                                <input type="date" name="start_date" class="form-control"
                                    id="start_date" value="{{ old('start_date') }}" required>
                                @if ($errors->has('start_date'))
                                <div class="error text-danger">{{ $errors->first('start_date') }}</div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label for="end_date">End Date</label>
                                <input type="date" name="end_date" class="form-control"
                                    id="end_date" value="{{ old('end_date') }}" required>
                                @if ($errors->has('end_date'))
                                <div class="error text-danger">{{ $errors->first('end_date') }}</div>
                                @endif
                            </div>

                            <!-- <div class="mb-3 col-lg-2">
                                <label for="action">Select Action:</label>
                                <select class="form-select" name="action" id="action" required>
                                    <option value="view">View</option>
                                    <option value="download">Download</option>
                                </select>
                            </div> -->

                            <div class="col-lg-1 align-self-center">
                                <div class="d-flex gap-2">
                                    <input type="submit" class="btn btn-success w-md" value="Show" />
                                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/report') }}">Back</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- end row -->

@if (count($data) > 0)
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <form action="{{ route('generate-pdf') }}" method="POST">
                    @csrf

                    <input type="hidden" name="action" value="download">
                    <input type="hidden" name="start_date" value="{{request()->start_date}}">
                    <input type="hidden" name="end_date" value="{{request()->end_date}}">

                    <input type="submit" class="btn btn-success mt-1 mt-lg-0 mb-2" value="Download" />

                </form>

                <div class="table-responsive">
                    <table class="table align-middle table-nowrap mb-0" id="diamondprintTable">
                        <thead class="table-light">
                            <tr>
                                <th>Sr.</th>
                                <th>Title</th>
                                <th>Date</th>
                                <th width="20%">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->date)->format('d-m-Y') }}</td>
                                <td>{{ $item->amount }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- end table-responsive -->
            </div>
        </div>
    </div>
</div>
@elseif(request()->start_date != '')
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

@endsection