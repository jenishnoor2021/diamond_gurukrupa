<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Worker;
use App\Models\Process;
use App\Models\WorkerRate;
use App\Models\Designation;
use App\Models\Workerrange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DesignationWiseRate;
use Illuminate\Support\Facades\Redirect;

class AdminWorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workers = Worker::orderBy('id', 'DESC')->get();
        return view('admin.worker.index', compact('workers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roundworkerrangs = Workerrange::where('shape', 'Round')->get();
        $otherworkerrangs = Workerrange::where('shape', 'Other')->get();
        $designations = Designation::get();
        return view('admin.worker.create', compact('designations', 'roundworkerrangs', 'otherworkerrangs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'fname' => [
                'required',
                'unique:workers,fname',
                'regex:/^\S+$/',
            ],
            'lname' => 'required',
            'designation' => 'required',
            // 'address' => 'required',
            // 'mobile' => 'required',
            // 'aadhar_no' => 'required',
        ], [
            'fname.regex' => 'The first name must not contain spaces.',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($input)->withErrors($validator);
        }

        // Remove unwanted keys (worker_rates keys) from input
        $workerData = collect($input)->only(['fname', 'lname', 'designation', 'address', 'mobile', 'aadhar_no', 'bank_name', 'ifsc_code', 'account_no', 'remark', 'account_holder_name'])->toArray();

        $worker = Worker::create($workerData);
        $worker_id = $worker->id;

        // Prepare data for Worker insert
        $workerRatesData = [];

        // Handle "Round" Shape Worker Ranges
        $roundWorkerRanges = Workerrange::where('shape', 'Round')->get();
        foreach ($roundWorkerRanges as $roundWorkerRange) {
            $workerRatesData[] = [
                'workers_id' => $worker_id,
                'key' => $roundWorkerRange->key,
                'value' => $input[$roundWorkerRange->key] ?? $roundWorkerRange->value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Handle "Other" Shape Worker Ranges
        $otherWorkerRanges = Workerrange::where('shape', 'Other')->get();
        foreach ($otherWorkerRanges as $otherWorkerRange) {
            $workerRatesData[] = [
                'workers_id' => $worker_id,
                'key' => $otherWorkerRange->key,
                'value' => $input[$otherWorkerRange->key] ?? $otherWorkerRange->value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert all data in worker_rates table
        if (!empty($workerRatesData)) {
            DB::table('worker_rates')->insert($workerRatesData);
        }

        return redirect('admin/worker')->with('success', "Add Record successfully");
    }

    public function workerExists($name)
    {
        return Worker::where('fname', $name)->exists();
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
        $worker = Worker::findOrFail($id);
        $designations = Designation::get();
        $workerrates = WorkerRate::where('workers_id', $id)->get();

        // Map PartyRate values to keys
        $workerrateValues = $workerrates->pluck('value', 'key')->toArray();

        $roundworkerrangs = Workerrange::where('shape', 'Round')->get();
        $otherworkerrangs = Workerrange::where('shape', 'Other')->get();
        return view('admin.worker.edit', compact('worker', 'designations', 'roundworkerrangs', 'otherworkerrangs', 'workerrateValues'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $worker = Worker::findOrFail($id);

        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'fname' => [
                'required',
                'unique:workers,fname,' . $id,
                'regex:/^\S+$/', // no spaces allowed
            ],
            'lname' => 'required',
            'designation' => 'required',
            // 'address' => 'required',
            // 'mobile' => 'required',
            // 'aadhar_no' => 'required',
        ], [
            'fname.regex' => 'The first name must not contain spaces.',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($input)->withErrors($validator);
        }

        // Remove unwanted keys (party_rates keys) from input
        $workerData = collect($input)->only(['fname', 'lname', 'designation', 'address', 'mobile', 'aadhar_no', 'bank_name', 'ifsc_code', 'account_no', 'remark', 'account_holder_name'])->toArray();

        Process::where('worker_name', $worker->fname)->update(['worker_name' => $input['fname']]);

        // Update Party Information
        $worker->update($workerData);

        // **Remove all old worker_rates related to this worker**
        DB::table('worker_rates')->where('workers_id', $id)->delete();

        // Prepare data for inserting new PartyRate records
        $workerRatesData = [];

        // Handle "Round" Shape Party Ranges
        $roundWorkewrRanges = Workerrange::where('shape', 'Round')->get();
        foreach ($roundWorkewrRanges as $roundWorkewrRange) {
            $key = $roundWorkewrRange->key;
            $value = $input[$key] ?? $roundWorkewrRange->value; // Default to '1' if not provided

            $workerRatesData[] = [
                'Workers_id' => $worker->id,
                'key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Handle "Other" Shape Party Ranges
        $otherWorkersRanges = Workerrange::where('shape', 'Other')->get();
        foreach ($otherWorkersRanges as $otherWorkersRange) {
            $key = $otherWorkersRange->key;
            $value = $input[$key] ?? $otherWorkersRange->value; // Default to '1' if not provided

            $workerRatesData[] = [
                'Workers_id' => $worker->id,
                'key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // **Insert new PartyRate records**
        if (!empty($workerRatesData)) {
            DB::table('worker_rates')->insert($workerRatesData);
        }

        return redirect('admin/worker')->with('success', "update Record successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $worker = Worker::findOrFail($id);
        $worker->delete();

        WorkerRate::where('workers_id', $id)->delete();
        return Redirect::back()->with('success', "Delete Record Successfully");
    }

    public function workerActive($id)
    {
        $worker = Worker::where('id', $id)->first();
        if ($worker->is_active == 1) {
            $worker->is_active = 0;
        } else {
            $worker->is_active = 1;
        }
        $worker->save();
        return redirect()->back()->with('success', "update Record Successfully");
    }

    public function getRates(Request $request)
    {
        $designation = $request->get('designation');

        $designationData = Designation::where('name', $designation)->first();

        if (!$designationData) {
            return response()->json([]);
        }

        $rates = DesignationWiseRate::where('designation_id', $designationData->id)->pluck('value', 'range_key');

        return response()->json($rates);
    }
}
