<?php

use App\Models\Dimond;
?>
@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Diamond Process Flow</h4>

            <!-- <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                    <li class="breadcrumb-item active">Party Diamond Transfer</li>
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

                <form action="{{ route('admin.diamondprocessflowpdf') }}" name="dimondflowForm" method="get">
                    @csrf
                    <div data-repeater-list="group-a">
                        <div data-repeater-item class="row">

                            <div class="mb-3 col-lg-2">
                                <label for="barcode">Barcode:</label>
                                <input type="text" name="barcode" class="form-control"
                                    id="barcode" placeholder="Enter barcode"
                                    value="<?= isset(request()->barcode) ? request()->barcode : '' ?>">
                                @if ($errors->has('barcode'))
                                <div class="error text-danger">{{ $errors->first('barcode') }}</div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-3">
                                <label for="party_id">Party Name</label>
                                <select name="party_id" id="party_id" class="form-select">
                                    <option value="">Select party</option>
                                    <!-- <option value="All" {{ request()->party_id == 'All' ? 'selected' : '' }}>ALL
                                    </option> -->
                                    @foreach ($partyLists as $partyList)
                                    <option value="{{ $partyList->id }}"
                                        {{ request()->party_id == $partyList->id ? 'selected' : '' }}>
                                        {{ $partyList->fname }}&nbsp;&nbsp;{{ $partyList->lname }}
                                    </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('party_id'))
                                <div class="error text-danger">{{ $errors->first('party_id') }}</div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label for="start_date">Start Date:</label>
                                <input type="date" name="start_date" class="form-control"
                                    id="start_date"
                                    value="<?= isset(request()->start_date) ? request()->start_date : '' ?>">
                                @if ($errors->has('start_date'))
                                <div class="error text-danger">{{ $errors->first('start_date') }}</div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label for="end_date">End Date:</label>
                                <input type="date" name="end_date" class="form-control"
                                    id="end_date" value="<?= isset(request()->end_date) ? request()->end_date : '' ?>">
                                @if ($errors->has('end_date'))
                                <div class="error text-danger">{{ $errors->first('end_date') }}</div>
                                @endif
                            </div>

                            <div class="col-lg-1 align-self-center">
                                <div class="d-flex gap-2">
                                    <input type="submit" class="btn btn-success w-md" value="Show" />
                                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/diamond_process_flow') }}">Back</a>
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
<style>
    table {
        width: 100%;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        margin-top: 40px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 12px;
        vertical-align: top;
    }

    th {
        background-color: #f2f2f2;
        text-align: left;
    }

    td div {
        margin-bottom: 6px;
    }

    .label {
        font-weight: bold;
    }

    .process_data {
        display: flex;
        gap: 15px;
        overflow: auto;
    }

    .process_data-item {
        min-width: 30%;
    }

    .stone-id {
        width: 200px;
    }

    @media(max-width: 991px) {
        .process_data-item {
            min-width: 50%;
        }
    }

    @media(max-width: 767px) {
        .process_data-item {
            min-width: 75%;
        }
    }

    @media(max-width: 543px) {
        .process_data-item {
            min-width: 85%;
        }
        .stone-id {
            width: 50%;
        }
    }
    
    @media(max-width: 400px) {
        .process_data, .process {
            width: 50%;
        }
    }

    .text-orang {
        color: orange;
    }
</style>
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                @foreach($data as $item)
                <table>
                    <tr>
                        <th>Stone ID</th>
                        <th class="process">Process</th>
                    </tr>
                    <tr>
                        <td class="stone-id">
                            <div><span class="label">ID (Diamond):</span> {{ $item['diamond']->dimond_name }}</div>
                            <div><span class="label">Date:</span> {{ \Carbon\Carbon::parse($item['diamond']->created_at)->format('d-m-Y')}}</div>
                            <div><span class="label">Party Name:</span> {{ $item['diamond']->parties->fname }}</div>
                            <div><span class="label">R Weight:</span> {{ $item['diamond']->weight }}</div>
                            <div><span class="label">C Weight:</span> {{ $item['diamond']->required_weight }}</div>
                        </td>
                        <td class="process_data">
                            @foreach($item['processes'] as $da)
                            <div class="process_data-item">
                                <div class="@if($da->return_weight == '') text-orang @endif"><span class="label">Process:</span> {{ $da->designation }}</div>
                                <div><span class="label">Name (Worker):</span> {{ $da->worker_name }}</div>
                                <div><span class="label">I Date:</span> {{ \Carbon\Carbon::parse($da->issue_date)->format('d-m-Y')}}</div>
                                <div><span class="label">R Date:</span> {{ \Carbon\Carbon::parse($da->return_date)->format('d-m-Y')}}</div>
                                <div><span class="label">I Weight:</span> {{ $da->issue_weight }}</div>
                                <div><span class="label">R Weight:</span> {{ $da->return_weight }}</div>
                            </div>
                            @endforeach
                        </td>
                    </tr>
                </table>
                <br>
                @endforeach
            </div>
        </div>
    </div>
</div>
@elseif(request()->_token != '')
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

@section('script')
@endsection