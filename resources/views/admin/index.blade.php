<?php

use App\Models\Dimond;
use Illuminate\Support\Facades\DB;
?>
@extends('layouts.admin')

@section('content')


<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Dashboard</h4>
        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="row">

            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Delivered Dimonds</p>
                                <h4 class="mb-0">{{ $deliverd_count }}</h4>
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

            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Completed Dimonds</p>
                                <h4 class="mb-0">{{ $completed_count }}</h4>
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

            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Processing Dimonds</p>
                                <h4 class="mb-0">{{ $processing_count }}</h4>
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

            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Pending Dimonds</p>
                                <h4 class="mb-0">{{ $pending_count }}</h4>
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

            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Outter Dimonds</p>
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

            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Total Dimonds</p>
                                <h4 class="mb-0">{{ $total_count }}</h4>
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
<!-- end row -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Party Report</h4>
                <div class="table-responsive">
                    <table id="" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                        <thead>
                            <tr>
                                <th>Party Name</th>
                                <th>Discus</th>
                                <th>HPHT</th>
                                <th>Pending</th>
                                <th>Outter</th>
                                <th>Processing</th>
                                <th>Completed</th>
                                <th>Delivered</th>
                                <th>Total Dimond</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($partyes as $partyList)
                            <?php
                            $baseQuery = DB::table('processes')
                                ->join('dimonds', 'processes.dimonds_id', '=', 'dimonds.id')
                                ->where('dimonds.parties_id', $partyList->id)->where('processes.return_weight', '');

                            $discussCount = (clone $baseQuery)
                                ->where('processes.designation', 'Discuss')
                                ->count();

                            $hphtCount = (clone $baseQuery)
                                ->where('processes.designation', 'HPHT')
                                ->count();

                            $totalDimond = Dimond::where('parties_id', $partyList->id)->where('status', '!=', 'Delivered')->count();
                            $outterDimond = Dimond::where(['parties_id' => $partyList->id, 'status' => 'OutterProcessing'])->count();
                            $pendingDimond = Dimond::where(['parties_id' => $partyList->id, 'status' => 'Pending'])->count();
                            $processingDimond = Dimond::where('parties_id', $partyList->id)->where('status', 'Processing')->count();
                            $completedDimond = Dimond::where(['parties_id' => $partyList->id, 'status' => 'Completed'])->count();
                            $deliveredDimond = Dimond::where(['parties_id' => $partyList->id, 'status' => 'Delivered'])->count();
                            ?>
                            <tr>
                                <td>{{ $partyList->fname }}&nbsp;{{ $partyList->lname }}</td>
                                <td>{{ $discussCount }}</td>
                                <td>{{ $hphtCount }}</td>
                                <td>{{ $pendingDimond }}</td>
                                <td>{{ $outterDimond }}</td>
                                <td>{{ $processingDimond }}</td>
                                <td>{{ $completedDimond }}</td>
                                <td>{{ $deliveredDimond }}</td>
                                <td>{{ $totalDimond }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Worker Avg</h4>
                <form method="GET" action="">
                    <div class="row m-0  mt-4 mb-4">
                        <div class="col-md-1">
                            <div class="mb-3">
                                <label for="month">Select Month:</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="mb-3">
                                <input type="month" name="month" class="form-control" id="month" value="{{ $selectedMonth }}" required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="d-flex gap-2 mb-3">
                                <button type="submit" class="btn btn-primary w-md">Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered dt-responsive nowrap w-100 mt-3">
                        <thead class="table-light">
                            <tr>
                                <th class="align-middle">Worker Name</th>
                                <th class="align-middle">Issue weight</th>
                                <th class="align-middle">Diamonds</th>
                                <th class="align-middle">Avg.</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($workerData as $data)
                            <tr>
                                <td>{{ $data['name'] }}</td>
                                <td>{{ number_format($data['issueWeight'], 2) }}</td>
                                <td>{{ $data['diamondCount'] }}</td>
                                <td>{{ number_format($data['avgWeight'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Transaction Modal -->
<!-- <div class="modal fade transaction-detailModal" tabindex="-1" role="dialog" aria-labelledby="transaction-detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="transaction-detailModalLabel">Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2">Product id: <span class="text-primary">#SK2540</span></p>
                <p class="mb-4">Billing Name: <span class="text-primary">Neal Matthews</span></p>

                <div class="table-responsive">
                    <table class="table align-middle table-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <div>
                                        <img src="{{ asset('assets/images/product/img-7.png') }}" alt="" class="avatar-sm">
                                    </div>
                                </th>
                                <td>
                                    <div>
                                        <h5 class="text-truncate font-size-14">Wireless Headphone (Black)</h5>
                                        <p class="text-muted mb-0">$ 225 x 1</p>
                                    </div>
                                </td>
                                <td>$ 255</td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <div>
                                        <img src="{{ asset('assets/images/product/img-4.png') }}" alt="" class="avatar-sm">
                                    </div>
                                </th>
                                <td>
                                    <div>
                                        <h5 class="text-truncate font-size-14">Phone patterned cases</h5>
                                        <p class="text-muted mb-0">$ 145 x 1</p>
                                    </div>
                                </td>
                                <td>$ 145</td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h6 class="m-0 text-right">Sub Total:</h6>
                                </td>
                                <td>
                                    $ 400
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h6 class="m-0 text-right">Shipping:</h6>
                                </td>
                                <td>
                                    Free
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <h6 class="m-0 text-right">Total:</h6>
                                </td>
                                <td>
                                    $ 400
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div> -->
<!-- end modal -->

<!-- subscribeModal -->
<!-- <div class="modal fade" id="subscribeModal" tabindex="-1" aria-labelledby="subscribeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <div class="avatar-md mx-auto mb-4">
                        <div class="avatar-title bg-light rounded-circle text-primary h1">
                            <i class="mdi mdi-email-open"></i>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-xl-10">
                            <h4 class="text-primary">Subscribe !</h4>
                            <p class="text-muted font-size-14 mb-4">Subscribe our newletter and get notification to stay update.</p>

                            <div class="input-group bg-light rounded">
                                <input type="email" class="form-control bg-transparent border-0" placeholder="Enter Email address" aria-label="Recipient's username" aria-describedby="button-addon2">

                                <button class="btn btn-primary" type="button" id="button-addon2">
                                    <i class="bx bxs-paper-plane"></i>
                                </button>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->
<!-- end modal -->

@endsection