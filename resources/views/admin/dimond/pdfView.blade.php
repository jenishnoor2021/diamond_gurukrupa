<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print Slip</title>
  <style>
    p {
      font-size: 7px;
      font-family: Arial, Helvetica, sans-serif;
      margin-left: 30px;
    }

    p.Content {
      margin-left: 25px;
    }

    #barcode {
      width: 100px;
      height: 60px;
    }

    @media print {
      .no-print {
        display: none;
      }
    }
  </style>
</head>

<body>
  <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
  <script>
    window.onload = function() {
      generateBarcode('{{$dimond->barcode_number}}');
    };
  </script>
  <div>
    <p style="font-size:12px;"><b>{{ $dimond->dimond_name }}&nbsp;&nbsp;|&nbsp;&nbsp;W: {{ $dimond->weight }}</b></p>
    <div style="margin-left:30px;margin-top:-25px"><svg id="barcode"></svg></div>
    <p class="Content" style="font-size:10px;margin-top:-15px;">{{ $dimond->created_at->format('d-m') }}&nbsp;|&nbsp;GKD &nbsp;|&nbsp; {{ $dimond->parties->party_code }}</p>
  </div>
  <button class="no-print" onclick="window.print()">Print</button>

  <script>
    function generateBarcode(value) {
      JsBarcode("#barcode", value, {
        format: "CODE128",
        displayValue: false,
        height: 100,
        width: 4,
        fontOptions: "bold",
        fontSize: 40,
      });
    }
  </script>
</body>

</html>