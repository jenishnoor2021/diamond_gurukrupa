<?php

use Carbon\Carbon;
?>
@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Party Bill</h4>

            <!-- <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Party Bill</li>
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

                <form action="{{ route('party.bill') }}" id="billform" method="post">
                    @csrf
                    <div data-repeater-list="group-a">
                        <div data-repeater-item class="row">
                            <div class="mb-3 col-lg-3">
                                <label for="party_id">Party Name</label>
                                <select name="party_id" id="party_id" class="form-select" required>
                                    <option value="">Select party</option>
                                    @foreach ($partyLists as $partyList)
                                    <option value="{{ $partyList->id }}">
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
                                <input type="date" name="start_date" class="form-control" id="start_date"
                                    value="{{ request()->start_date }}">
                                @if ($errors->has('start_date'))
                                <div class="error text-danger">{{ $errors->first('start_date') }}</div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label for="end_date">End Date:</label>
                                <input type="date" name="end_date" class="form-control" id="end_date"
                                    value="{{ request()->end_date }}">
                                @if ($errors->has('end_date'))
                                <div class="error text-danger">{{ $errors->first('end_date') }}</div>
                                @endif
                            </div>

                            <div class="mb-3 col-lg-2">
                                <label for="add_gst">GST include:</label>
                                <select name="add_gst" id="add_gst" class="form-select" required>
                                    <option value="">Select type</option>
                                    <option value="gst">GST</option>
                                    <option value="notgst">NOT GST</option>
                                </select>
                                @if ($errors->has('add_gst'))
                                <div class="error text-danger">{{ $errors->first('add_gst') }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="gap-2">
                            <button type="button" id="button1" class="btn btn-success mt-3 w-md">Generate
                                EXcel</button>
                            <button type="button" id="button2" class="btn btn-info mt-3 w-md">Generate
                                PDF</button>
                            <a class="btn btn-light mt-3 w-md" href="/admin/party-report">Clear</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<!-- end row -->
@endsection

@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById('billform');
        var button1 = document.getElementById('button1');
        var button2 = document.getElementById('button2');

        button1.addEventListener('click', function() {
            form.action = "{{ route('party.bill.excel') }}";
            // Submit the form
            form.submit();
        });

        button2.addEventListener('click', function() {
            // Change the form action for button 2
            form.action = "{{ route('party.bill') }}";
            // Submit the form
            form.submit();
        });
    });
</script>
@endsection