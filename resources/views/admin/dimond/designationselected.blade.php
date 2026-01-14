<?php

use App\Models\Process;
?>
@extends('layouts.admin')
@section('content')

<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0 font-size-18">Designation wise Diamond</h4>

      <!-- <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
          <li class="breadcrumb-item active">Designation wise Diamond</li>
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

        <div id="right">
          <div id="menu" class="mb-3">

            <span id="menu-navi"
              class="d-sm-flex flex-wrap text-center text-sm-start justify-content-sm-between">
              <div class="d-sm-flex flex-wrap gap-1">
              </div>

              <div class="align-self-start mt-3 mt-sm-0 mb-2">
              </div>
            </span>

          </div>
        </div>

        <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
          <thead>
            <tr>
              <th>Action</th>
              <th>Dimond Name</th>
              <th>Barcode</th>
              <th>Date</th>
              <th>Current Status</th>
            </tr>
          </thead>

          <tbody>
            @foreach($dailys as $index =>$dimond)
            <tr>
              <td>
                @if($dimond->dimonds)
                <a href="{{route('admin.dimond.show', $dimond->dimonds_barcode)}}" class="btn btn-info"><i class="fa fa-eye"></i></a>
                @endif
              </td>
              <td>{{ $dimond->dimonds?->dimond_name }}</td>
              <td>{{$dimond->dimonds_barcode}}</td>
              <td>{{ \Carbon\Carbon::parse($dimond->updated_at)->format('d-m-Y') }}</td>
              <td>{{ $dimond->dimonds?->status }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>

      </div>
    </div>
  </div> <!-- end col -->
</div> <!-- end row -->

@endsection