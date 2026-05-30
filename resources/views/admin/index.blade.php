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

            <!-- <div class="col-md-3">
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
            </div> -->

            <div class="col-md-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Pending Dimonds</p>
                                <h4 class="mb-0"><a class="text-decoration-none text-dark" href="{{ route('admin.dimond.index', ['status' => 'Pending']) }}">{{ $pending_count }}</a></h4>
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
                                <p class="text-muted fw-medium">Processing Dimonds</p>
                                <h4 class="mb-0"><a class="text-decoration-none text-dark" href="{{ route('admin.dimond.index', ['status' => 'Processing']) }}">{{ $processing_count }}</a></h4>
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
                                <p class="text-muted fw-medium">Completed Dimonds</p>
                                <h4 class="mb-0"><a class="text-decoration-none text-dark" href="{{ route('admin.dimond.index', ['status' => 'Completed']) }}">{{ $completed_count }}</a></h4>
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
                                <p class="text-muted fw-medium">Current Month Delivered</p>
                                <h4 class="mb-0"><a class="text-decoration-none text-dark" href="{{ route('admin.dimond.index', ['status' => 'Delivered', 'delivery_year' => \Carbon\Carbon::now()->year, 'delivery_month' => \Carbon\Carbon::now()->month]) }}">{{ $current_month_delivered_count }}</a></h4>
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

            <!-- <div class="col-md-3">
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
            </div> -->

            <!-- <div class="col-md-3">
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
            </div> -->

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
                                <th>Repair</th>
                                <!-- <th>Return</th> -->
                                <th>Delivered</th>
                                <th>Total Dimond</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $totalDiscuss = 0;
                            $totalHPHT = 0;
                            $totalPending = 0;
                            $totalOutter = 0;
                            $totalProcessing = 0;
                            $totalCompleted = 0;
                            $totalRepair = 0;
                            $totalReturn = 0;
                            $totalDelivered = 0;
                            $totalDiamonds = 0;
                            ?>
                            @foreach ($partyes as $partyList)
                            <?php
                            $baseQuery = DB::table('processes')
                                ->join('dimonds', 'processes.dimonds_id', '=', 'dimonds.id')
                                ->where('dimonds.parties_id', $partyList->id)
                                ->where('dimonds.is_kp', 0)
                                ->where(function ($query) {
                                    $query->where('processes.return_weight', '')->orWhereNull('processes.return_weight');
                                });

                            $discussCount = (clone $baseQuery)
                                ->where('processes.designation', 'discus')
                                ->count();

                            $hphtCount = (clone $baseQuery)
                                ->where('processes.designation', 'HPHT')
                                ->count();

                            $totalDimond = Dimond::where('parties_id', $partyList->id)->where('status', '!=', 'Delivered')->where('status', '!=', 'OutterProcessing')->where('is_kp', 0)->count();
                            $outterDimond = Dimond::where(['parties_id' => $partyList->id, 'status' => 'OutterProcessing'])->where('is_kp', 0)->count();
                            $pendingDimond = Dimond::where(['parties_id' => $partyList->id, 'status' => 'Pending'])->where('is_kp', 0)->count();
                            $processingDimond = Dimond::where('parties_id', $partyList->id)->where('status', 'Processing')->where('is_kp', 0)->count();
                            $completedDimond = Dimond::where(['parties_id' => $partyList->id, 'status' => 'Completed'])->where('is_kp', 0)->count();
                            $deliveredDimond = Dimond::where(['parties_id' => $partyList->id, 'status' => 'Delivered'])->where('is_kp', 0)->count();
                            $repairDimond = DB::table('repairs')
                                ->join('dimonds', 'repairs.dimonds_id', '=', 'dimonds.id')
                                ->where('dimonds.parties_id', $partyList->id)
                                ->where('dimonds.status', 'Processing')
                                ->where('dimonds.is_kp', 0)
                                ->distinct()
                                ->count('dimonds.id');
                            $returnCount = DB::table('processes')
                                ->join('dimonds', 'processes.dimonds_id', '=', 'dimonds.id')
                                ->where('dimonds.parties_id', $partyList->id)
                                ->where('dimonds.is_kp', 0)
                                ->whereNotNull('processes.return_weight')
                                ->where('processes.return_weight', '!=', '')
                                ->distinct()
                                ->count('dimonds.id');

                            $totalDiscuss += $discussCount;
                            $totalHPHT += $hphtCount;
                            $totalPending += $pendingDimond;
                            $totalOutter += $outterDimond;
                            $totalProcessing += $processingDimond;
                            $totalCompleted += $completedDimond;
                            $totalRepair += $repairDimond;
                            $totalDelivered += $deliveredDimond;
                            $totalReturn += $returnCount;
                            $totalDiamonds += $totalDimond;
                            ?>
                            <tr>
                                <td>{{ $partyList->fname }}&nbsp;{{ $partyList->lname }}</td>
                                <td><a href="{{ route('admin.dimond.index', ['designation' => 'discus', 'party_id' => $partyList->id]) }}">{{ $discussCount }}</a></td>
                                <td><a href="{{ route('admin.dimond.index', ['designation' => 'HPHT', 'party_id' => $partyList->id]) }}">{{ $hphtCount }}</a></td>
                                <td><a href="{{ route('admin.dimond.index', ['status' => 'Pending', 'party_id' => $partyList->id]) }}">{{ $pendingDimond }}</a></td>
                                <td><a href="{{ route('admin.dimond.index', ['status' => 'OutterProcessing', 'party_id' => $partyList->id]) }}">{{ $outterDimond }}</a></td>
                                <td><a href="{{ route('admin.dimond.index', ['status' => 'Processing', 'party_id' => $partyList->id]) }}">{{ $processingDimond }}</a></td>
                                <td><a href="{{ route('admin.dimond.index', ['status' => 'Completed', 'party_id' => $partyList->id]) }}">{{ $completedDimond }}</a></td>
                                <td><a href="{{ route('admin.dimond.index', ['designation' => 'repair', 'party_id' => $partyList->id]) }}">{{ $repairDimond }}</a></td>
                                <!-- <td><a href="{{ route('admin.dimond.index', ['designation' => 'return', 'party_id' => $partyList->id]) }}">{{ $returnCount }}</a></td> -->
                                <td><a href="{{ route('admin.dimond.index', ['status' => 'Delivered', 'party_id' => $partyList->id]) }}">{{ $deliveredDimond }}</a></td>
                                <td><a href="{{ route('admin.dimond.index', ['party_id' => $partyList->id, 'status_not' => 'Delivered']) }}">{{ $totalDimond }}</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th>Total</th>
                                <th>{{ $totalDiscuss }}</th>
                                <th>{{ $totalHPHT }}</th>
                                <th>{{ $totalPending }}</th>
                                <th>{{ $totalOutter }}</th>
                                <th>{{ $totalProcessing }}</th>
                                <th>{{ $totalCompleted }}</th>
                                <th>{{ $totalRepair }}</th>
                                <th>{{ $totalReturn }}</th>
                                <th>{{ $totalDelivered }}</th>
                                <th>{{ $totalDiamonds }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->

<!-- <div class="row">
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
</div> -->

@endsection