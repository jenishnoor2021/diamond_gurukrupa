<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Dimond;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class AdminCompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $comapyes = Company::orderBy('id', 'DESC')->get();
        return view('admin.company.index', compact('comapyes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.company.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'gst_no' => 'required',
            'pan_no' => 'required',
            'email' => 'required',
            'contact' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $name = null;
        if ($file = $request->file('sign')) {

            $str = $file->getClientOriginalName();
            $str = str_replace(' ', '_', $str);

            $name = time() . $str;

            $file->move('companysign', $name);
        }

        $bank_info  = [
            'bank_name' => $request->bank_name ?? '',
            'account_no' => $request->account_no ?? '',
            'ifsc_code' => $request->ifsc_code ?? '',
            'branch' => $request->branch ?? '',
        ];

        $companyData = $request->except(['bank_name', 'account_no', 'ifsc_code', 'branch']);
        $companyData['sign'] = $name;
        $companyData['bank_info'] = json_encode($bank_info);

        Company::create($companyData);

        return redirect('admin/company')->with('success', "Add Record Successfully");
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
        $company = Company::findOrFail($id);
        return view('admin.company.edit', compact('company'));
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
            'name' => 'required',
            'gst_no' => 'required',
            'pan_no' => 'required',
            'email' => 'required',
            'contact' => 'required',
            'address' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $company = Company::findOrFail($id);

        if ($file = $request->file('sign')) {

            $str = $file->getClientOriginalName();
            $str = str_replace(' ', '_', $str);

            $name = time() . $str;

            $file->move('companysign', $name);

            if ($company->sign != '/companysign/') {
                if (file_exists(public_path() . $company->sign)) {
                    unlink(public_path() . $company->sign);
                }
            }

            $company->update([
                'sign' => $name ?? '',
            ]);
        }

        $input = $request->all();

        $bank_info  = [
            'bank_name' => $request->bank_name ?? '',
            'account_no' => $request->account_no ?? '',
            'ifsc_code' => $request->ifsc_code ?? '',
            'branch' => $request->branch ?? '',
        ];

        $input = $request->except(['bank_name', 'account_no', 'ifsc_code', 'branch', 'sign']);
        $input['bank_info'] = json_encode($bank_info);

        $company->update($input);
        return redirect('admin/company')->with('success', "Update Record Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();
        return Redirect::back()->with('success', "Delete Record Successfully");
    }

    public function serieUpdate(Request $request)
    {
        $company = Company::first();

        // Default values from company table
        $year  = $request->series_year  ?? $company->series_year;
        $month = $request->series_month ?? $company->series_month;

        $sampleStoneId = '';

        if ($year && $month) {

            // Update company when request values exist
            if ($request->filled('series_year') && $request->filled('series_month')) {
                $company->update([
                    'series_year'  => $year,
                    'series_month' => $month,
                ]);
            }

            // Get last diamond for selected year & month
            $lastDiamond = Dimond::where('year', $year)
                ->where('month', $month)
                ->latest('id')
                ->first();

            $nextCount = $lastDiamond ? $lastDiamond->series_no + 1 : 1;

            // Generate Sample Stone ID
            $sampleStoneId = "GKD{$year}-{$month}-{$nextCount}";
        }

        return view('admin.company.series_update', compact(
            'company',
            'sampleStoneId',
            'year',
            'month'
        ));
    }
}
