<?php

use Carbon\Carbon;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Party Diamond Transfer</title>
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
      padding: 10px;
      /* border-right: 1px dotted #000; */
    }

    .column-right p,
    .column-left p {
      font-size: 13px;
    }

    .column-right {
      float: left;
      width: 50%;
      padding: 10px;
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
      /* text-align: center; */
    }

    .data-table td {
      border: 1px solid #000;
      padding: 10px;
      font-size: 10px;
      /* text-align: center; */
    }

    .data-table th {
      background-color: #f2f2f2;
      font-weight: bold;
    }

    /*table end*/
    hr {
      border-top: 1px solid #000;
    }

    @media print {
      .no-print {
        display: none;
      }
    }
  </style>
</head>

<body>
  <button class="no-print" onclick="window.print()">Print</button>
  @foreach($diamonds as $data)
  <div class="container">
    <br />
    <center>
      <h1 style="margin-top:-10px">{{$company->name}}</h1>
      <p style="font-size:10px;margin-top:-12px">{{$company->address}}</p>
      <strong class="align-center title">Slip</strong>
    </center>
    <div class="row">
      <div class="column-left">
        <p><strong class="align-center title">Bill Date: {{ \Carbon\Carbon::parse(Carbon::now())->format('d-m-Y H:i:s') }}</strong></p>
      </div>
      <div class="column-right">
      </div>
      <div style="clear: both;"></div>
    </div>
    <br />
    <table class="data-table">
      <tr>
        <th width="50%">Party</th>
        <th width="50%">Dimond Detail</th>
      </tr>
      <tbody>
        <tr>
          <td>
            <p>Party Name: {{ $data->parties->party_code }}</p>
            <p>Dimond Name: {{ $data->dimond_name }}</p>
            <p>Barcode:</p>
            <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
            <script>
              window.onload = function() {
                @foreach($diamonds as $d)
                JsBarcode("#barcode-{{ $d->id }}", "{{ $d->barcode_number }}", {
                  format: "CODE128",
                  displayValue: true,
                  height: 60,
                  width: 3,
                  fontOptions: "bold",
                  fontSize: 30,
                });
                @endforeach
              };
            </script>
            <svg id="barcode-{{ $data->id }}"></svg>
          </td>
          <td>
            <p>Row Weight: {{ $data->weight }}</p>
            <p>Polished Weight: {{ $data->required_weight }}</p>
            <p>Shap: {{ $data->shape }}</p>
            <p>clarity: {{ $data->clarity }}</p>
            <p>color: {{ $data->color }}</p>
            <p>cut: {{ $data->cut }}</p>
            <p>polish: {{ $data->polish }}</p>
            <p>symmetry: {{ $data->symmetry }}</p>
          </td>
        </tr>
      </tbody>
    </table>
    <br />
    <div>
      <div style="padding-top:50px;"></div>
      <p>Author Sigh</p>
    </div>
  </div>
  @endforeach
</body>

</html>