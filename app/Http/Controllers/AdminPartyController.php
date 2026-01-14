<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Party;
use App\Models\Partyrange;
use App\Models\PartyRate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class AdminPartyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $partys = Party::orderBy('id', 'DESC')->get();
        return view('admin.party.index', compact('partys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roundpartyrangs = Partyrange::where('shape', 'Round')->get();
        $otherpartyrangs = Partyrange::where('shape', 'Other')->get();
        return view('admin.party.create', compact('roundpartyrangs', 'otherpartyrangs'));
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
            'fname' => 'required',
            'lname' => 'required',
            'party_code' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($input)->withErrors($validator);
        }

        // Remove unwanted keys (party_rates keys) from input
        $partyData = collect($input)->only(['fname', 'lname', 'party_code', 'address', 'mobile', 'gst_no'])->toArray();

        $party = Party::create($partyData);
        $party_id = $party->id;

        // Prepare data for PartyRate insert
        $partyRatesData = [];

        // Handle "Round" Shape Party Ranges
        $roundPartyRanges = Partyrange::where('shape', 'Round')->get();
        foreach ($roundPartyRanges as $roundPartyrange) {
            $partyRatesData[] = [
                'parties_id' => $party_id,
                'key' => $roundPartyrange->key,
                'value' => $input[$roundPartyrange->key] ?? $roundPartyrange->value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Handle "Other" Shape Party Ranges
        $otherPartyRanges = Partyrange::where('shape', 'Other')->get();
        foreach ($otherPartyRanges as $otherPartyrange) {
            $partyRatesData[] = [
                'parties_id' => $party_id,
                'key' => $otherPartyrange->key,
                'value' => $input[$otherPartyrange->key] ?? $otherPartyrange->value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert all data in party_rates table
        if (!empty($partyRatesData)) {
            DB::table('party_rates')->insert($partyRatesData);
        }

        return redirect('admin/party')->with('success', "Add Record Successfully");
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
        $party = Party::findOrFail($id);
        $partyrates = PartyRate::where('parties_id', $id)->get();

        // Map PartyRate values to keys
        $partyrateValues = $partyrates->pluck('value', 'key')->toArray();

        $roundpartyrangs = Partyrange::where('shape', 'Round')->get();
        $otherpartyrangs = Partyrange::where('shape', 'Other')->get();
        return view('admin.party.edit', compact('party', 'roundpartyrangs', 'otherpartyrangs', 'partyrateValues'));
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
        $party = Party::findOrFail($id);
        $input = $request->all();

        // Validation
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            'lname' => 'required',
            'party_code' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($input)->withErrors($validator);
        }

        // Remove unwanted keys (party_rates keys) from input
        $partyData = collect($input)->only(['fname', 'lname', 'party_code', 'address', 'mobile', 'gst_no'])->toArray();

        // Update Party Information
        $party->update($partyData);

        // **Remove all old party_rates related to this party**
        DB::table('party_rates')->where('parties_id', $id)->delete();

        // Prepare data for inserting new PartyRate records
        $partyRatesData = [];

        // Handle "Round" Shape Party Ranges
        $roundPartyRanges = Partyrange::where('shape', 'Round')->get();
        foreach ($roundPartyRanges as $roundPartyrange) {
            $key = $roundPartyrange->key;
            $value = $input[$key] ?? $roundPartyrange->value; // Default to '1' if not provided

            $partyRatesData[] = [
                'parties_id' => $party->id,
                'key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Handle "Other" Shape Party Ranges
        $otherPartyRanges = Partyrange::where('shape', 'Other')->get();
        foreach ($otherPartyRanges as $otherPartyRange) {
            $key = $otherPartyRange->key;
            $value = $input[$key] ?? $otherPartyRange->value; // Default to '1' if not provided

            $partyRatesData[] = [
                'parties_id' => $party->id,
                'key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // **Insert new PartyRate records**
        if (!empty($partyRatesData)) {
            DB::table('party_rates')->insert($partyRatesData);
        }

        return redirect('admin/party')->with('success', 'Party updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $party = Party::findOrFail($id);
        $party->delete();

        PartyRate::where('parties_id', $id)->delete();
        return Redirect::back()->with('success', "Delete Record Successfully");
    }

    public function partyActive($id)
    {
        $party = Party::where('id', $id)->first();
        if ($party->is_active == 1) {
            $party->is_active = 0;
        } else {
            $party->is_active = 1;
        }
        $party->save();
        return redirect()->back()->with('success', "Update Record Successfully");
    }
}
