<?php

namespace App\Http\Controllers;

use PDF;
use Validator;
use App\Models\Party;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Invoicedata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class AdminInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::orderBy('id', 'DESC')->get();
        return view('admin.invoice.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $partyes = Party::where('is_active', 1)->get();
        $companyes = Company::get();
        return view('admin.invoice.create', compact('partyes', 'companyes'));
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
            'parties_id' => 'required',
            'companies_id' => 'required',
            'invoice_date' => 'required',
            'invoice_no' => 'required',
            'place_to_supply' => 'required',
            'due_date' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $invoice = Invoice::create($request->all());

        return redirect('admin/invoice/edit/' . $invoice->id)->with('success', "Add Record Successfully");
    }

    public function storeInvoiceData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required',
            'item' => 'required',
            'hsn_no' => 'required',
            'tax' => 'required',
            'quntity' => 'required',
            'rate' => 'required',
            'per' => 'required',
            'total_amount' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }
        Invoicedata::create($request->all());

        $total = 0;

        $getdatas = Invoicedata::where('invoice_id', $request->invoice_id)->get();

        foreach ($getdatas as $getdata) {
            $total += $getdata->total_amount;
        }

        $invoice = Invoice::where('id', $request->invoice_id)->first();
        $invoice->update(['invoice_total' => $total]);

        return redirect('admin/invoice/edit/' . $invoice->id)->with('success', "Add Record Successfully");
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
        $invoice = Invoice::findOrFail($id);
        $invoicedatas = Invoicedata::where('invoice_id', $id)->get();
        $partyes = Party::get();
        $companyes = Company::get();
        return view('admin.invoice.edit', compact('invoice', 'partyes', 'companyes', 'invoicedatas'));
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
        // dd($request->all());
        $validator = Validator::make($request->all(), [
            'parties_id' => 'required',
            'companies_id' => 'required',
            'invoice_date' => 'required',
            'invoice_no' => 'required',
            'place_to_supply' => 'required',
            'due_date' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $invoice = Invoice::findOrFail($id);
        $input = $request->all();
        $invoice->update($input);

        return redirect('admin/invoice/edit/' . $invoice->id)->with('success', "update Record Successfully");
    }

    public function updateInvoiceData(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'invoice_id' => 'required',
            'item' => 'required',
            'hsn_no' => 'required',
            'tax' => 'required',
            'quntity' => 'required',
            'rate' => 'required',
            'per' => 'required',
            'total_amount' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $invoiceData = Invoicedata::findOrFail($id);
        $input = $request->all();
        $invoiceData->update($input);

        $total = 0;
        $getdatas = Invoicedata::where('invoice_id', $request->invoice_id)->get();

        foreach ($getdatas as $getdata) {
            $total += $getdata->total_amount;
        }

        $invoice = Invoice::where('id', $request->invoice_id)->first();
        $invoice->update(['invoice_total' => $total]);

        return redirect('admin/invoice/edit/' . $request->invoice_id)->with('success', "update Record Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        Invoicedata::where('invoice_id', $invoice->id)->delete();
        $invoice->delete();
        return Redirect::back()->with('success', "Delete Record Successfully");
    }

    public function destroyInvoiceData($id)
    {
        $invoicedata = Invoicedata::findOrFail($id);
        $invoicedata->delete();

        $total = 0;
        $getdatas = Invoicedata::where('invoice_id', $invoicedata->invoice_id)->get();

        foreach ($getdatas as $getdata) {
            $total += $getdata->total_amount;
        }

        $invoice = Invoice::where('id', $invoicedata->invoice_id)->first();
        $invoice->update(['invoice_total' => $total]);

        return redirect('admin/invoice/edit/' . $invoice->id)->with('success', "Add Record Successfully");
    }

    public function createPDF($id)
    {
        $invoice = Invoice::findOrFail($id);

        if ($invoice->file != '') {
            if (file_exists(public_path() . '/invoices/' . $invoice->file)) {
                unlink(public_path() . '/invoices/' . $invoice->file);
            }
        }

        $partyes = Party::where('id', $invoice->parties_id)->first();
        $companyes = Company::where('id', $invoice->companies_id)->first();

        $invoicedetail = Invoicedata::where('invoice_id', $id)->get();

        $data = [
            'invoice' => $invoice,
            'invoicedetail' => $invoicedetail,
            'partydetail' => $partyes,
            'companydetail' => $companyes
        ];

        $pdf = PDF::loadView('admin.invoice.invoice_pdf', $data);

        $fileName = time() . '-' . str_replace(' ', '-', $invoice->invoice_no) . '.pdf';
        $pdf->save(public_path('invoices/') . $fileName);

        $invoice->update(['file' => $fileName]);

        return redirect('admin/invoice/edit/' . $invoice->id)->with('success', "Generate Invoice Successfully");
    }
}
