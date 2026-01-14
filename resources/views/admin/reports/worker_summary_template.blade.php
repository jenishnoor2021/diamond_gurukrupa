<?php

use Carbon\Carbon;
use App\Models\Process;
use App\Models\Dimond;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Worker Summary Report</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 40px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }

    .header-table {
      border: none;
    }

    .header-table td {
      text-align: center;
      padding: 10px;
    }

    .header-title {
      font-size: 20px;
      font-weight: bold;
    }

    .header-subtitle {
      font-size: 30px;
      font-weight: bold;
      padding-top: 0 !important;
    }

    .header-address {
      font-size: 14px;
      padding-top: 0 !important;
    }

    /* .data-table th,
    .data-table td {
      border: 1px solid #000;
      padding: 10px;
      text-align: center;
    } */

    .data-table th {
      border: 1px solid #000;
      padding: 10px;
      font-size: 12px;
      text-align: center;
    }

    .data-table td {
      padding: 10px;
      font-size: 10px;
      text-align: center;
    }

    .data-table th {
      background-color: #f2f2f2;
      font-weight: bold;
    }
  </style>
</head>

<body>

  <!-- Header Table -->
  <table class="header-table">
    <tr>
      <td class="header-title" colspan="5">WORKER SUMMARY REPORT</td>
    </tr>
    <tr>
      <td class="header-subtitle" colspan="5">{{$company->name}}</td>
    </tr>
    <tr>
      <td class="header-address" colspan="5">
        {{$company->address}}
      </td>
    </tr>
  </table>

  <!-- Data Table -->
  <?php if (isset($_GET['worker_name']) && $_GET['worker_name'] == 'all') { ?>
    <table class="data-table">
      <tr>
        <th>Worker Name</th>
        <th>Total Diamond</th>
      </tr>
      <tbody>
        <?php $total = 0; ?>
        @foreach($workers as $worker)
        <?php
        $processcount = Process::where('worker_name', $worker->fname)->where('return_weight', null)->count();
        ?>
        <tr>
          <td>{{$worker->fname}}&nbsp;{{$worker->lname}}</td>
          <td>{{$processcount}}</td>
        </tr>
        <?php $total = $total + $processcount; ?>
        @endforeach
        <tr>
          <td align="right">Total</td>
          <td>{{$total}}</td>
        </tr>
      </tbody>
    </table>
  <?php } else { ?>
    @foreach($workers as $worker)
    <center>
      <h4>{{$worker->fname}}&nbsp;{{$worker->lname}}</h4>
    </center>
    <table class="data-table">
      <tr>
        <th>Party Name</th>
        <th>Diamond Name</th>
        <th>Issue Date</th>
        <th>Diamond Barcode</th>
        <th>Created Date</th>
      </tr>
      <tbody>
        <?php
        $workerprocess = Process::where('worker_name', $worker->fname)->where('return_weight', null)->get();
        ?>
        @foreach($workerprocess as $workerpro)
        <?php
        $dimond = Dimond::where('barcode_number', $workerpro->dimonds_barcode)->first();
        ?>
        <tr>
          <td>{{$dimond->parties->fname}}</td>
          <td>{{$dimond->dimond_name}}</td>
          <td>{{ \Carbon\Carbon::parse($workerpro->issue_date)->format('d-m-Y') }}</td>
          <td>{{$workerpro->dimonds_barcode}}</td>
          <td>{{ \Carbon\Carbon::parse($dimond->creatd_at)->format('d-m-Y') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @endforeach
  <?php } ?>

</body>

</html>