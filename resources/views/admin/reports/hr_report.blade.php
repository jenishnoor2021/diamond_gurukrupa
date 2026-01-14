<?php

use App\Models\Process;
?>

@extends('layouts.admin')
@section('content')

<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0 font-size-18">Export Delievery Slip</h4>

      <!-- <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
          <li class="breadcrumb-item active">Export Delievery Slip</li>
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
        <form action="{{ route('admin.hr.export') }}" method="GET" class="repeater">
          @csrf
          <div data-repeater-list="group-a">
            <div data-repeater-item class="row">
              <div class="mb-3 col-lg-2">
                <label for="party_id">Party Name</label>
                <select class="form-select" name="party_id" id="validationCustom03" required="">
                  <option value="{{request()->party_id}}">Select party</option>
                  @foreach($partyLists as $partyList)
                  <option value="{{$partyList->id}}" {{ request()->party_id == $partyList->id ? 'selected' : '' }}>{{$partyList->fname}}&nbsp;&nbsp;{{$partyList->lname}}</option>
                  @endforeach
                </select>
              </div>

              <div class="mb-3 col-lg-2">
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request()->start_date }}" placeholder="Enter date" required />
              </div>

              <div class="mb-3 col-lg-2">
                <label for="end_date">End Date</label>
                <input type="date" name="end_date" id="end_date" class="form-control" placeholder="Enter date" value="{{ request()->end_date }}" required />
              </div>

              <div class="col-lg-1 align-self-center">
                <div class="d-flex gap-2">
                  <input type="submit" class="btn btn-success mt-3 mt-lg-0" value="Show" />
                  <a class="btn btn-light mt-3 mt-lg-0" href="{{ URL::to('/admin/hr-export') }}">Back</a>
                </div>
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@if (count($data) > 0)
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">

        <form action="{{ route('admin.hr.exportpdf') }}" method="GET">
          @csrf

          <input type="hidden" name="party_id" value="{{request()->party_id}}">
          <input type="hidden" name="start_date" value="{{request()->start_date}}">
          <input type="hidden" name="end_date" value="{{request()->end_date}}">

          <input type="submit" class="btn btn-success mt-1 mt-lg-0 mb-2" value="Export" />

        </form>

        <div class="table-responsive">
          <table class="table align-middle table-nowrap mb-0" id="diamondprintTable">
            <thead class="table-light">
              <tr>
                <th>Sr.</th>
                <th>Stone Id</th>
                <th>RW</th>
                <th>PW</th>
                <th>SHP</th>
                <th>CL</th>
                <th>PRT</th>
                <th>CUT</th>
                <th>PL</th>
                <th>STN</th>
              </tr>
            </thead>
            <tbody>
              @foreach($data as $key=>$da)
              <?php
              $process = Process::where(['designation' => 'Grading', 'dimonds_id' => $da->id])->first();
              ?>
              <tr>
                <td>{{$key+1}}</td>
                <td>{{$da->dimond_name}}</td>
                <td>{{$da->weight}}</td>
                <td>{{isset($process->return_weight) ?$process->return_weight: ''}}</td>
                <td>{{isset($process->r_shape) ?$process->r_shape: ''}}</td>
                <td>{{isset($process->r_clarity) ?$process->r_clarity: ''}}</td>
                <td>{{isset($process->r_color) ?$process->r_color: ''}}</td>
                <td>{{isset($process->r_cut) ?$process->r_cut: ''}}</td>
                <td>{{isset($process->r_polish) ?$process->r_polish: ''}}</td>
                <td>{{isset($process->r_symmetry) ?$process->r_symmetry: ''}}</td>
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
@elseif(request()->party_id != '')
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