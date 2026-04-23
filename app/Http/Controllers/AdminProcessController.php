<?php

namespace App\Http\Controllers;

use App\Models\Daily;
use App\Models\Party;
use App\Models\Dimond;
use App\Models\Worker;
use App\Models\Process;
use App\Models\PartyRate;
use App\Models\Partyrange;
use App\Models\WorkerRate;
use App\Models\Designation;
use App\Models\Workerrange;
use App\Exports\DiamondRangeExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;

class AdminProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $dimonds = Dimond::where('id', $request->dimonds_id)->first();
        $outerDesignation = Designation::where('category', 'Outter')->pluck('name')->toArray();
        if (in_array($request->designation, $outerDesignation)) {
            $dimonds->update(['status' => 'OutterProcessing']);
        } else {
            $dimonds->update(['status' => 'Processing']);
        }
        Process::create($request->all());


        // Daily::create([
        //     'dimonds_id' => $dimonds->id,
        //     'barcode' => $dimonds->barcode_number,
        //     'stage' => 'issue',
        //     'status' => 1,
        // ]);
        return redirect('admin/dimond/show/' . $request->dimonds_barcode)->with('success', "Save Record Successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $process = Process::where('id', $id)->first();
        return response()->json(['data' => $process]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request)
    {
        $process = Process::where('id', $request->id)->first();
        $dimonds = Dimond::where('id', $process->dimonds_id)->first();
        $r_weight = $request->return_weight;
        $i_weight = $process->issue_weight;

        $diffrence = $i_weight - $r_weight;
        $weight = $i_weight;
        $designation = Designation::where('name', $process->designation)->first();
        if ($designation->rate_apply_on == 'return_weight' || $designation->rate_apply_on == 'ready_to_ruff_weight') {
            $weight = $r_weight;
        }
        if ($designation->rate_apply_on == 'diff_weight') {
            $weight = $diffrence;
        }

        if ($i_weight < $r_weight) {
            return Redirect::back()->with('error', "Return weight large than Issue weight");
        }

        $rate_cut = $request->has('ratecut') ? (($request->ratecut != null) ? 1 : 0) : 0;
        $getWorker = Worker::where('fname', $process->worker_name)->where('designation', $process->designation)->first();

        if (isset($weight)) {

            $get_rate = 0;

            if ($dimonds->shape == 'Round') {
                $WorkerRange = Workerrange::where('shape', 'Round')
                    ->where('min_value', '<', $weight)
                    ->where('max_value', '>=', $weight)
                    ->first();

                if ($WorkerRange) {
                    $getkey = $WorkerRange->key;
                    $workerrate = WorkerRate::where('key', $getkey)
                        ->where('workers_id', $getWorker->id)
                        ->first();
                    if ($workerrate) {
                        $get_rate = $workerrate->value;
                    }
                    if ($get_rate == 0) {
                        $get_rate = $WorkerRange->value;
                    }
                }
            }

            if ($dimonds->shape != 'Round') {
                $WorkerRange = Workerrange::where('shape', 'Other')
                    ->where('min_value', '<', $weight)
                    ->where('max_value', '>=', $weight)
                    ->first();

                if ($WorkerRange) {
                    $getkey = $WorkerRange->key;
                    $workerrate = WorkerRate::where('key', $getkey)
                        ->where('workers_id', $getWorker->id)
                        ->first();
                    if ($workerrate) {
                        $get_rate = $workerrate->value;
                    }
                    if ($get_rate == 0) {
                        $get_rate = $WorkerRange->value;
                    }
                }
            }

            if (isset($get_rate)) {
                $countprocess = Process::where(['dimonds_id' => $process->dimonds_id, 'designation' => $process->designation])->where('return_weight', '!=', '')->count();
                $processid = Process::where(['dimonds_id' => $process->dimonds_id, 'designation' => $process->designation])->where('return_weight', '!=', '')->first();

                $getpdata = Process::where(['dimonds_id' => $process->dimonds_id, 'designation' => $process->designation])->get();

                $datas = $getpdata->pluck('id');

                $previousId = '';
                $previousdata = '';

                $currentIdIndex = $datas->search($request->id);

                if ($currentIdIndex !== false && $currentIdIndex > 0) {
                    $previousId = $datas->get($currentIdIndex - 1);
                }

                if (!empty($previousId)) {
                    $previousdata = Process::where(['id' => $previousId])->first();
                }

                if ($designation->rate_apply_on == 'ready_to_ruff_weight') {
                    $getFirstProcess = Process::where('dimonds_id', $process->dimonds_id)->where('designation', $process->designation)->first();
                    $weight = $getFirstProcess->issue_weight;
                }

                if ($rate_cut == 1) {
                    $request['price'] = 0;
                    Process::where(['dimonds_barcode' => $process->dimonds_barcode, 'worker_name' => $process->worker_name])->update(['ratecut' => 1]);
                } elseif ($countprocess == 0) {
                    // $request['price'] = $weight * ($get_rate);
                    $request['price'] = $get_rate;
                } else {
                    if ($processid->id == $request->id) {
                        // $request['price'] = $weight * ($get_rate);
                        $request['price'] = $get_rate;
                    } else {
                        if (!empty($previousdata) && $previousdata->price == 0 && $previousdata->ratecut == 1) {
                            // $request['price'] = $i_weight * ($get_rate);
                            $request['price'] = $get_rate;
                        } else {
                            $request['price'] = 0;
                        }
                    }
                }
            }
        }

        $check_process = Process::where(['dimonds_id' => $process->dimonds_id])->where('return_weight', '!=', '')->count();
        if ($check_process == 0) {
            $dimonds->update(['status' => 'Processing']);
        }
        if ($process->designation == 'Grading' && isset($r_weight)) {

            $get_party_rate = 0;
            $dimond_amount = 0;
            if ($dimonds->shape == 'Round') {
                $partyRange = Partyrange::where('shape', 'Round')
                    ->where('min_value', '<', $dimonds->weight)
                    ->where('max_value', '>=', $dimonds->weight)
                    ->first();

                if ($partyRange) {
                    $getkey = $partyRange->key;
                    $partyrate = PartyRate::where('key', $getkey)
                        ->where('parties_id', $dimonds->parties_id)
                        ->first();
                    if ($partyrate) {
                        $get_party_rate = $partyrate->value;
                    }
                    if ($get_party_rate == 0) {
                        $get_party_rate = $partyRange->value;
                    }
                }
            }

            if ($dimonds->shape != 'Round') {
                $partyRange = Partyrange::where('shape', 'Other')
                    ->where('min_value', '<', $dimonds->weight)
                    ->where('max_value', '>=', $dimonds->weight)
                    ->first();

                if ($partyRange) {
                    $getkey = $partyRange->key;
                    $partyrate = PartyRate::where('key', $getkey)
                        ->where('parties_id', $dimonds->parties_id)
                        ->first();
                    if ($partyrate) {
                        $get_party_rate = $partyrate->value;
                    }
                    if ($get_party_rate == 0) {
                        $get_party_rate = $partyRange->value;
                    }
                }
            }

            if (isset($get_party_rate)) {
                $dimond_amount = ($dimonds->weight) * ($get_party_rate);
            }

            $dimonds->update(['status' => 'Completed', 'amount' => $dimond_amount]);

            $daily = Daily::where('dimonds_id', $process->dimonds_id)->first();
            isset($daily) ? $daily->delete() : '';
        }

        $requestData = $request->all();
        $requestData['ratecut'] = $rate_cut;

        $process->update($requestData);

        $check_process = Process::where(['dimonds_id' => $process->dimonds_id])->where('return_weight', '==', '')->count();
        if ($check_process == 0 && isset($r_weight) && $process->designation != 'Grading') {
            $dimonds->update(['status' => 'Processing']);
        }

        return redirect('admin/dimond/show/' . $request->dimonds_barcode)->with('success', "Update Record Successfully");
    }


    // public function update(Request $request)
    // {
    //     $process = Process::where('id', $request->id)->first();
    //     $dimonds = Dimond::where('id', $process->dimonds_id)->first();
    //     $r_weight = $request->return_weight;
    //     $i_weight = $process->issue_weight;

    //     $diffrence = $i_weight - $r_weight;
    //     $weight = $i_weight;
    //     $designation = Designation::where('name', $process->designation)->first();
    //     if ($designation->rate_apply_on == 'return_weight') {
    //         $weight = $r_weight;
    //     }
    //     if ($designation->rate_apply_on == 'diff_weight') {
    //         $weight = $diffrence;
    //     }

    //     if ($i_weight < $r_weight) {
    //         return Redirect::back()->with('error', "Return weight large than Issue weight");
    //     }
    //     $rate_cut = $request->has('ratecut') ? (($request->ratecut != null) ? 1 : 0) : 0;
    //     if (isset($weight)) {
    //         if ($weight < 1.5)
    //             $key = 'key_1';
    //         else if ($weight >= 1.5 && $weight < 2)
    //             $key = 'key_2';
    //         else if ($weight >= 2 && $weight < 3)
    //             $key = 'key_3';
    //         else
    //             $key = 'key_4';

    //         $get_rate = WorkerRate::where('designation', $process->designation)->where('Key', $key)->first();
    //         if (isset($get_rate)) {
    //             $countprocess = Process::where(['dimonds_id' => $process->dimonds_id, 'designation' => $process->designation])->where('return_weight', '!=', '')->count();
    //             $processid = Process::where(['dimonds_id' => $process->dimonds_id, 'designation' => $process->designation])->where('return_weight', '!=', '')->first();
    //             if ($rate_cut == 1) {
    //                 $request['price'] = 0;
    //             } elseif ($countprocess == 0) {
    //                 $request['price'] = $weight * ($get_rate->value);
    //             } else {
    //                 if ($processid->id == $request->id) {
    //                     $request['price'] = $weight * ($get_rate->value);
    //                 } else {
    //                     $request['price'] = 0;
    //                 }
    //             }
    //             // if ($countprocess == 0) {
    //             //     $request['price'] = $rate_cut == 1 ? 0 : ($i_weight * ($get_rate->value));
    //             // } elseif ($rate_cut == 0) {
    //             //     $request['price'] = $i_weight * ($get_rate->value);
    //             // } else {
    //             //     $request['price'] = 0;
    //             // }
    //         }
    //     }

    //     $check_process = Process::where(['dimonds_id' => $process->dimonds_id])->where('return_weight', '!=', '')->count();
    //     if ($check_process == 0) {
    //         $dimonds->update(['status' => 'Processing']);
    //     }
    //     if ($process->designation == 'Grading' && isset($r_weight)) {

    //         if ($dimonds->weight < 1.5)
    //             $key = 'key_1';
    //         else if ($dimonds->weight >= 1.5 && $dimonds->weight < 2)
    //             $key = 'key_2';
    //         else if ($dimonds->weight >= 2 && $dimonds->weight < 3)
    //             $key = 'key_3';
    //         else
    //             $key = 'key_4';

    //         $get_party_rate = PartyRate::where('Key', $key)->first();
    //         if (isset($get_party_rate))
    //             $dimond_amount = ($dimonds->weight) * ($get_party_rate->value);

    //         $dimonds->update(['status' => 'Completed', 'amount' => $dimond_amount]);

    //         $daily = Daily::where('dimonds_id', $process->dimonds_id)->first();
    //         isset($daily) ? $daily->delete() : '';
    //     }

    //     $requestData = $request->all();
    //     $requestData['ratecut'] = $rate_cut;

    //     $process->update($requestData);

    //     $check_process = Process::where(['dimonds_id' => $process->dimonds_id])->where('return_weight', '==', '')->count();
    //     if ($check_process == 0 && isset($r_weight) && $process->designation != 'Grading') {
    //         $dimonds->update(['status' => 'Processing']);
    //     }

    //     return redirect('admin/dimond/show/' . $request->dimonds_barcode)->with('success', "Update Record Successfully");
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $process = Process::findOrFail($id);
        $count = Process::where('dimonds_id', $process->dimonds_id)->count();

        if ($count == 1) {
            $dimonds = Dimond::where('id', $process->dimonds_id)->first();
            $dimonds->update(['status' => 'Pending']);
        }

        $process->delete();
        // $daily = Daily::where('dimonds_id', $process->dimonds_id)->first();
        // $daily->delete();
        return Redirect::back()->with('success', "Deleted Record Successfully");
    }

    public function bulkIssue(Request $request)
    {
        $designations = Designation::get();
        $workerLists = Worker::where('is_active', 1)->get();

        return view('admin.reports.bulk_issue', compact('workerLists', 'designations'));
    }

    public function checkDiamondStatus(Request $request)
    {
        $barcode = $request->barcode;

        // Step 1: Find diamond by barcode
        $diamond = Dimond::where('barcode_number', $barcode)->first();

        if (!$diamond) {
            return response()->json(['status' => 'error', 'message' => 'Diamond not found.']);
        }

        // Step 2: Check diamond not delivered
        if (strtoupper($diamond->status) == 'DELIVERED') {
            return response()->json(['status' => 'error', 'message' => 'This diamond is already delivered.']);
        }

        // Step 3: Check last process entry
        $lastProcess = Process::where('dimonds_id', $diamond->id)
            ->orderBy('id', 'desc')
            ->first();

        if ($lastProcess && (empty($lastProcess->return_date) || empty($lastProcess->return_weight))) {
            return response()->json([
                'status' => 'error',
                'message' => 'This diamond is already issued and not yet returned.'
            ]);
        }

        // 5️⃣ Determine issue weight
        if ($lastProcess && !empty($lastProcess->return_weight)) {
            $issueWeight = $lastProcess->return_weight; // from process table
        } else {
            $issueWeight = $diamond->weight; // from diamond table
        }

        // Step 4: If all good, return success with diamond info
        // return response()->json([
        //     'status' => 'success',
        //     'data' => $diamond
        // ]);

        // 6️⃣ Return all details
        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $diamond->id,
                'barcode_number' => $diamond->barcode_number,
                'dimond_name' => $diamond->dimond_name,
                // 'status' => $diamond->status,
                // 'shape' => $diamond->shape,
                // 'weight' => $diamond->weight,
                'issue_weight' => $issueWeight, // ✅ your calculated issue weight
            ]
        ]);
    }

    public function storeBulkIssue(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'designation' => 'required',
            'worker_name' => 'required',
            'issue_date' => 'required|date',
            'diamonds' => 'required|array|min:1',
        ], [
            'category.required' => 'Please select a category.',
            'designation.required' => 'Please choose a designation.',
            'worker_name.required' => 'Please select a worker.',
            'issue_date.required' => 'Please select the issue date.',
            'diamonds.required' => 'Please scan at least one diamond before saving.',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->diamonds as $diamondId) {
                $issueWeight = $request->issue_weights[$diamondId] ?? null;
                $barcode = $request->barcode_number[$diamondId] ?? null;

                // 1️⃣ Create new process entry
                Process::create([
                    'dimonds_id' => $diamondId,
                    'dimonds_barcode' => $barcode,
                    'designation' => $request->designation,
                    'worker_name' => $request->worker_name,
                    'issue_date' => $request->issue_date,
                    'issue_weight' => $issueWeight,
                    // 'return_date' => null,
                    // 'return_weight' => null,
                    // 'status' => 'ISSUED',
                ]);

                // 2️⃣ Update diamond status
                // Dimond::where('id', $diamondId)->update(['status' => 'ISSUED']);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Diamonds issued successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error issuing diamonds: ' . $e->getMessage());
        }
    }

    public function bulkReturn()
    {
        return view('admin.reports.bulk_return');
    }

    public function checkDiamondStatusReturn(Request $request)
    {
        $barcode = $request->barcode;

        $diamond = Dimond::where('barcode_number', $barcode)->first();

        if (!$diamond) {
            return response()->json(['status' => 'error', 'message' => 'Diamond not found.']);
        }

        // get last process record
        $lastProcess = Process::where('dimonds_id', $diamond->id)
            ->orderBy('id', 'desc')
            ->first();

        // if never issued
        if (!$lastProcess || !empty($lastProcess->return_date) || !empty($lastProcess->return_weight)) {
            return response()->json(['status' => 'error', 'message' => 'This diamond is not issued.']);
        }

        // if issued in grading
        if (strtoupper($lastProcess->designation) === 'GRADING') {
            return response()->json(['status' => 'error', 'message' => 'This diamond is issued in grading process.']);
        }

        // prepare data for return table
        $data = [
            'id' => $diamond->id,
            'barcode_number' => $diamond->barcode_number,
            'dimond_name' => $diamond->dimond_name,
            'issue_weight' => $lastProcess->issue_weight,
        ];

        return response()->json(['status' => 'success', 'data' => $data]);
    }

    public function storeBulkReturn(Request $request)
    {
        $request->validate([
            'diamonds' => 'required|array|min:1',
            'return_dates' => 'required|array',
            'return_weights' => 'required|array',
        ]);

        // dd($request->all());

        DB::beginTransaction();
        try {
            foreach ($request->diamonds as $diamondId) {
                $returnDate = $request->return_dates[$diamondId] ?? now();
                $returnWeight = $request->return_weights[$diamondId] ?? null;

                $process = Process::where('dimonds_id', $diamondId)->orderBy('id', 'desc')->first();
                if (!$process) continue;

                $dimonds = Dimond::where('id', $process->dimonds_id)->first();
                $i_weight = $process->issue_weight;
                $r_weight = $returnWeight;

                $diffrence = $i_weight - $r_weight;
                $weight = $i_weight;

                $designation = Designation::where('name', $process->designation)->first();

                if ($designation->rate_apply_on == 'return_weight' || $designation->rate_apply_on == 'ready_to_ruff_weight') {
                    $weight = $r_weight;
                }
                if ($designation->rate_apply_on == 'diff_weight') {
                    $weight = $diffrence;
                }

                $rate_cut = $request->has('ratecut') ? (($request->ratecut != null) ? 1 : 0) : 0;
                $getWorker = Worker::where('fname', $process->worker_name)
                    ->where('designation', $process->designation)
                    ->first();

                $get_rate = 0;

                if ($dimonds->shape == 'Round') {
                    $WorkerRange = Workerrange::where('shape', 'Round')
                        ->where('min_value', '<', $weight)
                        ->where('max_value', '>=', $weight)
                        ->first();
                } else {
                    $WorkerRange = Workerrange::where('shape', 'Other')
                        ->where('min_value', '<', $weight)
                        ->where('max_value', '>=', $weight)
                        ->first();
                }

                if ($WorkerRange) {
                    $getkey = $WorkerRange->key;
                    $workerrate = WorkerRate::where('key', $getkey)
                        ->where('workers_id', $getWorker->id)
                        ->first();

                    if ($workerrate) {
                        $get_rate = $workerrate->value;
                    }
                    if ($get_rate == 0) {
                        $get_rate = $WorkerRange->value;
                    }
                }

                $getpdata = Process::where([
                    'dimonds_id' => $process->dimonds_id,
                    'designation' => $process->designation,
                ])->get();

                $datas = $getpdata->pluck('id');
                $currentIdIndex = $datas->search($process->id);
                $previousId = ($currentIdIndex !== false && $currentIdIndex > 0)
                    ? $datas->get($currentIdIndex - 1)
                    : null;
                $previousdata = $previousId ? Process::find($previousId) : null;

                if ($designation->rate_apply_on == 'ready_to_ruff_weight') {
                    $getFirstProcess = Process::where('dimonds_id', $process->dimonds_id)
                        ->where('designation', $process->designation)
                        ->first();
                    $weight = $getFirstProcess->issue_weight;
                }

                if ($rate_cut == 1) {
                    $price = 0;
                    Process::where(['dimonds_barcode' => $process->dimonds_barcode, 'worker_name' => $process->worker_name])
                        ->update(['ratecut' => 1]);
                } else {
                    $countprocess = Process::where([
                        'dimonds_id' => $process->dimonds_id,
                        'designation' => $process->designation,
                    ])->where('return_weight', '!=', '')->count();

                    $firstProcess = Process::where([
                        'dimonds_id' => $process->dimonds_id,
                        'designation' => $process->designation,
                    ])->where('return_weight', '!=', '')->first();

                    if ($countprocess == 0) {
                        $price = $weight * $get_rate;
                    } else {
                        if ($firstProcess && $firstProcess->id == $process->id) {
                            $price = $weight * $get_rate;
                        } else {
                            if (!empty($previousdata) && $previousdata->price == 0 && $previousdata->ratecut == 1) {
                                $price = $i_weight * $get_rate;
                            } else {
                                $price = 0;
                            }
                        }
                    }
                }

                $process->update([
                    'return_date' => $returnDate,
                    'return_weight' => $r_weight,
                    'price' => $price ?? 0,
                    'ratecut' => $rate_cut,
                ]);

                $check_process = Process::where(['dimonds_id' => $process->dimonds_id])
                    ->whereNull('return_weight')
                    ->count();

                if ($check_process == 0 && isset($r_weight) && $process->designation != 'Grading') {
                    $dimonds->update(['status' => 'Processing']);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Diamonds returned successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error while returning diamonds: ' . $e->getMessage());
        }
    }

    /**
     * Diamond Range Report
     * Show diamonds filtered by designation, worker, date range, and weight range
     */
    public function diamondRangeReport(Request $request)
    {
        $designations = Designation::get();
        $workers = Worker::where('is_active', 1)->get();

        $diamonds = [];

        if ($request->filled('designation') && $request->filled('start_date') && $request->filled('end_date')) {
            $designation = $request->input('designation');
            $worker_name = $request->input('worker_name');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $minRange = $request->input('min_range');
            $maxRange = $request->input('max_range');

            // Get all process records for selected designation and date range
            $query = Process::where('designation', $designation)
                ->whereDate('issue_date', '>=', $startDate)
                ->whereDate('issue_date', '<=', $endDate);

            // Filter by worker if selected and not "all"
            if ($worker_name && $worker_name != '' && $worker_name != 'all') {
                $query->where('worker_name', $worker_name);
            }

            $processRecords = $query->get();

            // Get diamond IDs from process records
            $diamondIds = $processRecords->pluck('dimonds_id')->unique()->toArray();

            // Get diamonds with their weights
            $diamondsQuery = Dimond::whereIn('id', $diamondIds);

            // Filter by weight range if provided
            if ($minRange !== '' && $minRange !== null) {
                $diamondsQuery->where('weight', '>=', $minRange);
            }
            if ($maxRange !== '' && $maxRange !== null) {
                $diamondsQuery->where('weight', '<=', $maxRange);
            }

            // Order by weight ascending
            $diamonds = $diamondsQuery->orderBy('weight', 'asc')->get();
        }

        return view('admin.reports.diamond_range_report', compact('designations', 'workers', 'diamonds'));
    }

    /**
     * Export Diamond Range Report
     * Export filtered diamonds to Excel
     */
    public function exportDiamondRangeReport(Request $request)
    {
        $designation = $request->input('designation');
        $worker_name = $request->input('worker_name');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $minRange = $request->input('min_range');
        $maxRange = $request->input('max_range');

        // Get all process records for selected designation and date range
        $query = Process::where('designation', $designation)
            ->whereDate('issue_date', '>=', $startDate)
            ->whereDate('issue_date', '<=', $endDate);

        // Filter by worker if selected and not "all"
        if ($worker_name && $worker_name != '' && $worker_name != 'all') {
            $query->where('worker_name', $worker_name);
        }

        $processRecords = $query->get();

        // Get diamond IDs from process records
        $diamondIds = $processRecords->pluck('dimonds_id')->unique()->toArray();

        // Get diamonds with their weights
        $diamondsQuery = Dimond::whereIn('id', $diamondIds);

        // Filter by weight range if provided
        if ($minRange !== '' && $minRange !== null) {
            $diamondsQuery->where('weight', '>=', $minRange);
        }
        if ($maxRange !== '' && $maxRange !== null) {
            $diamondsQuery->where('weight', '<=', $maxRange);
        }

        // Order by weight ascending
        $diamonds = $diamondsQuery->orderBy('weight', 'asc')->get();

        // Generate filename with timestamp
        $fileName = 'Diamond_Range_Report_' . now()->format('Y-m-d_H-i-s') . '.xlsx';

        return Excel::download(new DiamondRangeExport($diamonds), $fileName);
    }
}
