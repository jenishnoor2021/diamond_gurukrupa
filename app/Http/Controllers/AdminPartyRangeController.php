<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Party;
use App\Models\PartyRate;
use App\Models\Partyrange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class AdminPartyRangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partyranges = Partyrange::orderBy('id', 'DESC')->get();
        return view('admin.partyranges.index', compact('partyranges'));
    }

    public function getMinValue(Request $request)
    {
        $shape = $request->shape;

        $partyrange = Partyrange::where('shape', $shape)->orderBy('id', 'DESC')->first();

        $min_value = $partyrange ? $partyrange->max_value : 0;

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

        $partyrange = Partyrange::orderBy('id', 'DESC')->first();

        // Check if the record exists
        if ($partyrange) {
            $number = $partyrange->id + 1;
        } else {
            $number = 1;
        }

        $key = 'key_' . $number;

        // Set the key
        $input['key'] = $key;

        Partyrange::create($input);

        $partyLists = Party::where('is_active', 1)->pluck('id');

        if ($partyLists->isNotEmpty()) {
            $partyRatesData = $partyLists->map(function ($partyid) use ($input) {
                return [
                    'parties_id' => $partyid,
                    'key' => $input['key'],
                    'value' => $input['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->toArray();

            DB::table('party_rates')->insert($partyRatesData);
        }

        return redirect('admin/party_range')->with('success', "Add Record Successfully");
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
        $partyR = Partyrange::findOrFail($id);
        $partyranges = Partyrange::orderBy('id', 'DESC')->get();
        return view('admin.partyranges.edit', compact('partyR', 'partyranges'));
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
        $partyRange = Partyrange::findOrFail($id);

        if (!$partyRange) {
            return redirect()->back()->with('error', "Record not found.");
        }

        // Get all keys that need to be deleted
        $keysToDelete = Partyrange::where('shape', $partyRange->shape)
            ->where('id', '>=', $partyRange->id)
            ->pluck('key')
            ->toArray();

        // Get the last ID for the specific shape
        $lastPartyRange = Partyrange::where('shape', $partyRange->shape)
            ->orderBy('id', 'desc')
            ->first();

        if ($partyRange->id == $lastPartyRange->id) {
            // If it's the last record, delete only this one
            $partyRange->delete();

            // Remove only related PartyRate
            PartyRate::whereIn('key', [$partyRange->key])->delete();

            return redirect()->back()->with('success', "Deleted last record of shape {$partyRange->shape}.");
        } else {
            // If it's in between, delete all records with th    +e same shape and greater than or equal to this ID
            Partyrange::where('shape', $partyRange->shape)
                ->where('id', '>=', $partyRange->id)
                ->delete();

            // Delete all PartyRate records that have matching keys
            PartyRate::whereIn('key', $keysToDelete)->delete();

            return redirect()->back()->with('success', "Deleted all records of shape {$partyRange->shape} after ID $id.");
        }
    }
}
