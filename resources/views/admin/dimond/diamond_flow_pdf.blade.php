<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Diamond Process Flow</title>
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
      font-family: Arial, sans-serif;
      margin-top: 40px;
    }

    th,
    td {
      border: 1px solid #000;
      padding: 12px;
      vertical-align: top;
    }

    th {
      background-color: #f2f2f2;
      text-align: left;
    }

    td div {
      margin-bottom: 6px;
    }

    .label {
      font-weight: bold;
    }

    .process_data {
      display: flex;
      gap: 15px;
      overflow: auto;
    }

    .process_data-item {
      min-width: 30%;
    }

    .stone-id {
      min-width: 200px;
    }

    @media(max-width: 991px) {
      .process_data-item {
        min-width: 50%;
      }
    }

    @media(max-width: 767px) {
      .process_data-item {
        min-width: 75%;
      }
    }

    @media(max-width: 543px) {
      .process_data-item {
        min-width: 85%;
      }
    }

    .text-orang {
      color: orange;
    }
  </style>
</head>

<body>

  @if(count($data)>0)
  @foreach($data as $item)
  <table>
    <tr>
      <th>Stone ID</th>
      <th>Process</th>
    </tr>
    <tr>
      <td class="stone-id">
        <div><span class="label">ID (Diamond):</span> {{ $item['diamond']->dimond_name }}</div>
        <div><span class="label">Date:</span> {{ \Carbon\Carbon::parse($item['diamond']->created_at)->format('d-m-Y')}}</div>
        <div><span class="label">Party Name:</span> {{ $item['diamond']->parties->fname }}</div>
        <div><span class="label">R Weight:</span> {{ $item['diamond']->weight }}</div>
        <div><span class="label">C Weight:</span> {{ $item['diamond']->required_weight }}</div>
      </td>
      <td class="process_data">
        @foreach($item['processes'] as $da)
        <div class="process_data-item">
          <div class="@if($da->return_weight == '') text-orang @endif"><span class="label">Process:</span> {{ $da->designation }}</div>
          <div><span class="label">Name (Worker):</span> {{ $da->worker_name }}</div>
          <div><span class="label">I Date:</span> {{ \Carbon\Carbon::parse($da->issue_date)->format('d-m-Y')}}</div>
          <div><span class="label">R Date:</span> {{ \Carbon\Carbon::parse($da->return_date)->format('d-m-Y')}}</div>
          <div><span class="label">I Weight:</span> {{ $da->issue_weight }}</div>
          <div><span class="label">R Weight:</span> {{ $da->return_weight }}</div>
        </div>
        @endforeach
        <!-- <div class="process_data-item">
          <div><span class="label">P Name:</span> Polish</div>
          <div><span class="label">Date:</span> 2025-04-10</div>
          <div><span class="label">R Weight:</span> 1.20</div>
          <div><span class="label">C Weight:</span> 1.18</div>
          <div><span class="label">E Name (Employee):</span> Jane Smith</div>
        </div>
        <div class="process_data-item">
          <div><span class="label">P Name:</span> Polish</div>
          <div><span class="label">Date:</span> 2025-04-10</div>
          <div><span class="label">R Weight:</span> 1.20</div>
          <div><span class="label">C Weight:</span> 1.18</div>
          <div><span class="label">E Name (Employee):</span> Jane Smith</div>
        </div>
        <div class="process_data-item">
          <div><span class="label">P Name:</span> Polish</div>
          <div><span class="label">Date:</span> 2025-04-10</div>
          <div><span class="label">R Weight:</span> 1.20</div>
          <div><span class="label">C Weight:</span> 1.18</div>
          <div><span class="label">E Name (Employee):</span> Jane Smith</div>
        </div> -->
      </td>
    </tr>
  </table>
  <br>
  @endforeach
  @else
  <h1>No Data Found</h1>
  @endif

</body>

</html>