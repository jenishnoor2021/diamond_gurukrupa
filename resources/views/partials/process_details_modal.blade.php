@if(count($processes) > 0)
    <table class="table table-bordered mb-0">
        <thead>
            <tr>
                <th>Designation</th>
                <th>Worker</th>
                <th>Issue Date</th>
                <th>Return Date</th>
                <th>Issue Weight</th>
                <th>Return Weight</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($processes as $process)
                <tr>
                    <td>{{ $process->designation }}</td>
                    <td>{{ $process->worker_name }}</td>
                    <td>{{ \Carbon\Carbon::parse($process->issue_date)->format('d-m-Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($process->return_date)->format('d-m-Y') }}</td>
                    <td>{{ $process->issue_weight }}</td>
                    <td>{{ $process->return_weight }}</td>
                    <td>{{ $process->price }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="text-danger">No process data found.</div>
@endif
