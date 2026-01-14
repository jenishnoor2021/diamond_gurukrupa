@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Direct Issues Return</h4>

            <!-- <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
                    <li class="breadcrumb-item active">Direct Issues Return</li>
                </ol>
            </div> -->

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="row">

            <div class="col-md-2">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Total</p>
                                <h4 class="mb-0">{{ $dimondcount }}</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-copy-alt font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Pending</p>
                                <h4 class="mb-0">{{ $pendingcount }}</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-copy-alt font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Processing</p>
                                <h4 class="mb-0">{{ $outercount + $issuecount }}</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Inner</p>
                                <h4 class="mb-0">{{ $issuecount }}</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center ">
                                <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="bx bx-archive-in font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Outter</p>
                                <h4 class="mb-0">{{ $outercount }}</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="avatar-sm rounded-circle bg-primary mini-stat-icon">
                                    <span class="avatar-title rounded-circle bg-primary">
                                        <i class="bx bx-purchase-tag-alt font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- end row -->
    </div>
</div>

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
                                <h4 class="render-range fw-bold pt-1 mx-3">
                                    <h5><span class="text-warning">Count:</span> {{ $scancount }}</h5>
                                </h4>
                            </div>

                            <div>
                                <form method="POST" action="{{ route('admin.daily-status.store') }}">
                                    @csrf
                                    <input type="text" id="inputField2" name="inputField" class="form-control" placeholder="Search barcode" required>
                                </form>
                            </div>

                            <div class="align-self-start mt-3 mt-sm-0 mb-2">
                                <a href="/admin/daily-status/refresh" class="btn btn-primary text-white">
                                    <i class="fas fa-sync"></i>
                                </a>
                            </div>
                        </span>

                    </div>
                </div>

                <table id="dailytable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Dimond Name</th>
                            <th>Barcode</th>
                            <th>Date</th>
                            <!-- <th>Stage</th> -->
                            <th>Current Status</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($dailys as $index => $dimond)
                        <tr>
                            <td>
                                <a href="{{ route('admin.dimond.show', $dimond->barcode) }}" class="btn btn-info"><i
                                        class="fa fa-eye"></i></a>
                                {{-- <a href="{{route('admin.daily-status.destroy', $dimond->id)}}" onclick="return confirm('Sure ! You want to delete ?');" class="btn btn-outline-danger waves-effect waves-light"><i class="fa fa-trash"></i></a> --}}
                            </td>
                            <td>{{ $dimond->dimonds->dimond_name }}</td>
                            <td>{{ $dimond->barcode }}</td>
                            <td>{{ \Carbon\Carbon::parse($dimond->updated_at)->format('d-m-Y') }}</td>
                            <td>{{ $dimond->dimonds->status }}</td>
                            <!-- <td><b><span class="{{ $dimond->stage == 'issue' ? 'text-success' : 'text-danger' }}">{{ $dimond->stage }}</span></b></td> -->
                            <td><a href="/admin/daily-status/statusupdate/{{ $dimond->id }}"
                                    class="btn {{ $dimond->status == 0 ? 'btn-danger' : 'btn-success' }}">{{ $dimond->stage }}</a>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-focus on the input field when the page loads
        document.getElementById('inputField2').focus();
    });
</script>
@endsection