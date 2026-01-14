@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Expense List</h4>

            <!-- <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                        <li class="breadcrumb-item active">Expense List</li>
                    </ol>
                </div> -->

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-check-all me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div id="right">
                    <div id="menu" class="mb-3">
                        <a class="btn btn-info waves-effect waves-light" href="{{ route('admin.expense.create') }}">
                            <i class="fa fa-plus editable" style="font-size:15px;">&nbsp;ADD</i>
                        </a>
                    </div>
                </div>

                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($expenses as $expense)
                        <tr>
                            <td>
                                <a href="{{ route('admin.expense.edit', $expense->id) }}"
                                    class="btn btn-outline-primary waves-effect waves-light"><i
                                        class="fa fa-edit"></i></a>
                                <a href="{{ route('admin.expense.destroy', $expense->id) }}"
                                    onclick="return confirm('Sure ! You want to delete ?');"
                                    class="btn btn-outline-danger waves-effect waves-light"><i
                                        class="fa fa-trash"></i></a>
                            </td>
                            <td>{{ $expense->title }}</td>
                            <td>
                                @if (strlen($expense->description) > 100)
                                {!! substr($expense->description, 0, 100) !!}
                                <span class="read-more-show hide_content">More<i
                                        class="fa fa-angle-down"></i></span>
                                <span class="read-more-content">
                                    {{ substr($expense->description, 100, strlen($expense->description)) }}
                                    <span class="read-more-hide hide_content">Less <i
                                            class="fa fa-angle-up"></i></span> </span>
                                @else
                                {{ $expense->description }}
                                @endif
                            </td>
                            <td>{{ $expense->amount }}</td>
                            <td>{{ \Carbon\Carbon::parse($expense->date)->format('d-m-Y') }}</td>
                            <td>
                                <!-- {!! Form::open([
                                'method' => 'post',
                                'action' => 'AdminExpenceController@updateStatus',
                                'class' => 'form-horizontal',
                                'id' => 'myForm',
                                ]) !!}
                                @csrf
                                <input type="hidden" name="id" value="{{ $expense->id }}">
                                <div class="mb-3">
                                    <select name="status" id="status1" class="form-select">
                                        <option value="Pending" {{ $expense->status == 'Pending' ? 'selected' : '' }}>
                                            Pending
                                        </option>
                                        <option value="Processing"
                                            {{ $expense->status == 'Processing' ? 'selected' : '' }}>Processing
                                        </option>
                                        <option value="Completed"
                                            {{ $expense->status == 'Completed' ? 'selected' : '' }}>Completed
                                        </option>
                                    </select>
                                </div>
                                {!! Form::close() !!} -->
                                {{ $expense->status }}

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
@endsection

@section('script')
<script>
    $(document).ready(function() {
        // Capture the change event of the dropdown
        $('#status1').on('change', function() {
            // Trigger form submission when the dropdown changes
            $('#myForm').submit();
        });
    });
</script>
@endsection