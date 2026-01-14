<?php

use Carbon\Carbon;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Outter Worker Slip</title>
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

    .column-right {
      float: left;
      width: 50%;
      padding: 5px;
    }

    /* Create four equal columns that floats next to each other */

    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0 auto;
    }

    /* Clear floats after the columns */
    .row:after {
      content: "";
      display: table;
      clear: both;
    }

    .d-flex {
      display: flex;
    }

    .flex-col {
      flex-direction: column;
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

    .float-right {
      float: right;
    }

    .float-left {
      float: left;
    }

    .circle-logo {
      width: 60px;
    }

    .logo {
      width: 220px;
    }

    .title {
      margin-top: 5px;
    }

    .student-name {
      margin-bottom: 10px;
    }

    .bar-code {
      width: 200px;
      align-self: center;
      margin-top: 5px;
      margin-bottom: 10px;
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
      font-size: 9px;
    }

    table tr {
      background-color: #fff;
      /* border: 1px solid #000; */
      padding: .25em;
    }

    thead {
      border-bottom: 1px solid #000;
      border-top: 1px solid #000;
    }

    table th,
    table td {
      /* padding: .625em; */
      /* border: 1px solid #000; */
    }

    .top-b {
      border-top: 1px solid #000;
    }

    /*table end*/
    hr {
      border-top: 1px solid #000;
    }
  </style>
</head>

<body>
  <div class="container">

    <div class="row">
      <div class="column-left">
        <center>
          <h4 style="font-size:10px;">OUTTER WORKER SLIP REPORT</h4>
          <h1 style="font-size:12px;">{{$company->name}}</h1>
          <p style="font-size:8px;margin-top:-8px">{{$company->address}}</p>
        </center>
        <hr />
        <p style="font-size:9px;">
          <strong>Date:</strong><span> {{ \Carbon\Carbon::parse(Carbon::now())->format('d-m-Y') }}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <strong>To:</strong><span> {{$worker_name}}</span>
        </p>
        <hr />
        <p style="font-size:9px;">
          <strong>GSTIN:</strong><span> 24AIZPB0708M1Z2</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <strong>HSN:</strong><span> AIZPB0708M</span>
        </p>
        <div class="table-responsive">
          <table class="table align-items-center table-flush table-borderless" cellspacing="1">
            <thead>
              <tr>
                <th>Diamond Name</th>
                <th>Diamond Barcode</th>
                <th>Issue Date</th>
                <th>Issue Weight</th>
              </tr>
            </thead>
            <tbody>
              @foreach($workerprocess as $workerpro)
              <tr>
                <td align="center"><?= $workerpro['dimond_name'] ?></td>
                <td align="center"><?= $workerpro['barcode_number'] ?></td>
                <td align="center"><?= $workerpro['issue_date'] ?></td>
                <td align="center"><?= $workerpro['issue_weight'] ?></td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <br>
          <div class="row" style="font-size:10px;">
            <div style="margin-left:70%">
              <p>-----------------------</p>
              <p><strong>Authorized sign</strong></p>
            </div>
          </div>
        </div>
      </div>
      <div class="column-right">
        <center>
          <h4 style="font-size:10px;">OUTTER WORKER SLIP REPORT</h4>
          <h1 style="font-size:12px;">{{$company->name}}</h1>
          <p style="font-size:8px;margin-top:-8px">{{$company->address}}</p>
        </center>
        <hr />
        <p style="font-size:9px;">
          <strong>Date:</strong><span> {{ \Carbon\Carbon::parse(Carbon::now())->format('d-m-Y') }}</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <strong>To:</strong><span> {{$worker_name}}</span>
        </p>
        <hr />
        <p style="font-size:9px;">
          <strong>GSTIN:</strong><span> 24AIZPB0708M1Z2</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <strong>HSN:</strong><span> AIZPB0708M</span>
        </p>
        <div class="table-responsive">
          <table class="table align-items-center table-flush table-borderless" cellspacing="1">
            <thead>
              <tr>
                <th>Diamond Name</th>
                <th>Diamond Barcode</th>
                <th>Issue Date</th>
                <th>Issue Weight</th>
              </tr>
            </thead>
            <tbody>
              @foreach($workerprocess as $workerpro)
              <tr>
                <td align="center"><?= $workerpro['dimond_name'] ?></td>
                <td align="center"><?= $workerpro['barcode_number'] ?></td>
                <td align="center"><?= $workerpro['issue_date'] ?></td>
                <td align="center"><?= $workerpro['issue_weight'] ?></td>
              </tr>
              @endforeach
            </tbody>
          </table>
          <br>
          <div class="row" style="font-size:10px;">
            <div style="margin-left:70%">
              <p>-----------------------</p>
              <p><strong>Authorized sign</strong></p>
            </div>
          </div>
        </div>
      </div>
      <div style="clear: both;"></div>
    </div>
  </div>
</body>

</html>