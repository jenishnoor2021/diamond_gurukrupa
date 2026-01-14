<?php

namespace App\Http\Controllers;

use Validator;
use App\Models\Worker;
use Illuminate\Http\Request;
use App\Models\WorkerBarcode;
use App\Models\WorkerAttendance;
use Illuminate\Support\Facades\Redirect;

class AdminWorkerBarcodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $barcodeLists = WorkerBarcode::orderBy('id', 'DESC')->get();
        $barcodeWorkerIds = WorkerBarcode::pluck('worker_id')->toArray();
        $workers = Worker::where('is_active', 1)
            ->whereNotIn('id', $barcodeWorkerIds)
            ->orderBy('id', 'DESC')
            ->get();

        return view('admin.worker_barcode.index', compact('workers', 'barcodeLists'));
    }

    public function printBarcode($id)
    {
        $barcode = WorkerBarcode::where('id', $id)->first();

        return view('admin.worker_barcode.pdfView', compact('barcode'));
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
        $validator = Validator::make($request->all(), [
            'worker_id' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $number = mt_rand(1000000000, 9999999999);

        if ($this->barCodeExists($number)) {
            $number = mt_rand(1000000000, 9999999999);
        }

        $request['barcode'] = $number;

        WorkerBarcode::create($request->all());

        return redirect('admin/worker-barcode')->with('success', "Genrate Barcode Successfully");
    }

    public function barCodeExists($number)
    {
        return WorkerBarcode::where('barcode', $number)->exists();
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
        $workerBarcode = WorkerBarcode::findOrFail($id);
        return view('admin.worker_barcode.edit', compact('workerBarcode'));
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
            'worker_id' => 'required',
            'barcode' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $barcodelist = WorkerBarcode::findOrFail($id);
        $barcodelist->update($request->all());

        return redirect('admin/worker-barcode')->with('success', "Update Record Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $barcodelist = WorkerBarcode::findOrFail($id);
        // WorkerAttendance::where('worker_id', $barcodelist->worker_id)->delete();
        $barcodelist->delete();

        return Redirect::back()->with('success', "Delete Record Successfully");
    }
}
