<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Worker;
use App\Models\WorkerRate;
use App\Models\Workerrange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DesignationWiseRate;
use Illuminate\Support\Facades\Redirect;

class AdminWorkerRangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workerranges = Workerrange::orderBy('id', 'DESC')->get();
        return view('admin.workerranges.index', compact('workerranges'));
    }

    public function getMinValue(Request $request)
    {
        $shape = $request->shape;

        $workerrange = Workerrange::where('shape', $shape)->orderBy('id', 'DESC')->first();

        $min_value = $workerrange ? $workerrange->max_value : 0;

        return response()->json(['min_value' => $min_value]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'shape'      => 'required',
            'min_value'  => 'required|numeric|min:0',
            'max_value'  => 'required|numeric|gt:min_value',
            'value'      => 'required|numeric|min:1'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($input)->withErrors($validator);
        }

        $workerrange = Workerrange::orderBy('id', 'DESC')->first();

        // Check if the record exists
        if ($workerrange) {
            $number = $workerrange->id + 1;
        } else {
            $number = 1;
        }

        // Set the key
        $input['key'] = 'key_' . $number;

        Workerrange::create($input);

        $workerLists = Worker::pluck('id'); // Fetch only IDs for better performance

        if ($workerLists->isNotEmpty()) {
            $workerRatesData = $workerLists->map(function ($workerid) use ($input) {
                return [
                    'workers_id' => $workerid,
                    'key' => $input['key'],
                    'value' => $input['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            DB::table('worker_rates')->insert($workerRatesData);
        }

        return redirect('admin/worker_range')->with('success', "Add Record Successfully");
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
        $workerR = Workerrange::findOrFail($id);
        $workerranges = Workerrange::orderBy('id', 'DESC')->get();
        return view('admin.workerranges.edit', compact('workerR', 'workerranges'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $workerRange = Workerrange::findOrFail($id);

        if (!$workerRange) {
            return redirect()->back()->with('error', "Record not found.");
        }

        // Get all keys that need to be deleted
        $keysToDelete = Workerrange::where('shape', $workerRange->shape)
            ->where('id', '>=', $workerRange->id)
            ->pluck('key')
            ->toArray();

        // Get the last ID for the specific shape
        $lastWorkerRange = Workerrange::where('shape', $workerRange->shape)
            ->orderBy('id', 'desc')
            ->first();

        if ($workerRange->id == $lastWorkerRange->id) {
            // If it's the last record, delete only this one
            $workerRange->delete();

            // Remove only related PartyRate
            WorkerRate::whereIn('key', [$workerRange->key])->delete();
            DesignationWiseRate::whereIn('range_key', [$workerRange->key])->delete();

            return redirect()->back()->with('success', "Deleted last record of shape {$workerRange->shape}.");
        } else {
            // If it's in between, delete all records with the same shape and greater than or equal to this ID
            Workerrange::where('shape', $workerRange->shape)
                ->where('id', '>=', $workerRange->id)
                ->delete();

            // Delete all PartyRate records that have matching keys
            WorkerRate::whereIn('key', $keysToDelete)->delete();
            DesignationWiseRate::whereIn('range_key', $keysToDelete)->delete();

            return redirect()->back()->with('success', "Deleted all records of shape {$workerRange->shape} after ID $id.");
        }
    }
}
