<?php

use Carbon\Carbon;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Diamond Slip</title>
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
      <h1 class="align-center title">Diamond Slip</h1>
      <h1 style="margin-top:-10px">{{$company->name}}</h1>
      <p style="font-size:10px;margin-top:-5px">{{$company->address}}</p>
    </center>
    <hr />
    <div class="row">
      <div class="column-left">
        <h4><u>Party Details</u></h4>
        <p><strong class="align-center title">Bill Date:</strong> {{ \Carbon\Carbon::parse(Carbon::now())->format('d-m-Y H:i:s') }}</p>
        <p><strong class="align-center title">Party:</strong> {{$party_name}}</p>
      </div>
      <div class="column-right">
        <h4><u>Company Details</u></h4>
        <p><strong class="align-center title">GST No.:</strong> {{$company->gst_no}}</p>
        <p><strong class="align-center title">PAN No.:</strong> {{$company->pan_no}}</p>
        <p><strong class="align-center title">Phones :</strong> {{$company->contact}}</p>
      </div>
      <div style="clear: both;"></div>
    </div>
    <br />

    <table class="data-table table-flush table-borderless" cellspacing="1">
      <thead>
        <tr>
          <th>D Name</th>
          <th>R W</th>
          <th>P W</th>
          <th>S</th>
          <th>Cut</th>
          <th>Amt.</th>
          <th>D Date</th>
        </tr>
      </thead>
      <tbody>
        <?php $t_w = 0;
        $t_p_w = 0; ?>
        @foreach($process as $proc)
        <tr>
          <td align="center"><?= $proc['dimond_name'] ?></td>
          <td align="center"><?= $proc['weight'] ?></td>
          <td align="center"><?= $proc['required_weight'] ?></td>
          <td align="center"><?= $proc['shape'] ?></td>
          <td align="center"><?= $proc['cut'] ?></td>
          <td align="center"><?= $proc['amount'] ?></td>
          <td align="center"><?= \Carbon\Carbon::parse($proc['delivery_date'])->format('d-m-Y') ?></td>
        </tr>
        <?php $t_w += $proc['weight'];
        $t_p_w += $proc['required_weight']; ?>
        @endforeach
        <tr class="top-b">
          <td align="center">Total</td>
          <td align="center"><?= $t_w ?></td>
          <td align="center"><?= $t_p_w ?></td>
          <td align="center"></td>
          <td align="center"></td>
          <td align="center"></td>
          <td align="center"></td>
        </tr>
      </tbody>
    </table>
    <br>
    <div class="row" style="font-size:15px;">
      <div style="margin-left:75%">
        <p>-----------------------</p>
        <p><strong>Authorized sign</strong></p>
      </div>
    </div>
  </div>

</body>

</html>