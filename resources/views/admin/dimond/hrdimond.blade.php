<?php

use App\Models\Dimond;
?>

@extends('layouts.admin')
@section('content')

<!-- start page title -->
<div class="row">
  <div class="col-12">
    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
      <h4 class="mb-sm-0 font-size-18">DHYANI IMPEX List</h4>

      <!-- <div class="page-title-right">
        <ol class="breadcrumb m-0">
          <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
          <li class="breadcrumb-item active">DIAMOND</li>
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
        <a href="/admin/hr-export" class="btn btn-success">Export Slip</a>

        <ul class="nav nav-tabs nav-tabs-custom mt-3" role="tablist">
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#transactions-repair-tab" role="tab">Repair ({{count($repairDimonds)}})</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#transactions-delivery-tab" role="tab">Delivered ({{count($deliveredDimonds)}})</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" data-bs-toggle="tab" href="#transactions-complete-tab" role="tab">Completed ({{count($completedDimonds)}})</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#transactions-processing-tab" role="tab">Processing ({{count($processingDimonds)}})</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-bs-toggle="tab" href="#transactions-pending-tab" role="tab">Pending ({{count($pendingDimonds)}})</a>
          </li>
        </ul>

        <div class="tab-content mt-4">
          <div class="tab-pane" id="transactions-repair-tab" role="tabpanel">
            <div class="table-responsive" data-simplebar style="max-height: 330px;">
              <table class="table table-hover datatable dt-responsive nowrap">
                <thead>
                  <tr>
                    <th>Show</th>
                    <th>Party Name</th>
                    <th>Dimond Name</th>
                    <th>Weight</th>
                    <th>Barcode</th>
                    <th>Shap</th>
                    <th>clarity</th>
                    <th>color</th>
                    <th>cut</th>
                    <th>polish</th>
                    <th>symmetry</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($repairDimonds as $repairdimond)
                  <tr>
                    <?php
                    $dimond = Dimond::where('id', $repairdimond->dimonds_id)->first();
                    ?>
                    <td>
                      <a href="{{route('admin.dimond.show', $dimond->barcode_number)}}"><i class="fa fa-eye" style="color:white;font-size:15px;background-color:rgba(255, 255, 255, 0.25);padding:8px;"></i></a>
                    </td>
                    <td>{{$dimond->parties->party_code}}</td>
                    <td>{{$dimond->dimond_name}}</td>
                    <td>{{$dimond->weight}}</td>
                    <td>{!! $dimond->barcode_number !!}</td>
                    <td>{{$dimond->shape}}</td>
                    <td>{{$dimond->clarity}}</td>
                    <td>{{$dimond->color}}</td>
                    <td>{{$dimond->cut}}</td>
                    <td>{{$dimond->polish}}</td>
                    <td>{{$dimond->symmetry}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane" id="transactions-delivery-tab" role="tabpanel">
            <div class="table-responsive" data-simplebar style="max-height: 330px;">
              <table class="table table-hover datatable dt-responsive nowrap">
                <thead>
                  <tr>
                    <th>Repair</th>
                    <!-- <th>Slip</th> -->
                    <th>Party Name</th>
                    <th>Date</th>
                    <th>Dimond Name</th>
                    <th>Weight</th>
                    <th>Barcode</th>
                    <th>Shap</th>
                    <th>clarity</th>
                    <th>color</th>
                    <th>cut</th>
                    <th>polish</th>
                    <th>symmetry</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($deliveredDimonds as $dimond)
                  <tr>
                    <td><a href="/admin/repair/{{$dimond->id}}" class="btn btn-info">Repair</a></td>
                    <!-- <td><a href="/admin/print-slipe/{{$dimond->id}}" class="btn btn-primary" target="_blank">Print</a></td> -->
                    <td>{{$dimond->parties->party_code}}</td>
                    <td>{{ \Carbon\Carbon::parse($dimond->delevery_date)->format('d-m-Y') }}</td>
                    <td>{{$dimond->dimond_name}}</td>
                    <td>{{$dimond->weight}}</td>
                    <td>{!! $dimond->barcode_number !!}</td>
                    <td>{{$dimond->shape}}</td>
                    <td>{{$dimond->clarity}}</td>
                    <td>{{$dimond->color}}</td>
                    <td>{{$dimond->cut}}</td>
                    <td>{{$dimond->polish}}</td>
                    <td>{{$dimond->symmetry}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane active" id="transactions-complete-tab" role="tabpanel">
            <div class="table-responsive" data-simplebar style="max-height: 330px;">
              <table class="table align-middle table-nowrap">
                <thead>
                  <tr>
                    <th>Slip</th>
                    <th>Party Name</th>
                    <th>Dimond Name</th>
                    <th>Weight</th>
                    <th>Barcode</th>
                    <th>Shap</th>
                    <th>clarity</th>
                    <th>color</th>
                    <th>cut</th>
                    <th>polish</th>
                    <th>symmetry</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($completedDimonds as $dimond)
                  <tr>
                    <td><a href="/admin/print-slipe/{{$dimond->id}}" class="btn btn-primary" target="_blank">Print</a></td>
                    <td>{{$dimond->parties->party_code}}</td>
                    <td>{{$dimond->dimond_name}}</td>
                    <td>{{$dimond->weight}}</td>
                    <td>{!! $dimond->barcode_number !!}</td>
                    <td>{{$dimond->shape}}</td>
                    <td>{{$dimond->clarity}}</td>
                    <td>{{$dimond->color}}</td>
                    <td>{{$dimond->cut}}</td>
                    <td>{{$dimond->polish}}</td>
                    <td>{{$dimond->symmetry}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane" id="transactions-processing-tab" role="tabpanel">
            <div class="table-responsive" data-simplebar style="max-height: 330px;">
              <table class="table align-middle table-nowrap">
                <thead>
                  <tr>
                    <th>Party Name</th>
                    <th>Dimond Name</th>
                    <th>Weight</th>
                    <th>Barcode</th>
                    <th>Shap</th>
                    <th>clarity</th>
                    <th>color</th>
                    <th>cut</th>
                    <th>polish</th>
                    <th>symmetry</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($processingDimonds as $dimond)
                  <tr>
                    <td>{{$dimond->parties->party_code}}</td>
                    <td>{{$dimond->dimond_name}}</td>
                    <td>{{$dimond->weight}}</td>
                    <td>{!! $dimond->barcode_number !!}</td>
                    <td>{{$dimond->shape}}</td>
                    <td>{{$dimond->clarity}}</td>
                    <td>{{$dimond->color}}</td>
                    <td>{{$dimond->cut}}</td>
                    <td>{{$dimond->polish}}</td>
                    <td>{{$dimond->symmetry}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <div class="tab-pane" id="transactions-pending-tab" role="tabpanel">
            <div class="table-responsive" data-simplebar style="max-height: 330px;">
              <table class="table align-middle table-nowrap">
                <thead>
                  <tr>
                    <th>Date</th>
                    <th>Party Name</th>
                    <th>Dimond Name</th>
                    <th>Weight</th>
                    <th>Barcode</th>
                    <th>Shap</th>
                    <th>clarity</th>
                    <th>color</th>
                    <th>cut</th>
                    <th>polish</th>
                    <th>symmetry</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($pendingDimonds as $dimond)
                  <tr>
                    <td>{{ \Carbon\Carbon::parse($dimond->created_at)->format('d-m-Y') }}</td>
                    <td>{{$dimond->parties->party_code}}</td>
                    <td>{{$dimond->dimond_name}}</td>
                    <td>{{$dimond->weight}}</td>
                    <td>{!! $dimond->barcode_number !!}</td>
                    <td>{{$dimond->shape}}</td>
                    <td>{{$dimond->clarity}}</td>
                    <td>{{$dimond->color}}</td>
                    <td>{{$dimond->cut}}</td>
                    <td>{{$dimond->polish}}</td>
                    <td>{{$dimond->symmetry}}</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

@endsection