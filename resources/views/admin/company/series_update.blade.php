<?php

use App\Models\Process;
?>
@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Series Update</h4>

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

                <form action="{{ route('admin.serie.update') }}" method="GET" id="seriesForm">
                    @csrf
                    <input type="hidden" name="id" value="{{$company->id}}">
                    <div data-repeater-list="group-a">
                        <div data-repeater-item class="row">
                            <div class="mb-3 col-lg-3">
                                <label for="series_year">Select Year</label>
                                <select name="series_year" id="series_year" onchange="submitSeriesForm()" class="form-select" required>
                                    @foreach(range(26, 35) as $y)
                                    <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>
                                        20{{ $y }}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('series_year'))
                                <div class="error text-danger">{{ $errors->first('series_year') }}</div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label for="series_month">Select Month:</label>
                                <select name="series_month" id="series_month" onchange="submitSeriesForm()" class="form-select" required>
                                    @foreach(range(1, 12) as $m)
                                    @php $m = str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                                    <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                        {{ $m }}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('series_year'))
                                <div class="error text-danger">{{ $errors->first('series_year') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>

                @if(!empty($sampleStoneId))
                <div class="alert alert-success mt-3">
                    <strong>Sample Stone ID:</strong> {{ $sampleStoneId }}
                </div>
                @endif

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

@section('script')
<script>
    function submitSeriesForm() {
        document.getElementById('seriesForm').submit();
    }
</script>
@endsection