<?php

namespace App\Http\Controllers;

use App\Models\Daily;
use App\Models\Dimond;
use App\Models\Process;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AdminDailyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $this->syncDailyRecords();

        // $outerdesignation = Designation::where('category', 'Outter')->pluck('name')->toArray();
        $dailys = Daily::with('dimonds')
            ->whereHas('dimonds', function ($query) {
                $query->whereNotIn('status', ['Delivered', 'Completed', 'OutterProcessing'])
                    ->where('is_kp', 0);
            })
            ->orderByRaw('FIELD(status, 0, 1)')
            ->get();
        $dimondcount = Dimond::whereNotIn('status', ['Delivered', 'Completed', 'OutterProcessing'])->where('is_kp', 0)->count();
        $pendingcount = Dimond::where('status', 'Pending')->where('is_kp', 0)->count();
        $issuecount = Dimond::where('status', 'Processing')->where('is_kp', 0)->count();
        $outercount = Dimond::where('status', 'OutterProcessing')->where('is_kp', 0)->count();
        // $distinctDimondIds = Process::select('dimonds_id')->distinct()->pluck('dimonds_id')->toArray();
        // $getdimonds = Dimond::whereIn('id', $distinctDimondIds)->whereNotIn('status', ['Delivered', 'Completed', 'Pending'])->get();
        // $issuecount = 0;
        // $outercount = 0;
        // foreach ($getdimonds as $val) {
        //     $var = Process::where('dimonds_id', $val->id)->latest()->first();
        //     if (in_array($var->designation, $outerdesignation)) {
        //         if (empty($var->return_weight)) {
        //             $outercount += 1;
        //         } else {
        //             $issuecount += 1;
        //         }
        //     } else {
        //         $issuecount += 1;
        //     }
        // }

        $scancount = Daily::where('status', 1)->count();

        return view('admin.daily.index', compact('dailys', 'outercount', 'dimondcount', 'issuecount', 'pendingcount', 'scancount'));
    }

    protected function syncDailyRecords()
    {
        $activeDimonds = Dimond::whereNotIn('status', ['Delivered', 'Completed'])
            ->where('is_kp', 0)
            ->get();

        foreach ($activeDimonds as $dimond) {
            Daily::firstOrCreate(
                ['barcode' => $dimond->barcode_number],
                [
                    'dimonds_id' => $dimond->id,
                    'stage' => 'No',
                    'status' => 0,
                ]
            );
        }
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
        $barcode = $request->inputField;
        $barcodeDetail = Dimond::where('barcode_number', $barcode)->whereNotIn('status', ['Delivered', 'Completed'])->first();
        if (isset($barcodeDetail)) {
            $dailys = Daily::where('barcode', $barcode)->first();
            if (!isset($dailys)) {
                // Daily::create([
                //     'dimonds_id' => $barcodeDetail->id,
                //     'barcode' => $barcode,
                //     'stage' => 'Done',
                //     'status' => 1,
                // ]);
            } else {
                $outerdimond = Dimond::where('barcode_number', $barcode)->whereIn('status', ['OutterProcessing'])->first();
                if (isset($outerdimond)) {
                    return redirect()->back()->with('success', "This dimond in outter process");
                } else {
                    if ($dailys->status == 0) {
                        $status = 1;
                        $stage = 'Done';
                    } else {
                        // $status = 0;
                        // $stage = 'No';
                        return redirect()->back()->with('success', "Dimond already scanned");
                    }
                    $dailys->update([
                        'stage' => $stage,
                        'status' => $status,
                    ]);
                }
            }
            return redirect()->back()->with('success', "Add / Update Record Successfully");
        }
        return redirect()->back()->with('error', "Invalid Barcode");
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
        //
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
        $daily = Daily::findOrFail($id);
        $daily->delete();
        return Redirect::back()->with('success', "Delete Record Successfully");
    }

    public function statusUpdate($id)
    {
        $daily = Daily::findOrFail($id);
        if ($daily->status == 0) {
            $status = 1;
            $stage = 'Done';
        } else {
            $status = 0;
            $stage = 'No';
        }
        $daily->update([
            'stage' => $stage,
            'status' => $status,
        ]);
        return redirect()->back();
    }

    public function statusRefresh()
    {
        Daily::query()->delete();
        $dimonds = Dimond::whereNotIn('status', ['Delivered', 'Completed'])->get();
        foreach ($dimonds as $dimond) {
            // $daily = Daily::where('barcode', $dimond->barcode_number)->first();
            // if (!isset($daily)) {
            Daily::create([
                'dimonds_id' => $dimond->id,
                'barcode' => $dimond->barcode_number,
                'stage' => 'No',
                'status' => 0,
            ]);
            // }
        }
        // $dailies = Daily::get();
        // foreach ($dailies as $daily) {
        //     $daily->update(['status' => 0, 'stage' => 'No']);
        // }
        return redirect()->back()->with('success', "Refresh Successfully");
    }
}
