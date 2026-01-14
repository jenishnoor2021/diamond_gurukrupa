<?php

use Carbon\Carbon;
use App\Models\Process;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Party Report</title>
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
      <h1 class="align-center title">Party Report</h1>
      <h1 style="margin-top:-5px">{{$company->name}}</h1>
      <p style="font-size:10px;margin-top:-5px">{{$company->address}}</p>
    </center>
    <hr />
    <div class="row">
      <div class="column-left">
        <h4><u>Party Details</u></h4>
        <p><strong class="align-center title">Invoice No: # {{$party->id}}/2024</strong></p>
        <p><strong class="align-center title">Name:</strong>&nbsp;{{$party->fname}}&nbsp;&nbsp;{{isset($party->lname)?$party->lname:''}}</p>
        <p><strong class="align-center title">Address:</strong>&nbsp;{{isset($party->address)?$party->address:''}}</p>
        <p><strong class="align-center title">Phone:</strong>&nbsp;{{isset($party->mobile)?$party->mobile:''}}</p>
        <p><strong class="align-center title">GST No.:</strong>&nbsp;{{isset($party->gst_no)?$party->gst_no:''}}</p>
        <p><strong class="align-center title">Bill Date: {{ \Carbon\Carbon::parse(Carbon::now())->format('d-m-Y H:i:s') }}</strong></p>
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
          <th width="5%">Amount</th>
          <th width="10%">Created At</th>
        </tr>
      </thead>
      @php
      $sum = 0;
      @endphp
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
          <td>{{$da->amount}}</td>
          <td>{{\Carbon\Carbon::parse($da->created_at)->format('d-m-Y')}}</td>
          @php
          $sum += ($da->amount);
          @endphp
        </tr>
        @endforeach
        <tr>
          <td align="right" colspan="10">
            <b>
              <h4>Total Amount</h4>
            </b>
          </td>
          <td align="right">
            <b>
              <h4>{{$sum}}</h4>
            </b>
          </td>
          <td>
          </td>
        </tr>
        @if($getGst == 'gst')
        <tr>
          <td align="right" colspan="10">
            <b>
              <h4>SGST (1.5 %)</h4>
            </b>
          </td>
          <td align="right">
            <b>
              <h4><?php $sgst = ($sum * 1.5) / 100;
                  echo $sgst; ?></h4>
            </b>
          </td>
          <td>
          </td>
        </tr>
        <tr>
          <td align="right" colspan="10">
            <b>
              <h4>CGST (1.5 %)</h4>
            </b>
          </td>
          <td align="right">
            <b>
              <h4><?php $cgst = ($sum * 1.5) / 100;
                  echo $cgst; ?></h4>
            </b>
          </td>
          <td>
          </td>
        </tr>
        <tr>
          <td align="right" colspan="10">
            <b>
              <h4>Final Amount</h4>
            </b>
          </td>
          <td align="right">
            <b>
              <h4>{{$sum+$sgst+$cgst}}</h4>
            </b>
          </td>
          <td>
          </td>
        </tr>
        @endif
      </tbody>
    </table>
  </div>
</body>

</html>