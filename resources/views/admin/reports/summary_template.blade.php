<?php

use Carbon\Carbon;
use App\Models\Process;
use App\Models\Dimond;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Summary Report</title>
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

  <!-- Header Table -->
  <div class="container">
    <center>
      <h1>SUMMARY REPORT</h1>
      <h1 style="margin-top:-10px">{{$company->name}}</h1>
      <p style="font-size:10px;margin-top:-5px">{{$company->address}}</p>
    </center>
    <hr />

    <?php if (isset($_GET['party_id'])) { ?>
      <div class="row">
        <div class="column-left">
          <h4><u>Party Details</u></h4>
          <p><strong class="align-center title">Invoice No:</strong> # {{$partyes[0]->id}}/{{ \Carbon\Carbon::now()->format('Y') }}</p>
          <p><strong class="align-center title">Name:</strong>&nbsp;{{$partyes[0]->fname}}&nbsp;&nbsp;{{isset($partyes[0]->lname)?$partyes[0]->lname:''}}</p>
          <p><strong class="align-center title">Bill Date:</strong> {{ \Carbon\Carbon::parse(Carbon::now())->format('d-m-Y H:i:s') }}</p>
        </div>
        <div class="column-right">
          <h4><u>Company Details</u></h4>
          <p><strong class="align-center title">GST No.:</strong> {{$company->gst_no}}</p>
          <p><strong class="align-center title">PAN No.:</strong> {{$company->pan_no}}</p>
          <p><strong class="align-center title">Phones :</strong> {{$company->contact}}</p>
        </div>
        <div style="clear: both;"></div>
      </div>

      <div class="table-responsive">
        <!-- Data Table -->
        <?php if ($_GET['party_id'] != 'All') { ?>
          <table class="data-table table-flush table-borderless" cellspacing="1">
            <thead>
              <tr>
                <th>Dimond Name</th>
                <th>Barcode</th>
                <th>Created</th>
                <th>Modified</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($partyes as $partyList)
              <?php
              $allDimonds = Dimond::where('parties_id', $partyList->id)->where('status', '!=', 'Delivered')->get();
              ?>
              @foreach($allDimonds as $allDimond)
              <tr>
                <td align="left">{{$allDimond->dimond_name}}</td>
                <td>{{$allDimond->barcode_number}}</td>
                <td>{{ \Carbon\Carbon::parse($allDimond->created_at)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($allDimond->updated_at)->format('d-m-Y') }}</td>
                <td align="left">{{$allDimond->status}}</td>
              </tr>
              @endforeach
              @endforeach
            </tbody>
          </table>
        <?php } else { ?>
          <table class="data-table table-flush table-borderless" cellspacing="1">
            <thead>
              <tr>
                <th>Party Name</th>
                <th>Pending</th>
                <th>Outter</th>
                <th>Processing</th>
                <th>Completed</th>
                <th>Delivered</th>
                <th>Total Dimond</th>
              </tr>
            </thead>
            <tbody>
              @foreach($partyes as $partyList)
              <?php
              $totalDimond = Dimond::where('parties_id', $partyList->id)->count();
              $outterDimond = Dimond::where(['parties_id' => $partyList->id, 'status' => 'OutterProcessing'])->count();
              $pendingDimond = Dimond::where(['parties_id' => $partyList->id, 'status' => 'Pending'])->count();
              $processingDimond = Dimond::where('parties_id', $partyList->id)->where('status', 'Processing')->count();
              $completedDimond = Dimond::where(['parties_id' => $partyList->id, 'status' => 'Completed'])->count();
              $deliveredDimond = Dimond::where(['parties_id' => $partyList->id, 'status' => 'Delivered'])->count();
              ?>
              <tr>
                <td align="left">{{$partyList->fname}}&nbsp;{{$partyList->lname}}</td>
                <td>{{$pendingDimond}}</td>
                <td>{{$outterDimond}}</td>
                <td>{{$processingDimond}}</td>
                <td>{{$completedDimond}}</td>
                <td>{{$deliveredDimond}}</td>
                <td>{{$totalDimond}}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        <?php } ?>
      </div>
    <?php } ?>
  </div>

</body>

</html>