<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode</title>
    <style>
        p {
            font-size: 6px;
            font-family: Arial, Helvetica, sans-serif;
            margin-left: 35px;
        }

        #barcode {
            width: 80px;
            height: 50px;
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
            generateBarcode('{{ $barcode->barcode }}');
        };
    </script>
    <div>
        <div style="margin-left:30px;margin-top:-10px"><svg id="barcode"></svg></div>
    </div>
    <button class="no-print" onclick="window.print()">Print</button>

    <script>
        function generateBarcode(value) {
            JsBarcode("#barcode", value, {
                format: "CODE128",
                displayValue: true,
                height: 100,
                width: 4,
                fontOptions: "bold",
                fontSize: 40,
            });
        }
    </script>
</body>

</html>
