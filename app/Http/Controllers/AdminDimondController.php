<?php

namespace App\Http\Controllers;

use App\Models\Clarity;
use App\Models\Color;
use App\Models\Cut;
use App\Models\Company;
use Image;
use Validator;
use App\Models\Daily;
use App\Models\Party;
use App\Models\Dimond;
use App\Models\Repair;
use App\Models\Worker;
use App\Models\Process;
use App\Models\Designation;
use App\Models\Polish;
use App\Models\Shape;
use App\Models\Symmetry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AdminDimondController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Show only diamonds that are NOT marked as KP
        $dimonds = Dimond::where('is_kp', 0)->orderBy('id', 'DESC')->get();
        return view('admin.dimond.index', compact('dimonds'));
    }

    /**
     * Display KP (Keep Private) diamonds list
     *
     * @return \Illuminate\Http\Response
     */
    public function kpList()
    {
        // Show only diamonds that are marked as KP
        $dimonds = Dimond::where('is_kp', 1)->orderBy('id', 'DESC')->get();
        return view('admin.dimond.kp-list', compact('dimonds'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $shapes = Shape::get();
        $claritys = Clarity::get();
        $colors = Color::get();
        $cuts = Cut::get();
        $polishes = Polish::get();
        $symmetrys = Symmetry::get();
        $partys = Party::where('is_active', 1)->orderBy('id', 'DESC')->get();

        $company = Company::first();
        $year    = $company->series_year;   // 25
        $month   = $company->series_month;  // 01

        // 🔹 Find last diamond
        $lastDiamond = Dimond::where('year', $year)
            ->where('month', $month)
            ->orderByRaw('CAST(series_no AS UNSIGNED) DESC')
            ->first();

        $startSeries = $lastDiamond ? $lastDiamond->series_no + 1 : 1;

        return view('admin.dimond.create', compact('partys', 'shapes', 'claritys', 'colors', 'cuts', 'polishes', 'symmetrys', 'year', 'month', 'startSeries'));
    }

    public function store(Request $request)
    {

        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'parties_id' => 'required',
            'dimond_name' => ['required', 'array', 'max:255', 'unique:dimonds'],
            'janger_no' => 'required|array',
            'weight' => 'required|array',
            'required_weight' => 'required|array',
            'shape' => 'required|array',
            'clarity' => 'required|array',
            'color' => 'required|array',
            'cut' => 'required|array',
            'polish' => 'required|array',
            'symmetry' => 'required|array',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $company = Company::first();
        $year    = $company->series_year;   // 25
        $month   = $company->series_month;  // 01

        $lastDiamond = Dimond::where('year', $year)
            ->where('month', $month)
            ->orderByRaw('CAST(series_no AS UNSIGNED) DESC')
            ->first();

        $seriesNo = $lastDiamond ? $lastDiamond->series_no : 0;

        $savedDiamonds = [];

        // Loop through input arrays and create multiple records
        foreach ($request->janger_no as $index => $dimond_name) {

            $seriesNo = $seriesNo + 1;

            $stoneId = "GKD{$year}-{$month}-{$seriesNo}";

            $number = mt_rand(1000000000, 9999999999);

            while ($this->dimondCodeExists($number)) {
                $number = mt_rand(1000000000, 9999999999);
            }

            $urlToRedirect = route('admin.dimond.show', ['id' => $number]);
            $qrCode = QrCode::size(100)->generate($urlToRedirect);

            // Create the diamond record
            $dimond = Dimond::create([
                'parties_id' => $request->parties_id,
                'dimond_name' => $stoneId,
                'janger_no' => $request->janger_no[$index],
                'weight' => $request->weight[$index],
                'required_weight' => $request->required_weight[$index],
                'shape' => $request->shape[$index],
                'clarity' => $request->clarity[$index],
                'color' => $request->color[$index],
                'cut' => $request->cut[$index],
                'polish' => $request->polish[$index],
                'symmetry' => $request->symmetry[$index],
                'barcode' => $qrCode,
                'barcode_number' => $number,
                'status' => 'Pending',
                'year'            => $year,
                'month'           => $month,
                'series_no'       => $seriesNo,
            ]);

            $savedDiamonds[] = $dimond->id;

            // Insert into `daily` table
            Daily::create([
                'dimonds_id' => $dimond->id,
                'barcode' => $number,
                'stage' => 'No',
                'status' => 0,
            ]);
        }

        if ($request->input('action_type') == 'save_and_print') {

            $data = Dimond::whereIn('id', $savedDiamonds)->get();
            return view('admin.dimond.printSlip', compact('data'));
        }

        return redirect('admin/dimond')->with('success', "Add Record Successfully");
    }

    public function dimondCodeExists($number)
    {
        return Dimond::where('barcode_number', $number)->exists();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($barcode)
    {
        $designations = Designation::get();

        // Try to find diamond by ID first, then by barcode
        $barcodeDetail = Dimond::where('id', $barcode)
            ->orWhere('barcode_number', $barcode)
            ->first();

        if (!$barcodeDetail) {
            return redirect('admin/dimond')->withErrors(['error' => 'Diamond not found']);
        }

        $barcode_number = $barcodeDetail->barcode_number;
        $processes = Process::where('dimonds_barcode', $barcode_number)->get();
        $procee_return = Process::where('dimonds_barcode', $barcode_number)->where('return_weight', null)->first();
        $final_result = Process::where('dimonds_barcode', $barcode_number)->where('designation', 'Grading')->latest()
            ->first();
        $lastweight = Process::where('dimonds_barcode', $barcode_number)->orderBy('id', 'DESC')->first();

        return view('admin.dimond.show', compact('designations', 'barcodeDetail', 'processes', 'procee_return', 'final_result', 'lastweight'));
    }

    public function dimondDetail(Request $request)
    {
        $barcode = $request->inputField;
        $designations = Designation::get();
        // $barcodeDetail = Dimond::where('barcode_number', $barcode)->first();
        $barcodeDetail = Dimond::where('barcode_number', $barcode)
            ->orWhere('dimond_name', $barcode)
            ->first();
        if (isset($barcodeDetail)) {
            $barcode = $barcodeDetail->barcode_number;
            $processes = Process::where('dimonds_barcode', $barcode)->get();
            $procee_return = Process::where('dimonds_barcode', $barcode)->where('return_weight', null)->first();
            $final_result = Process::where('dimonds_barcode', $barcode)->where('designation', 'Grading')->latest()
                ->first();
            $lastweight = Process::where('dimonds_barcode', $barcode)->orderBy('id', 'DESC')->first();
            return view('admin.dimond.show', compact('designations', 'barcodeDetail', 'processes', 'procee_return', 'final_result', 'lastweight'));
        }
        // return redirect('admin/dimond')->withErrors(['error' => 'Barcode not found']);
        return Redirect::back()->withErrors(['error' => 'Invalid detail']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $shapes = Shape::get();
        $claritys = Clarity::get();
        $colors = Color::get();
        $cuts = Cut::get();
        $polishes = Polish::get();
        $symmetrys = Symmetry::get();
        $dimond = Dimond::findOrFail($id);
        $partys = Party::where('is_active', 1)->orderBy('id', 'DESC')->get();
        return view('admin.dimond.edit', compact('dimond', 'partys', 'shapes', 'claritys', 'colors', 'cuts', 'polishes', 'symmetrys'));
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
        $validator = Validator::make($request->all(), [
            'parties_id' => 'required',
            'dimond_name' => 'required',
            'janger_no' => 'required',
            'weight' => 'required',
            'required_weight' => 'required',
            'shape' => 'required',
            'clarity' => 'required',
            'color' => 'required',
            'cut' => 'required',
            'polish' => 'required',
            'symmetry' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $dimond = Dimond::findOrFail($id);
        $input = $request->all();
        $dimond->update($input);
        return redirect('admin/dimond')->with('success', "Update Record Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dimond = Dimond::find($id);

        if (!$dimond) {
            return back()->with('error', 'Diamond record not found');
        }

        DB::transaction(function () use ($id, $dimond) {
            Daily::where('dimonds_id', $id)->delete();
            Process::where('dimonds_id', $id)->delete();
            Repair::where('dimonds_id', $id)->delete();
            $dimond->delete();
        });

        return Redirect::back()->with('success', "Delete Record Successfully");
    }

    public function getWorkersByDesignation(Request $request)
    {
        $designation = $request->input('designation');
        $dimond = $request->input('dimond_id');
        $process_count = Process::where(['designation' => $designation, 'dimonds_id' => $dimond, 'ratecut' => 0])->count();
        $process = Process::where(['designation' => $designation, 'dimonds_id' => $dimond, 'ratecut' => 0])->first();
        $workers = Worker::where('is_active', 1)->where('designation', $designation)->get();

        if ($process_count != 0) {
            $workers = Worker::where('is_active', 1)->where(['designation' => $designation, 'fname' => $process->worker_name])->get();
        }

        return response()->json($workers);
    }

    public function getDesignationByCategory(Request $request)
    {
        $category = $request->input('category');

        $designations = Designation::get();

        if ($category != "all") {
            $designations = Designation::where('category', $category)->get();
        }

        return response()->json($designations);
    }

    public function getJangerListByParty(Request $request)
    {
        $partyId = $request->input('party');

        $jangerList = Dimond::select('janger_no')
            ->where('parties_id', $partyId)
            ->whereRaw("TRIM(janger_no) <> ''") // remove empty or whitespace only
            ->distinct()
            ->get();

        return response()->json($jangerList);
    }

    // public function updateStatus(Request $request)
    // {
    //     $dimond = Dimond::findOrFail($request->id);
    //     $dimond->update(['status' => $request->status]);
    //     return Redirect::back();
    // }

    public function hrDimond()
    {
        $deliveredDimonds = Dimond::where('status', 'Delivered')->orderBy('id', 'DESC')->get();
        $completedDimonds = Dimond::where('status', 'Completed')->orderBy('id', 'DESC')->get();
        $processingDimonds = Dimond::whereIn('status', ['Processing', 'OutterProcessing'])->orderBy('id', 'DESC')->get();
        $pendingDimonds = Dimond::where('status', 'Pending')->orderBy('id', 'DESC')->get();
        $repairDimonds = Repair::orderBy('id', 'DESC')->get();
        return view('admin.dimond.hrdimond', compact('deliveredDimonds', 'completedDimonds', 'processingDimonds', 'pendingDimonds', 'repairDimonds'));
    }

    public function transfer(Request $request)
    {
        $deliveredDimonds = Dimond::where('status', 'Delivered')->orderBy('id', 'DESC')->get();
        $completedDimonds = Dimond::where('status', 'Completed')->orderBy('id', 'DESC')->get();
        $processingDimonds = Dimond::whereIn('status', ['Processing', 'OutterProcessing'])->orderBy('id', 'DESC')->get();
        $pendingDimonds = Dimond::where('status', 'Pending')->orderBy('id', 'DESC')->get();
        $repairDimonds = Repair::orderBy('id', 'DESC')->get();

        $partyLists = Party::where('is_active', 1)->get();

        $party_id = $request->input('party_id');
        $diamondStatus = $request->input('diamond_status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $data = [];

        $query = Dimond::query();

        if ($party_id && $party_id !== 'All') {
            $query->where('parties_id', $party_id);
        }

        if ($diamondStatus) {
            $query->where('status', $diamondStatus);
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        if ($party_id) {
            $data = $query->orderBy('id', 'DESC')->get();
        }

        return view('admin.dimond.transfer', compact('deliveredDimonds', 'completedDimonds', 'processingDimonds', 'pendingDimonds', 'repairDimonds', 'partyLists', 'data'));
    }

    public function transferDiamonds(Request $request)
    {
        $request->validate([
            'selected_diamonds' => 'required|array',
            'selected_diamonds.*' => 'exists:dimonds,id',
        ], [
            'selected_diamonds.required' => 'Please select at least one diamond to download.',
            'selected_diamonds.*.exists' => 'One or more of the selected diamonds do not exist.',
        ]);

        // Get selected diamond IDs
        $selectedDiamonds = $request->input('selected_diamonds');

        $company = Company::first();

        Dimond::whereIn('id', $selectedDiamonds)->update(['status' => 'Delivered']);

        Dimond::whereIn('id', $selectedDiamonds)
            ->whereNull('delevery_date')
            ->update(['delevery_date' => now()]);

        $diamonds = Dimond::with('parties')->whereIn('id', $selectedDiamonds)->get();

        if ($request->input('del_and_pri') == '1') {
            return view('admin.expense.your-multi-print-view', compact('diamonds', 'company'));
        } else {
            return Redirect::back()->with('success', "Selected record delivered Successfully");
        }

        return Redirect::back()->with('error', "Sone thing wents wrong");
    }

    public function printImage($id)
    {
        $dimond = Dimond::where('id', $id)->first();

        return view('admin.dimond.pdfView', compact('dimond'));
    }

    public function importPage()
    {
        return view('admin.dimond.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx',
        ]);

        $path = $request->file('file')->store('temp');

        $data = Excel::toCollection(null, $path)->first();

        foreach ($data as $key => $row) {

            if ($key == 0) {
                continue;
            }

            // Check required fields - skip row if missing
            if (empty(trim($row[0])) || empty(trim($row[2])) || empty(trim($row[3])) || empty(trim($row[4]))) {
                continue;
            }

            if ($this->dimondCodeExists($row[11])) {
                continue;
            }

            try {
                Dimond::create([
                    'parties_id' => trim($row[0]),
                    'janger_no' => trim($row[1]) ?? '',
                    'dimond_name' => trim($row[2]),
                    'weight' => trim($row[3]),
                    'required_weight' => trim($row[4]),
                    'shape' => trim($row[5]) ?? '',
                    'clarity' => trim($row[6]) ?? '',
                    'color' => trim($row[7]) ?? '',
                    'cut' => trim($row[8]) ?? '',
                    'polish' => trim($row[9]) ?? '',
                    'symmetry' => trim($row[10]) ?? '',
                    'barcode_number' => trim($row[11]) ?? '',
                    'status' => 'Pending'
                ]);
            } catch (\Exception $e) {
                // Log the error and continue
                \Log::error('Error inserting row: ' . $e->getMessage());
                return back()->with('error', 'Something went to wrong');
            }
        }

        return back()->with('success', 'Diamonds imported successfully.');
    }

    public function selectedDesignation(Request $request, $type)
    {
        $dailys = Process::where('designation', $type)->whereNull('return_weight')->get();

        return view('admin.dimond.designationselected', compact('dailys'));
    }

    public function diamondProcessFlow(Request $request)
    {
        $partyLists = Party::where('is_active', 1)->get();
        $data = [];

        return view('admin.dimond.diamond_flow', compact('partyLists', 'data'));
    }

    public function diamondProcessFlowPdf(Request $request)
    {
        $partyLists = Party::where('is_active', 1)->get();
        $barcode = $request->input('barcode');
        $party_id = $request->input('party_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $diamonds = collect();

        if ($barcode) {
            $diamond = Dimond::where('barcode_number', $barcode)->first();
            if (!$diamond) {
                return response()->json(['error' => 'Barcode not found.'], 404);
            }
            $diamonds->push($diamond);
        } else {
            $query = Dimond::query();

            if ($party_id && $party_id !== 'All') {
                $query->where('parties_id', $party_id);
            }

            if ($startDate && $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }

            $diamonds = $query->orderBy('id', 'DESC')->get();
        }

        $data = [];

        foreach ($diamonds as $diamond) {
            $processes = Process::where('dimonds_id', $diamond->id)->get();
            $data[] = [
                'diamond' => $diamond,
                'processes' => $processes,
            ];
        }

        // $company = Company::first();

        // return view('admin.dimond.diamond_flow_pdf', compact('data', 'company'));
        return view('admin.dimond.diamond_flow', compact('data', 'partyLists'));
    }

    /**
     * Toggle KP status for a diamond
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function toggleKp($id)
    {
        $dimond = Dimond::findOrFail($id);
        $dimond->is_kp = !$dimond->is_kp;
        $dimond->save();

        $status = $dimond->is_kp ? 'marked as KP' : 'unmarked from KP';

        return redirect()->back()->with('success', 'Diamond ' . $status . ' successfully');
    }
}
