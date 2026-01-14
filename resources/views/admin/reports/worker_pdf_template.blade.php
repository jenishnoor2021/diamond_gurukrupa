<?php

use Carbon\Carbon;
use App\Models\Dimond;
use App\Models\Process;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Worker Report</title>
  <style>
    * {
      box-sizing: border-box;
    }

    /* general styling */
    body {
      font-family: "Open Sans", sans-serif;
    }

    .column-left {
      float: left;
      width: 50%;
      padding: 5px;
      /* border-right: 1px dotted #000; */
    }

    .column-right p,
    .column-left p {
      font-size: 11px;
      line-height: normal;
    }

    .column-right h4,
    .column-left h4 {
      font-size: 12px;
      margin-top: -2px;
    }

    .column-right {
      float: left;
      width: 50%;
      padding: 5px;
    }

    /* Create four equal columns that floats next to each other */
    .column {
      /* float: left;
      width: 100%;
      padding: 10px;
      border-right: 1px dotted #000;
      height: 50%; */
      /* Should be removed. Only for demonstration */
    }

    .container {
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0 auto;
    }

    .container h1 {
      font-size: 14px;
    }

    /* Clear floats after the columns */
    .row:after {
      content: "";
      display: table;
      clear: both;
    }

    .justify-content-between {
      justify-content: space-between;
    }

    .justify-content-center {
      justify-content: center;
    }

    .justify-content-end {
      justify-content: end;
    }

    .title {
      margin-top: 5px;
    }

    .align-center {
      align-self: center;
    }

    .align-items-center {
      align-items: center;
    }

    /*table*/
    table {
      margin-top: 10px;
      /* border: 1px solid #ccc; */
      border-bottom: 1px solid #000;
      border-collapse: collapse;
      margin: 0;
      padding: 0;
      width: 100%;
      table-layout: fixed;
      font-size: 11px;
    }

    table tr {
      background-color: #fff;
      /* border: 1px solid #000; */
      padding: .45em;
    }

    thead {
      border-bottom: 1px solid #000;
      border-top: 1px solid #000;
    }

    /* .header-table {
      border: none;
    }

    .header-table td {
      text-align: center;
      padding: 10px;
    } */

    /* .header-title {
      font-size: 20px;
      font-weight: bold;
    } */

    /* .header-subtitle {
      font-size: 30px;
      font-weight: bold;
      padding-top: 0 !important;
    } */

    /* .header-address {
      font-size: 14px;
      padding-top: 0 !important;
    } */

    /* .data-table th {
      border: 1px solid #000;
      padding: 10px;
      font-size: 12px;
      text-align: center;
    } */

    .data-table td {
      /* padding: 10px; */
      font-size: 10px;
      text-align: center;
    }

    .data-table th {
      /* background-color: #f2f2f2; */
      /* font-weight: bold; */
    }

    /*table end*/
    hr {
      border-top: 1px solid #000;
    }
  </style>
</head>

<body>
  <div class="container">
    <br />
    <center>
      <h1 class="align-center title">Worker Report</h1>
      <h1 style="margin-top:-5px">{{$company->name}}</h1>
      <p style="font-size:10px;margin-top:-5px">{{$company->address}}</p>
    </center>
    <hr />
    @foreach ($worker_detail as $worker)
    <div class="row">
      <div class="column-left">
        <p><strong class="align-center title">Bill Date:</strong>{{ \Carbon\Carbon::parse(Carbon::now())->format('d-m-Y H:i:s') }}</p>
        <p><strong class="align-center title">Worker Name:</strong>{{isset($worker->fname) ?$worker->fname : '' }} {{isset($worker->lname) ?$worker->lname : '' }}</p>
        <p><strong class="align-center title">Designation:</strong>{{isset($worker->designation)?$worker->designation:''}}</p>
      </div>
      <div class="column-right">
        <p><strong class="align-center title">Phones :</strong>{{isset($worker->mobile)?$worker->mobile:''}}</p>
        <p><strong class="align-center title">Address :</strong>{{isset($worker->address)?$worker->address:''}}</p>
        <p><strong class="align-center title">Aadhar No. :</strong>{{isset($worker->aadhar_no)?$worker->aadhar_no:''}}</p>
      </div>
      <div style="clear: both;"></div>
    </div>
    <br />
    <table class="data-table table-flush table-borderless" cellspacing="1">
      <thead>
        <tr>
          <th>Sr.</th>
          <th>Dimond Name</th>
          <th>Dimond barcode</th>
          <th>Issues Date</th>
          <th>Return Date</th>
          <th>Shape</th>
          <th>clarity</th>
          <th>color</th>
          <th>cut</th>
          <th>polish</th>
          <th>symmetry</th>
          <th>Issues Weight</th>
          <th>Return Weight</th>
          <th>Amount</th>
          <th>Created date</th>
          <th>Delivery date</th>
        </tr>
      </thead>
      @php
      $sum = 0;
      $p = 1;
      @endphp
      <tbody>
        <?php
        $dimondsBarcodeArray = [];
        ?>
        @foreach($data as $key=>$da)
        @if($worker->fname == $da->worker_name)
        <?php
        $category = $_GET['category'];
        $getdimond = Dimond::where('barcode_number', $da->dimonds_barcode)->first();
        $which_diamond = $_GET['which_diamond'];
        if ($which_diamond == 'updated_at') {
          $rw = $da->return_weight;
        } else {
          $returndimond = Process::where('dimonds_barcode', $da->dimonds_barcode)->where('designation', 'Grading')->latest()->first();
          $rw = isset($returndimond->return_weight) ? $returndimond->return_weight : '';
        }
        if (isset($getdimond) && ($da->price != 0) && ($category != "Outter")) { ?>
          <tr>
            <td>{{$p}}</td>
            <td>{{ $da->dimonds->dimond_name }}</td>
            <td>{{ $da->dimonds_barcode }}</td>
            <td>{{ \Carbon\Carbon::parse($da->issue_date)->format('d-m-Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($da->return_date)->format('d-m-Y') }}</td>
            <td>{{ $getdimond->shape }}</td>
            <td>{{$getdimond->clarity}}</td>
            <td>{{$getdimond->color}}</td>
            <td>{{$getdimond->cut}}</td>
            <td>{{$getdimond->polish}}</td>
            <td>{{$getdimond->symmetry}}</td>
            <td>{{ $da->issue_weight }}</td>
            <td>{{ isset($rw) ? $rw : '' }}</td>
            <td>{{ $da->price }}</td>
            <td>{{ \Carbon\Carbon::parse($getdimond->created_at)->format('d-m-Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($getdimond->delevery_date)->format('d-m-Y') }}</td>
            @php
            $sum += ($da->price);
            $p += 1;
            @endphp
          </tr>
        <?php } elseif ($category == "Outter" && !in_array($da->dimonds_barcode, $dimondsBarcodeArray)) {
          $dimondsBarcodeArray[] = $da->dimonds_barcode;
        ?>
          <tr>
            <td>{{$p}}</td>
            <td>{{ $da->dimonds->dimond_name }}</td>
            <td>{{ $da->dimonds_barcode }}</td>
            <td>{{ \Carbon\Carbon::parse($da->issue_date)->format('d-m-Y') }}</td>
            <td>{{ \Carbon\Carbon::parse($da->return_date)->format('d-m-Y') }}</td>
            <td>{{ $getdimond->shape }}</td>
            <td>{{$getdimond->clarity}}</td>
            <td>{{$getdimond->color}}</td>
            <td>{{$getdimond->cut}}</td>
            <td>{{$getdimond->polish}}</td>
            <td>{{$getdimond->symmetry}}</td>
            <td>{{ $da->issue_weight }}</td>
            <td>{{ isset($rw) ? $rw : '' }}</td>
            <td>{{ $da->price }}</td>
            <td>{{ \Carbon\Carbon::parse($getdimond->created_at)->format('d-m-Y')}}</td>
            <td>{{ \Carbon\Carbon::parse($getdimond->delevery_date)->format('d-m-Y') }}</td>
            @php
            $p += 1;
            @endphp
          </tr>
          <?php } else {
          if ($da->price != 0) { ?>
            <tr>
              <td>{{$p}}</td>
              <td>{{ $da->dimonds->dimond_name }}</td>
              <td>{{ $da->dimonds_barcode }}</td>
              <td>{{ \Carbon\Carbon::parse($da->issue_date)->format('d-m-Y') }}</td>
              <td>{{ \Carbon\Carbon::parse($da->return_date)->format('d-m-Y') }}</td>
              <td>{{ $getdimond->shape }}</td>
              <td>{{$getdimond->clarity}}</td>
              <td>{{$getdimond->color}}</td>
              <td>{{$getdimond->cut}}</td>
              <td>{{$getdimond->polish}}</td>
              <td>{{$getdimond->symmetry}}</td>
              <td>{{ $da->issue_weight }}</td>
              <td>{{ isset($rw) ? $rw : '' }}</td>
              <td>{{ $da->price }}</td>
              <td>{{ \Carbon\Carbon::parse($getdimond->created_at)->format('d-m-Y') }}</td>
              <td>{{ \Carbon\Carbon::parse($getdimond->delevery_date)->format('d-m-Y') }}</td>
              @php
              $sum += ($da->price);
              $p += 1;
              @endphp
            </tr>
        <?php }
        } ?>
        @endif
        @endforeach
        <tr>
          <td colspan="14">
            <b>
              <h4 align='right'>Total Amount</h4>
            </b>
          </td>
          <td>
            <b>
              <h4>{{$sum}}</h4>
            </b>
          </td>
          <td colspan="2">
          </td>
        </tr>
      </tbody>
    </table>
    @endforeach
  </div>
</body>

</html>