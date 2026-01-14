<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print Barcodes</title>
  <style>
    p {
      font-size: 7px;
      font-family: Arial, Helvetica, sans-serif;
      margin-left: 30px;
      margin-bottom: 13px;
    }

    p.content {
      margin-left: 25px;
      font-size: 10px;
      margin-top: -15px;
    }

    .slip {
      margin-bottom: 10px;
    }

    svg {
      width: 100px;
      height: 60px;
      margin-left: 30px;
      margin-top: -25px;
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
    const diamondData = [
      @foreach($data as $diamond) {
        name: '{{ $diamond->dimond_name }}',
        weight: '{{ $diamond->weight }}',
        barcode: '{{ $diamond->barcode_number }}',
        date: '{{ $diamond->created_at->format("d-m") }}',
        party: '{{ $diamond->parties->party_code ?? "" }}'
      },
      @endforeach
    ];

    window.onload = function() {
      diamondData.forEach((diamond, index) => createSlip(diamond, index));
    };

    function createSlip(diamond, index) {
      const slip = document.createElement('div');
      slip.className = 'slip';

      // Top text
      slip.innerHTML = `
      <p style="font-size:12px;"><b>${diamond.name}&nbsp;&nbsp;|&nbsp;&nbsp;W: ${diamond.weight}</b></p>
      <svg id="barcode${index}"></svg>
      <p class="content">${diamond.date}&nbsp;|&nbsp;GKD&nbsp;|&nbsp;${diamond.party}</p>
    `;

      document.body.appendChild(slip);

      JsBarcode(`#barcode${index}`, diamond.barcode, {
        format: "CODE128",
        displayValue: false,
        height: 100,
        width: 4
      });
    }
  </script>

  <button class="no-print" style="margin-left:200px;" onclick="window.print()">Print</button>
  <a href="/admin/diamondprintlist" class="no-print"><button>Back</button></a>
</body>

</html>






<?php /*

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print Barcodes</title>
  <style>
    p {
      font-size: 8px;
      font-family: Arial, Helvetica, sans-serif;
      margin-left: 27px;
    }

    .barcode-container {
      margin-left: 30px;
      margin-top: -10px;
      display: flex;
      flex-direction: column;
      align-items: flex-start;
    }

    .barcode {
      width: 40px;
      height: 25px;
    }

    svg {
      width: 150px !important;
      height: 100px !important;
      margin-top: -30px;
      // margin-bottom: -20px; 
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
    const diamondData = [
      @foreach($data as $diamond) {
        name: '{{ $diamond->dimond_name }}',
        weight: '{{ $diamond->weight }}',
        barcode_number: '{{ $diamond->barcode_number }}'
      },
      @endforeach
    ];

    window.onload = function() {
      diamondData.forEach((diamond, index) => {
        generateBarcode(diamond.barcode_number, index);
      });
    };

    function generateBarcode(value, index) {
      const barcodeDiv = document.createElement('div');
      barcodeDiv.className = 'barcode-container';

      const svg = document.createElementNS("http://www.w3.org/2000/svg", "svg");
      svg.className = 'barcode';
      svg.id = `barcode${index}`;

      const info = document.createElement('p');
      info.innerHTML = `<b>SID: ${diamondData[index].name}&nbsp;|&nbsp;RW: ${diamondData[index].weight}</b>`;

      const label = document.createElement('p');
      label.style.fontSize = '8px';
      label.style.marginTop = '-20px';
      label.style.marginLeft = '50px';
      label.innerText = 'DI Diamond';

      barcodeDiv.appendChild(info);
      barcodeDiv.appendChild(svg);
      barcodeDiv.appendChild(label);
      document.body.appendChild(barcodeDiv);

      JsBarcode(svg, value, {
        format: "CODE128",
        displayValue: true,
        height: 100,
        width: 4,
        fontOptions: "bold",
        fontSize: 25,
      });
    }
  </script>

  <button class="no-print" style="margin-left:200px;" onclick="window.print()">Print</button>
  <a href="/admin/diamondprintlist" class="no-print"><button>Back</button></a>
</body>

</html>

*/ ?>