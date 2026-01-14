@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Invoices</h4>

            <!-- <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                        <li class="breadcrumb-item active">Invoices</li>
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
                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="mdi mdi-block-helper me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div id="right">
                    <div id="menu" class="mb-3">

                        <span id="menu-navi"
                            class="d-sm-flex flex-wrap text-center text-sm-start justify-content-sm-between">
                            <div class="">
                                <a class="btn btn-primary" href="{{ route('admin.invoice.create') }}"><i
                                        class="fa fa-plus">&nbsp;ADD</i></a>
                            </div>
                        </span>

                    </div>
                </div>

                <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Party Name</th>
                            <th>Company Name</th>
                            <th>Invoice No</th>
                            <th>Invoice Date</th>
                            <th>Place To Supply</th>
                            <th>Due Date</th>
                            <th>PDF</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($invoices as $invoice)
                        <tr>
                            <td>
                                <a href="{{route('admin.invoice.edit', $invoice->id)}}" class="btn btn-outline-primary waves-effect waves-light"><i class="fa fa-edit"></i></a>
                                <a href="{{route('admin.invoice.destroy', $invoice->id)}}" onclick="return confirm('Sure ! You want to delete ?');" class="btn btn-outline-danger waves-effect waves-light"><i class="fa fa-trash"></i></a>
                            </td>
                            <td>{{ $invoice->parties_id }}</td>
                            <td>{{ $invoice->companies_id }}</td>
                            <td>{{ $invoice->invoice_no }}</td>
                            <td>{{ $invoice->invoice_date }}</td>
                            <td>{{ $invoice->place_to_supply }}</td>
                            <td>{{ $invoice->due_date }}</td>
                            <td>
                                @if ($invoice->file != '')
                                <a href="{{ asset('invoices/' . $invoice->file) }}"
                                    target="_blank"><b>PDF</b></a>
                                @endif
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