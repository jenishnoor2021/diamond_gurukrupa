<?php

namespace App\Http\Controllers;

use App\Models\Daily;
use App\Models\Dimond;
use App\Models\Party;
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
    public function index(Request $request)
    {
        $partyId = $request->query('party_id');

        $baseDimondQuery = Dimond::whereNotIn('status', ['Delivered', 'Completed', 'OutterProcessing'])
            ->where('is_kp', 0);

        if ($partyId) {
            $baseDimondQuery->where('parties_id', $partyId);
        }

        $dailys = Daily::with('dimonds')
            ->whereHas('dimonds', function ($query) use ($partyId) {
                $query->whereNotIn('status', ['Delivered', 'Completed', 'OutterProcessing'])
                    ->where('is_kp', 0);
                if ($partyId) {
                    $query->where('parties_id', $partyId);
                }
            })
            ->orderByRaw('FIELD(status, 0, 1)')
            ->get();

        $dimondcount = (clone $baseDimondQuery)->count();
        $pendingcount = (clone $baseDimondQuery)->where('status', 'Pending')->count();
        $issuecount = (clone $baseDimondQuery)->where('status', 'Processing')->count();
        $outercount = (clone $baseDimondQuery)->where('status', 'OutterProcessing')->count();

        $scancountQuery = Daily::where('status', 1)->whereHas('dimonds', function ($query) use ($partyId) {
            $query->whereNotIn('status', ['Delivered', 'Completed', 'OutterProcessing'])
                ->where('is_kp', 0);
            if ($partyId) {
                $query->where('parties_id', $partyId);
            }
        });
        $scancount = $scancountQuery->count();

        $partyList = Party::withCount([
            'dimonds as total_diamonds' => function ($query) {
                $query->whereNotIn('status', ['Delivered', 'Completed', 'OutterProcessing'])
                    ->where('is_kp', 0);
            },
            'dimonds as scanned_diamonds' => function ($query) {
                $query->whereNotIn('status', ['Delivered', 'Completed', 'OutterProcessing'])
                    ->where('is_kp', 0)->whereHas('daily', function ($q) {
                        $q->where('status', 1);
                    });
            },
        ])->whereHas('dimonds', function ($query) {
            $query->whereNotIn('status', ['Delivered', 'Completed', 'OutterProcessing'])
                ->where('is_kp', 0);
        })->get();

        $selectedParty = $partyId ? Party::find($partyId) : null;

        return view('admin.daily.index', compact('dailys', 'outercount', 'dimondcount', 'issuecount', 'pendingcount', 'scancount', 'partyList', 'selectedParty'));
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
                        return redirect()->route('admin.daily-status.index', ['party_id' => $request->party_id])->with('success', "Dimond already scanned");
                    }
                    $dailys->update([
                        'stage' => $stage,
                        'status' => $status,
                    ]);
                }
            }
            return redirect()->route('admin.daily-status.index', ['party_id' => $request->party_id])->with('success', "Add / Update Record Successfully");
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
