<?php

namespace App\Http\Controllers;

use PDF;
use TCPDF;
use Validator;
use Carbon\Carbon;
use App\Models\Daily;
use App\Models\Party;
use App\Models\Dimond;
use App\Models\Repair;
use App\Models\Worker;
use App\Models\Company;
use App\Models\Expence;
use App\Models\Process;
use App\Models\Designation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
// use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DiamondSlipStyledExport;
use App\Exports\DiamondSlipJangerStyledExport;

class AdminExpenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenses = Expence::orderBy('id', 'DESC')->get();
        return view('admin.expense.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.expense.create');
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
            'title' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        Expence::create($request->all());
        return redirect('admin/expense')->with('success', "Add Record Successfully");
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
        $expense = Expence::findOrFail($id);
        return view('admin.expense.edit', compact('expense'));
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
            'title' => 'required',
            'amount' => 'required',
            'date' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $expense = Expence::findOrFail($id);
        $input = $request->all();
        $expense->update($input);
        return redirect('admin/expense')->with('success', "Update Record Successfully");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $expense = Expence::findOrFail($id);
        $expense->delete();
        return Redirect::back()->with('success', "Delete Record Successfully");
    }

    public function updateStatus(Request $request)
    {
        $expense = Expence::findOrFail($request->id);
        $expense->update(['status' => $request->status]);
        return Redirect::back()->with('success', "Update Status Successfully");
    }

    public function report(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $data = [];

        if (isset($startDate) && isset($endDate)) {
            $data = $this->getDataForPdf($startDate, $endDate);
        }

        return view('admin.expense.expense-report', compact('data'));
    }

    public function generatePdf(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $company = Company::first();

        $data = $this->getDataForPdf($startDate, $endDate);

        $pdf = PDF::loadView('admin.expense.expense-pdf-template', compact('data', 'company'));

        if ($request->input('action') === 'view') {
            return $pdf->stream('expanse-report.pdf');
        } elseif ($request->input('action') === 'download') {
            // Session::flash('success', "Download Record Successfully");
            return $pdf->download('expanse-report.pdf');
        }

        return redirect('/admin/report')->with('error', 'Invalid action');
    }

    public function workerIssueReport(Request $request)
    {
        $designations = Designation::get();

        $designation = $request->input('designation');
        $worker_name = $request->input('worker_name');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $category = $request->input('category');

        $worker_detail = [];
        $data = [];

        if (isset($startDate) && isset($endDate)) {
            if (isset($category) && $category != 'all') {
                $categorydesignation = Designation::where('category', $category)->pluck('name')->toArray();
                if ($designation != 'all') {
                    if ($worker_name != 'all') {
                        $worker_detail = Worker::where(['designation' => $designation, 'fname' => $worker_name, 'is_active' => 1])->get();
                        $data = Process::whereDate('return_date', '>=', $startDate)->whereDate('return_date', '<=', $endDate)->whereNotNull('return_weight')->where(['designation' => $designation, 'worker_name' => $worker_name])->get();
                    } else {
                        $worker_detail = Worker::where('designation', $designation)->where('is_active', 1)->get();
                        $data = Process::whereDate('return_date', '>=', $startDate)->whereDate('return_date', '<=', $endDate)->whereNotNull('return_weight')->where(['designation' => $designation])->get();
                    }
                } else {
                    $worker_detail = Worker::whereIn('designation', $categorydesignation)->where('is_active', 1)->get();
                    $data = Process::whereDate('return_date', '>=', $startDate)->whereDate('return_date', '<=', $endDate)->whereNotNull('return_weight')->whereIn('designation', $categorydesignation)->get();
                }
            } else {
                if (isset($designation) && $designation != 'all') {
                    if ($worker_name != 'all') {
                        $worker_detail = Worker::where(['designation' => $designation, 'fname' => $worker_name, 'is_active' => 1])->get();
                        $data = Process::whereDate('return_date', '>=', $startDate)->whereDate('return_date', '<=', $endDate)->whereNotNull('return_weight')->where(['designation' => $designation, 'worker_name' => $worker_name])->get();
                    } else {
                        $worker_detail = Worker::where('designation', $designation)->where('is_active', 1)->get();
                        $data = Process::whereDate('return_date', '>=', $startDate)->whereDate('return_date', '<=', $endDate)->whereNotNull('return_weight')->where(['designation' => $designation])->get();
                    }
                } else {
                    if (isset($startDate) && isset($endDate)) {
                        $worker_detail = Worker::where('is_active', 1)->get();
                        $data = Process::whereDate('return_date', '>=', $startDate)->whereDate('return_date', '<=', $endDate)->whereNotNull('return_weight')->get();
                    }
                }
            }
        }

        // dd($data);

        return view('admin.reports.worker_issue_report', compact('designations', 'data', 'worker_detail'));
    }

    public function generateWorkerIssuePdf(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
            'designation' => 'required',
            'worker_name' => 'required',
            'category' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $designation = $request->input('designation');
        $worker_name = $request->input('worker_name');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $category = $request->input('category');

        $worker_detail = [];
        $data = [];

        if (isset($startDate) && isset($endDate)) {
            if (isset($category) && $category != 'all') {
                $categorydesignation = Designation::where('category', $category)->pluck('name')->toArray();
                if ($designation != 'all') {
                    if ($worker_name != 'all') {
                        $worker_detail = Worker::where(['designation' => $designation, 'fname' => $worker_name, 'is_active' => 1])->get();
                        $data = Process::whereDate('return_date', '>=', $startDate)->whereDate('return_date', '<=', $endDate)->whereNotNull('return_weight')->where(['designation' => $designation, 'worker_name' => $worker_name])->get();
                    } else {
                        $worker_detail = Worker::where('designation', $designation)->where('is_active', 1)->get();
                        $data = Process::whereDate('return_date', '>=', $startDate)->whereDate('return_date', '<=', $endDate)->whereNotNull('return_weight')->where(['designation' => $designation])->get();
                    }
                } else {
                    $worker_detail = Worker::whereIn('designation', $categorydesignation)->where('is_active', 1)->get();
                    $data = Process::whereDate('return_date', '>=', $startDate)->whereDate('return_date', '<=', $endDate)->whereNotNull('return_weight')->whereIn('designation', $categorydesignation)->get();
                }
            } else {
                if (isset($designation) && $designation != 'all') {
                    if ($worker_name != 'all') {
                        $worker_detail = Worker::where(['designation' => $designation, 'fname' => $worker_name, 'is_active' => 1])->get();
                        $data = Process::whereDate('return_date', '>=', $startDate)->whereDate('return_date', '<=', $endDate)->whereNotNull('return_weight')->where(['designation' => $designation, 'worker_name' => $worker_name])->get();
                    } else {
                        $worker_detail = Worker::where('designation', $designation)->where('is_active', 1)->get();
                        $data = Process::whereDate('return_date', '>=', $startDate)->whereDate('return_date', '<=', $endDate)->whereNotNull('return_weight')->where(['designation' => $designation])->get();
                    }
                } else {
                    if (isset($startDate) && isset($endDate)) {
                        $worker_detail = Worker::where('is_active', 1)->get();
                        $data = Process::whereDate('return_date', '>=', $startDate)->whereDate('return_date', '<=', $endDate)->whereNotNull('return_weight')->get();
                    }
                }
            }
        }

        $company = Company::first();

        $pdf = PDF::loadView('admin.reports.worker_issue_pdf_template', compact('data', 'worker_name', 'worker_detail', 'company'));
        // Session::flash('success', "Download Record Successfully");
        return $pdf->download('generate-' . $worker_name . '-report.pdf');
    }

    private function getDataForPdf($startDate, $endDate)
    {
        $data = Expence::whereDate('date', '>=', $startDate)->whereDate('date', '<=', $endDate)->get();
        return $data;
    }

    public function printSlipe(Request $request, $id)
    {
        $dimond = Dimond::findOrFail($id);
        $dimond->update(['status' => 'Delivered']);

        if ($dimond->delevery_date == '') {
            $dimond->update(['delevery_date' => today()]);
        }

        $get_dimond_detail = Dimond::where('id', $id)->first();
        $data = $get_dimond_detail;

        $company = Company::first();

        $pdf = PDF::loadView('admin.expense.your-pdf-view', compact('data', 'company'));

        if ($request->input('action') === 'download') {
            return $pdf->download('generated-' . $dimond->dimond_name . '-pdf.pdf');
        }

        return view('admin.expense.your-print-view', ['pdf' => $pdf, 'data' => $data, 'company' => $company]);

        // $pdf = new TCPDF();
        // $pdf->AddPage();
        // $pdf->SetFont('times', '', 12);
        // $html = view("admin.expense.your-pdf-view", compact('data'));
        // $pdf->writeHTML($html, true, false, true, false, '');
        // return $pdf->Output('generated-' . $dimond->dimond_name . '-pdf.pdf', 'I');
    }

    // public function workerReport(Request $request)
    // {
    //     $designations = Designation::get();
    //     return view('admin.reports.worker_report', compact('designations'));
    // }

    public function workerReport(Request $request)
    {
        $designations = Designation::get();

        $designation = $request->input('designation');
        $worker_name = $request->input('worker_name');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $category = $request->input('category');
        $whichDiamond = $request->input('which_diamond');

        $worker_detail = [];
        $data = [];
        $getdeliverddimonds = [];

        if (isset($startDate) && isset($endDate)) {
            if ($category == 'Outter') {
                $getdeliverddimonds = Dimond::whereDate('updated_at', '>=', $startDate)->whereDate('updated_at', '<=', $endDate)->pluck('id')->toArray();
            }
            if ($category != 'Outter') {
                if ($whichDiamond == 'updated_at') {
                    $getdeliverddimonds = Dimond::whereDate($whichDiamond, '>=', $startDate)->whereDate($whichDiamond, '<=', $endDate)->pluck('id')->toArray();
                }
                if ($whichDiamond == 'delevery_date') {
                    $getdeliverddimonds = Dimond::where('status', 'Delivered')->whereDate($whichDiamond, '>=', $startDate)->whereDate($whichDiamond, '<=', $endDate)->pluck('id')->toArray();
                }
            }
        }

        if (isset($getdeliverddimonds)) {
            if (isset($category) && $category != 'all') {
                $categorydesignation = Designation::where('category', $category)->pluck('name')->toArray();
                if ($designation != 'all') {
                    if ($worker_name != 'all') {
                        $worker_detail = Worker::where(['designation' => $designation, 'fname' => $worker_name, 'is_active' => 1])->get();
                        $data = Process::whereIn('dimonds_id', $getdeliverddimonds)->where(['designation' => $designation, 'worker_name' => $worker_name])->get();
                    } else {
                        $worker_detail = Worker::where('designation', $designation)->where('is_active', 1)->get();
                        $data = Process::whereIn('dimonds_id', $getdeliverddimonds)->where(['designation' => $designation])->get();
                    }
                } else {
                    $worker_detail = Worker::whereIn('designation', $categorydesignation)->where('is_active', 1)->get();
                    $data = Process::whereIn('dimonds_id', $getdeliverddimonds)->whereIn('designation', $categorydesignation)->get();
                }
            } else {
                if (isset($designation) && $designation != 'all') {
                    if ($worker_name != 'all') {
                        $worker_detail = Worker::where(['designation' => $designation, 'fname' => $worker_name, 'is_active' => 1])->get();
                        $data = Process::whereIn('dimonds_id', $getdeliverddimonds)->where(['designation' => $designation, 'worker_name' => $worker_name])->get();
                    } else {
                        $worker_detail = Worker::where('designation', $designation)->where('is_active', 1)->get();
                        $data = Process::whereIn('dimonds_id', $getdeliverddimonds)->where(['designation' => $designation])->get();
                    }
                } else {
                    if (isset($startDate) && isset($endDate)) {
                        $worker_detail = Worker::where('is_active', 1)->get();
                        $data = Process::whereIn('dimonds_id', $getdeliverddimonds)->get();
                    }
                }
            }
        }

        return view('admin.reports.worker_report', compact('designations', 'data', 'worker_detail'));
    }

    public function generateWorkerPdf(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
            'designation' => 'required',
            'worker_name' => 'required',
            'category' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $designation = $request->input('designation');
        $worker_name = $request->input('worker_name');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $category = $request->input('category');
        $whichDiamond = $request->input('which_diamond');

        $worker_detail = [];
        $data = [];
        $whichDiamond = $request->input('which_diamond');

        if (isset($startDate) && isset($endDate)) {
            if ($category == 'Outter') {
                $getdeliverddimonds = Dimond::whereDate('updated_at', '>=', $startDate)->whereDate('updated_at', '<=', $endDate)->pluck('id')->toArray();
            }
            if ($category != 'Outter') {
                if ($whichDiamond == 'updated_at') {
                    $getdeliverddimonds = Dimond::whereDate($whichDiamond, '>=', $startDate)->whereDate($whichDiamond, '<=', $endDate)->pluck('id')->toArray();
                }
                if ($whichDiamond == 'delevery_date') {
                    $getdeliverddimonds = Dimond::where('status', 'Delivered')->whereDate($whichDiamond, '>=', $startDate)->whereDate($whichDiamond, '<=', $endDate)->pluck('id')->toArray();
                }
            }
        }

        if ($category != 'all') {
            $categorydesignation = Designation::where('category', $category)->pluck('name')->toArray();
            if ($designation != 'all') {
                if ($worker_name != 'all') {
                    $worker_detail = Worker::where(['designation' => $designation, 'fname' => $worker_name, 'is_active' => 1])->get();
                    $data = Process::whereIn('dimonds_id', $getdeliverddimonds)->where(['designation' => $designation, 'worker_name' => $worker_name])->get();
                } else {
                    $worker_detail = Worker::where('designation', $designation)->where('is_active', 1)->get();
                    $data = Process::whereIn('dimonds_id', $getdeliverddimonds)->where(['designation' => $designation])
                        ->get();
                }
            } else {
                $worker_detail = Worker::whereIn('designation', $categorydesignation)->where('is_active', 1)->get();
                $data = Process::whereIn('dimonds_id', $getdeliverddimonds)->whereIn('designation', $categorydesignation)->get();
            }
        } else {
            if ($designation != 'all') {
                if ($worker_name != 'all') {
                    $worker_detail = Worker::where(['designation' => $designation, 'fname' => $worker_name, 'is_active' => 1])->get();
                    $data = Process::whereIn('dimonds_id', $getdeliverddimonds)->where(['designation' => $designation, 'worker_name' => $worker_name])->get();
                } else {
                    $worker_detail = Worker::where('designation', $designation)->where('is_active', 1)->get();
                    $data = Process::whereIn('dimonds_id', $getdeliverddimonds)->where(['designation' => $designation])->get();
                }
            } else {
                $worker_detail = Worker::where('is_active', 1)->get();
                $data = Process::whereIn('dimonds_id', $getdeliverddimonds)->get();
            }
        }

        $company = Company::first();

        $pdf = PDF::loadView('admin.reports.worker_pdf_template', compact('data', 'worker_name', 'worker_detail', 'company'));
        // Session::flash('success', "Download Record Successfully");
        return $pdf->download('generate-' . $worker_name . '-report.pdf');
    }

    public function partyReport(Request $request)
    {
        $partyLists = Party::where('is_active', 1)->get();
        return view('admin.reports.party_report', compact('partyLists'));
    }

    public function partyBill(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'party_id' => 'required',
            'end_date' => 'required',
            'start_date' => 'required',
            'add_gst' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $partyId = $request->input('party_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $getGst = $request->input('add_gst');

        $company = Company::first();

        $party = Party::where('id', $partyId)->first();
        $data = Dimond::where(['parties_id' => $partyId, 'status' => 'Delivered'])->whereDate('delevery_date', '>=', $startDate)->whereDate('delevery_date', '<=', $endDate)->get();

        $pdf = PDF::loadView('admin.reports.party_bill_template', compact('data', 'party', 'getGst', 'company'));
        // Session::flash('success', "Download Record Successfully");
        return $pdf->download('generate-' . $party->fname . '-report.pdf');
    }

    public function partyBillExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'party_id' => 'required',
            'end_date' => 'required',
            'start_date' => 'required',
            'add_gst' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }

        $partyId = $request->input('party_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $getGst = $request->input('add_gst');

        $party = Party::where('id', $partyId)->first();
        $data = Dimond::where(['parties_id' => $partyId, 'status' => 'Delivered'])->whereDate('delevery_date', '>=', $startDate)->whereDate('delevery_date', '<=', $endDate)->get();

        $spreadsheet = new Spreadsheet();

        // Get the active sheet
        $sheet = $spreadsheet->getActiveSheet();

        $title = 'DHYANI IMPEX';
        $titleCell = 'A1:L1';
        $sheet->setCellValue('A1', $title);
        $sheet->mergeCells($titleCell);
        $style = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ];
        $sheet->getStyle($titleCell)->applyFromArray($style);

        // Add data to the sheet (example data)
        $sheet->setCellValue('A2', 'Sr.');
        $sheet->setCellValue('B2', 'Stone Id');
        $sheet->setCellValue('C2', 'RW');
        $sheet->setCellValue('D2', 'PW');
        $sheet->setCellValue('E2', 'SHP');
        $sheet->setCellValue('F2', 'CL');
        $sheet->setCellValue('G2', 'PRT');
        $sheet->setCellValue('H2', 'CUT');
        $sheet->setCellValue('I2', 'PL');
        $sheet->setCellValue('J2', 'STN');
        $sheet->setCellValue('K2', 'Amount');
        $sheet->setCellValue('L2', 'Created');

        $excelData = [];

        $sum = $sgst = $cgst = 0;
        foreach ($data as $key => $da) {
            $process = Process::where(['designation' => 'Grading', 'dimonds_id' => $da->id])->first();
            // Populate your data here
            $excelData[] = [
                $key + 1,
                $da->dimond_name,
                $da->weight,
                isset($process->return_weight) ? $process->return_weight : '',
                isset($da->shape) ? $da->shape : '',
                isset($process->r_clarity) ? $process->r_clarity : '',
                isset($process->r_color) ? $process->r_color : '',
                isset($process->r_cut) ? $process->r_cut : '',
                isset($process->r_polish) ? $process->r_polish : '',
                isset($process->r_symmetry) ? $process->r_symmetry : '',
                $da->amount,
                $da->created_at,
            ];
            $sum += $da->amount;
        }

        $excelData[] = ['', '', '', '', '', '', '', '', '', 'Total =', $sum, ''];
        if ($getGst == 'gst') {
            $sgst = ($sum * 1.5) / 100;
            $cgst = ($sum * 1.5) / 100;
            $excelData[] = ['', '', '', '', '', '', '', '', '', 'SGST (1.5 %) =', $sgst, ''];
            $excelData[] = ['', '', '', '', '', '', '', '', '', 'CGST (1.5 %) =', $cgst, ''];
        } else {
        }
        $excelData[] = ['', '', '', '', '', '', '', '', '', 'Final Amount =', $sum + $sgst + $cgst, ''];

        $rowIndex = 3;
        // Add data rows
        foreach ($excelData as $row) {
            $columnIndex = 1;
            foreach ($row as $value) {
                $sheet->setCellValueByColumnAndRow($columnIndex, $rowIndex, $value);
                $columnIndex++;
            }
            $rowIndex++;
        }

        // Create a new CSV writer object
        $writer = new Csv($spreadsheet);

        // Set the headers to force download the file
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="example.csv"',
        ];

        // Create the HTTP response and send the file to the client
        return new Response($writer->save('php://output'), 200, $headers);


        // below code is use for live server
        // header('Content-Type: text/csv');
        // header('Content-Disposition: attachment; filename="example.csv"');
        // header('Cache-Control: max-age=0');

        // $writer->save('php://output');
        // exit;
    }

    public function partyFilter(Request $request)
    {
        $partyLists = Party::where('is_active', 1)->get();
        $partyId = $request->input('party_id');
        $status = $request->input('status');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $minWeight = $request->input('min_weight');
        $maxWeight = $request->input('max_weight');
        // $outerProcess = $request->input('process');
        $dimondsQuery = Dimond::query();
        if (isset($partyId) && $partyId != 'All') {
            $dimondsQuery->where('parties_id', $partyId);
            $partyLists = Party::where('id', $partyId)->where('is_active', 1)->get();
        }

        if (!empty($status)) {
            $dimondsQuery->whereIn('status', $status);
        }
        if (isset($startDate)) {
            if (isset($endDate)) {
                // If both start and end dates are provided
                $dimondsQuery->whereDate('delevery_date', '>=', $startDate)->whereDate('delevery_date', '<=', $endDate);
            } else {
                // If only start date is provided
                $dimondsQuery->where('delevery_date', '>=', $startDate);
            }
        }

        if (isset($minWeight) && $minWeight != '') {
            $dimondsQuery->where('weight', '>=', $minWeight);
        }
        if (isset($maxWeight) && $maxWeight != '') {
            $dimondsQuery->where('weight', '<=', $maxWeight);
        }

        $dimonds = $partyId ? $dimondsQuery->get() : [];

        return view('admin.reports.party_filter', compact('partyLists', 'dimonds'));
    }

    public function repair(Request $request, $id)
    {
        // $check_exist = Repair::where('dimonds_id', $id)->get();
        $dimond = Dimond::where('id', $id)->first();
        $daily = Daily::where('barcode', $dimond->barcode_number)->first();

        // if (count($check_exist) == 0) {
        Repair::create([
            'dimonds_id' => $id,
        ]);
        $dimond->update(['status' => 'Processing']);
        // }

        if (!isset($daily)) {
            Daily::create([
                'dimonds_id' => $id,
                'barcode' => $dimond->barcode_number,
                'stage' => 'No',
                'status' => 0,
            ]);
        }

        return redirect()->back();
    }

    public function summary(Request $request)
    {
        $partyLists = Party::where('is_active', 1)->get();
        $partyes = [];

        $partyId = $request->input('party_id');

        // $dimondsQuery = Dimond::query();
        $partyes = Party::query();
        if (isset($partyId) && $partyId != 'All') {
            $partyes->where('id', $partyId);
        }

        // $partyes = $partyId ? $partyes : $partyLists;
        $partyes = $partyes->where('is_active', 1)->get();

        return view('admin.reports.summary', compact('partyLists', 'partyes'));
    }

    public function summaryExport(Request $request)
    {

        $partyLists = Party::where('is_active', 1)->get();
        $partyes = [];

        $partyId = $request->input('party_id');

        // $dimondsQuery = Dimond::query();
        $partyes = Party::query();
        if (isset($partyId) && $partyId != 'All') {
            $partyes->where('id', $partyId);
        }

        $company = Company::first();

        // $partyes = $partyId ? $partyes : $partyLists;
        $partyes = $partyes->where('is_active', 1)->get();

        $pdf = PDF::loadView('admin.reports.summary_template', compact('partyLists', 'partyes', 'company'));

        return $pdf->download('summary-report.pdf');
    }

    // public function workerSummary(Request $request)
    // {
    //     $designations = Designation::get();
    //     $workerLists = Worker::get();
    //     $workers = [];

    //     $workerId = $request->input('worker_name');
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');

    //     $workers = Worker::query();
    //     if (isset($workerId) && $workerId != 'All') {
    //         $workers = $workers->where('fname', $workerId)->get();
    //     } else {
    //         $workers = $workers->get();
    //         return view('admin.reports.workersummaryAll', compact('workerLists', 'workers', 'designations'));
    //     }

    //     return view('admin.reports.workersummary', compact('workerLists', 'workers', 'designations'));
    // }

    public function workerSummary(Request $request)
    {
        $designations = Designation::get();
        $workerLists = Worker::where('is_active', 1)->get();
        $workers = [];

        $designation = $request->input('designation');
        $worker_name = $request->input('worker_name');
        $category = $request->input('category');

        if (isset($category) && $category != 'all') {
            $categorydesignation = Designation::where('category', $category)->pluck('name')->toArray();
            if (isset($designation) && $designation != 'all') {
                if (isset($worker_name) && $worker_name != 'all') {
                    $workers = Worker::where(['designation' => $designation, 'fname' => $worker_name, 'is_active' => 1])->get();
                } else {
                    $workers = Worker::where('designation', $designation)->where('is_active', 1)->get();
                }
            } else {
                if (isset($worker_name) && $worker_name != 'all') {
                    $workers = Worker::whereIn('designation', $categorydesignation)->where('fname', $worker_name)->where('is_active', 1)->get();
                } else {
                    $workers = Worker::whereIn('designation', $categorydesignation)->where('is_active', 1)->get();
                    return view('admin.reports.workersummaryAll', compact('workerLists', 'workers', 'designations'));
                }
            }
        } else if (isset($category) && $category == 'all') {
            if (isset($designation) && $designation != 'all') {
                if (isset($worker_name) && $worker_name != 'all') {
                    $workers = Worker::where(['designation' => $designation, 'fname' => $worker_name, 'is_active' => 1])->get();
                } else {
                    $workers = Worker::where('designation', $designation)->where('is_active', 1)->get();
                }
            } else {
                if (isset($worker_name) && $worker_name != 'all') {
                    $workers = Worker::where(['fname' => $worker_name, 'is_active' => 1])->get();
                } else {
                    $workers = Worker::where('is_active', 1)->get();
                    return view('admin.reports.workersummaryAll', compact('workerLists', 'workers', 'designations'));
                }
            }
        }

        return view('admin.reports.workersummary', compact('workerLists', 'workers', 'designations'));
    }

    // public function workerSummaryExport(Request $request)
    // {

    //     $workerLists = Worker::get();
    //     $workers = [];
    //     $designations = Designation::get();

    //     $workerId = $request->input('worker_name');
    //     $startDate = $request->input('start_date');
    //     $endDate = $request->input('end_date');

    //     $workers = Worker::query();
    //     if (isset($workerId) && $workerId != 'All') {
    //         $workers = $workers->where('fname', $workerId)->get();
    //     } else {
    //         $workers = $workers->get();
    //     }

    //     $pdf = PDF::loadView('admin.reports.worker_summary_template', compact('workerLists', 'workers', 'designations'));

    //     return $pdf->download('worker-summary-report.pdf');
    // }

    public function workerSummaryExport(Request $request)
    {

        $designations = Designation::get();
        $workerLists = Worker::where('is_active', 1)->get();
        $workers = [];

        $designation = $request->input('designation');
        $worker_name = $request->input('worker_name');
        $category = $request->input('category');

        if (isset($category) && $category != 'all') {
            $categorydesignation = Designation::where('category', $category)->pluck('name')->toArray();
            if (isset($designation) && $designation != 'all') {
                if (isset($worker_name) && $worker_name != 'all') {
                    $workers = Worker::where(['designation' => $designation, 'fname' => $worker_name, 'is_active' => 1])->get();
                } else {
                    $workers = Worker::where('designation', $designation)->where('is_active', 1)->get();
                }
            } else {
                if (isset($worker_name) && $worker_name != 'all') {
                    $workers = Worker::whereIn('designation', $categorydesignation)->where('fname', $worker_name)->where('is_active', 1)->get();
                }
                if (isset($worker_name) && $worker_name == 'all') {
                    $workers = Worker::whereIn('designation', $categorydesignation)->where('is_active', 1)->get();
                    // return view('admin.reports.workersummaryAll', compact('workerLists', 'workers', 'designations'));
                }
            }
        } else if (isset($category) && $category == 'all') {
            if (isset($designation) && $designation != 'all') {
                if (isset($worker_name) && $worker_name != 'all') {
                    $workers = Worker::where(['designation' => $designation, 'fname' => $worker_name, 'is_active' => 1])->get();
                } else {
                    $workers = Worker::where('designation', $designation)->where('is_active', 1)->get();
                }
            } else {
                if (isset($worker_name) && $worker_name != 'all') {
                    $workers = Worker::where(['fname' => $worker_name, 'is_active' => 1])->get();
                }
                if (isset($worker_name) && $worker_name == 'all') {
                    $workers = Worker::where('is_active', 1)->get();
                    // return view('admin.reports.workersummaryAll', compact('workerLists', 'workers', 'designations'));
                }
            }
        }

        $company = Company::first();

        $pdf = PDF::loadView('admin.reports.worker_summary_template', compact('workerLists', 'workers', 'designations', 'company'));

        return $pdf->download('worker-summary-report.pdf');
    }


    public function workerSlip(Request $request)
    {
        $designations = Designation::where('category', 'Outter')->get();
        $designa = Designation::where('category', 'Outter')->pluck('name')->toArray();
        $workerLists = Worker::whereIN('designation', $designa)->where('is_active', 1)->get();
        $workers = [];

        $designation = $request->input('designation');
        $worker_name = $request->input('worker_name');
        $category = $request->input('category');

        if (isset($category) && $category != 'all') {
            $categorydesignation = Designation::where('category', $category)->pluck('name')->toArray();
            if (isset($designation) && $designation != 'all') {
                if (isset($worker_name) && $worker_name != 'all') {
                    $workers = Worker::where(['designation' => $designation, 'fname' => $worker_name, 'is_active' => 1])->get();
                } else {
                    $workers = Worker::where('designation', $designation)->where('is_active', 1)->get();
                }
            } else {
                if (isset($worker_name) && $worker_name != 'all') {
                    $workers = Worker::whereIn('designation', $categorydesignation)->where('fname', $worker_name)->where('is_active', 1)->get();
                } else {
                    $workers = Worker::whereIn('designation', $categorydesignation)->where('is_active', 1)->get();
                }
            }
        } else if (isset($category) && $category == 'all') {
            if (isset($designation) && $designation != 'all') {
                if (isset($worker_name) && $worker_name != 'all') {
                    $workers = Worker::where(['designation' => $designation, 'fname' => $worker_name])->where('is_active', 1)->get();
                } else {
                    $workers = Worker::where('designation', $designation)->where('is_active', 1)->get();
                }
            } else {
                if (isset($worker_name) && $worker_name != 'all') {
                    $workers = Worker::where(['fname' => $worker_name, 'is_active' => 1])->get();
                } else {
                    $workers = Worker::where('is_active', 1)->get();
                }
            }
        }

        return view('admin.reports.workerslip', compact('workerLists', 'workers', 'designations'));
    }

    public function generateSlipPdf(Request $request)
    {
        $selectedRows = $request->input('selectedIds');
        $selectedIds = explode(',', $selectedRows);
        $workerprocess = [];
        $worker_name = $request->input('worker_name');

        if (!empty($selectedRows)) {
            // Loop through each selected row
            foreach ($selectedIds as $selectedRow) {
                // Fetch data from the database based on the ID
                $process = Process::findOrFail($selectedRow);
                $dimond = Dimond::where('barcode_number', $process->dimonds_barcode)->first();

                // Add the processed data to the array
                $workerprocess[] = [
                    'dimond_name' => $dimond->dimond_name,
                    'barcode_number' => $dimond->barcode_number,
                    'issue_date' => Carbon::parse($process->issue_date)->format('d-m-Y'),
                    'issue_weight' => $process->issue_weight,
                ];
            }

            $company = Company::first();

            // Generate PDF using selected rows data
            $pdf = PDF::loadView('admin.reports.slip_template', ['workerprocess' => $workerprocess, 'worker_name' => $worker_name, 'company' => $company]);

            // Return the PDF as a download
            return $pdf->download('selected_slip_data.pdf');
        }

        return redirect()->back()->with('error', "Please select Ids");
    }

    public function diamondSlip(Request $request)
    {

        $partyLists = Party::where('is_active', 1)->get();
        $partyId = $request->input('party_id');
        $status = ['Delivered'];
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $dimondsQuery = Dimond::query();
        if ($partyId) {
            $party_name = Party::where('id', $partyId)->first();
        }
        if (isset($partyId)) {
            if ($partyId == 'All') {
            } else {
                $dimondsQuery->where('parties_id', $partyId);
            }
        }
        if (!empty($status)) {
            $dimondsQuery->whereIn('status',  $status);
        }
        if (isset($startDate)) {
            if (isset($endDate)) {
                $dimondsQuery->whereDate('delevery_date', '>=', $startDate)->whereDate('delevery_date', '<=', $endDate);
            } else {
                $dimondsQuery->where('delevery_date', '>=', $startDate);
            }
        }

        $dimonds = $partyId ? $dimondsQuery->get() : [];

        return view('admin.reports.diamondslip', compact('partyLists', 'dimonds'));
    }

    public function diamondSlipPdf(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'selectedIds' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }
        $selectedRows = $request->input('selectedIds');
        $selectedIds = explode(',', $selectedRows);
        $process = [];
        $party = Party::where('id', $request->input('parties_id'))->first();
        $party_name = $party->fname;

        if (!empty($selectedRows)) {

            foreach ($selectedIds as $selectedRow) {
                // Fetch data from the database based on the ID
                $dimond = Dimond::where('id', $selectedRow)->first();

                // Add the processed data to the array
                $process[] = [
                    'dimond_name' => $dimond->dimond_name,
                    'barcode_number' => $dimond->barcode_number,
                    'weight' => $dimond->weight,
                    'required_weight' => $dimond->required_weight,
                    'shape' => $dimond->shape,
                    'clarity' => $dimond->clarity,
                    'color' => $dimond->color,
                    'cut' => $dimond->cut,
                    'polish' => $dimond->polish,
                    'symmetry' => $dimond->symmetry,
                    'amount' => $dimond->amount,
                    'created_at' => $dimond->created_at,
                    'delivery_date' => Carbon::parse($dimond->delevery_date)->format('d-m-Y'),
                ];
            }

            $company = Company::first();
            // Generate PDF using selected rows data
            $pdf = PDF::loadView('admin.reports.party_slip_template', ['process' => $process, 'party_name' => $party_name, 'company' => $company]);

            return $pdf->download('diamond_slip.pdf');
        }

        return redirect()->back()->with('error', "Please select Ids");
    }

    public function diamondSlipExcel(Request $request)
    {
        $selectedRows = $request->input('selectedIds');
        $selectedIds = explode(',', $selectedRows);

        if (empty($selectedRows)) {
            return redirect()->back()->with('error', "Please select Ids");
        }

        $party = Party::where('id', $request->input('parties_id'))->first();
        $party_name = $party->fname ?? 'Party';

        return Excel::download(new DiamondSlipStyledExport($selectedIds, $party_name), 'diamond_slip.xlsx');
    }

    public function hrExport(Request $request)
    {
        $data = [];
        if ($request->party_id) {
            $partyId = $request->input('party_id');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $data = Dimond::where(['parties_id' => $partyId, 'status' => 'Delivered'])->whereDate('delevery_date', '>=', $startDate)->whereDate('delevery_date', '<=', $endDate)->get();
        }

        $partyLists = Party::where('is_active', 1)->get();
        return view('admin.reports.hr_report', compact('partyLists', 'data'));
    }

    public function hrExportPDF(Request $request)
    {

        $partyId = $request->input('party_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $company = Company::first();
        $party = Party::where('id', $partyId)->first();
        $data = Dimond::where(['parties_id' => $partyId, 'status' => 'Delivered'])->whereDate('delevery_date', '>=', $startDate)->whereDate('delevery_date', '<=', $endDate)->get();

        $pdf = PDF::loadView('admin.reports.hr_slip_template', compact('data', 'party', 'company'));
        // Session::flash('success', "Download Record Successfully");
        return $pdf->download('generate-' . $party->fname . '-report.pdf');
    }

    public function addDiamondList(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $data = [];

        if (isset($startDate) && isset($endDate)) {
            $data = Dimond::whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->get();
        }
        return view('admin.reports.adddimondlist', compact('data'));
    }

    public function diamondPrintList(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $data = [];

        if (isset($startDate) && isset($endDate)) {
            $data = Dimond::whereDate('created_at', '>=', $startDate)->whereDate('created_at', '<=', $endDate)->get();
        }
        return view('admin.dimond.dimondprintlist', compact('data'));
    }

    public function downloadPDF(Request $request)
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

        // Fetch selected diamonds data
        $data = Dimond::whereIn('id', $selectedDiamonds)->get();

        return view('admin.dimond.printSlip', compact('data'));

        // $pdf = PDF::loadView('admin.dimond.printSlip', compact('data'));

        // return $pdf->download('barcodes-list.pdf');
    }

    public function getProcessDetails(Request $request)
    {
        $dimondId = $request->input('dimond_id');
        $barcode = $request->input('barcode_number');

        $processes = Process::where([
            'dimonds_barcode' => $barcode,
            'dimonds_id' => $dimondId
        ])->get();

        return view('partials.process_details_modal', compact('processes'))->render();
    }

    public function diamondSlipJanger(Request $request)
    {
        $partyLists = Party::where('is_active', 1)->get();
        $partyId = $request->input('party_id');
        $jangerNo = $request->input('janger_no');
        $status = ['Delivered'];
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // dd($request->all());

        $dimondsQuery = Dimond::query();

        if (isset($partyId)) {
            $dimondsQuery->where('parties_id', $partyId);
        }
        if (isset($jangerNo) && !empty($jangerNo) && $jangerNo != 'all') {
            $dimondsQuery->where('janger_no', $jangerNo);
        }
        if (!empty($status)) {
            $dimondsQuery->whereIn('status',  $status);
        }
        if (isset($startDate)) {
            if (isset($endDate)) {
                $dimondsQuery->whereDate('delevery_date', '>=', $startDate)->whereDate('delevery_date', '<=', $endDate);
            } else {
                $dimondsQuery->where('delevery_date', '>=', $startDate);
            }
        }

        $dimonds = $partyId ? $dimondsQuery->get() : [];

        return view('admin.reports.diamondslipjanger', compact('partyLists', 'dimonds', 'jangerNo'));
    }

    public function diamondSlipJangerPdf(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'selectedIds' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }
        $selectedRows = $request->input('selectedIds');
        $selectedIds = explode(',', $selectedRows);
        $process = [];
        $party = Party::where('id', $request->input('parties_id'))->first();
        $party_name = $party->fname;

        if (!empty($selectedRows)) {

            foreach ($selectedIds as $selectedRow) {
                // Fetch data from the database based on the ID
                $dimond = Dimond::where('id', $selectedRow)->first();

                // Add the processed data to the array
                $process[] = [
                    'dimond_name' => $dimond->dimond_name,
                    'janger_no' => $dimond->janger_no,
                    'barcode_number' => $dimond->barcode_number,
                    'weight' => $dimond->weight,
                    'required_weight' => $dimond->required_weight,
                    'shape' => $dimond->shape,
                    'clarity' => $dimond->clarity,
                    'color' => $dimond->color,
                    'cut' => $dimond->cut,
                    'polish' => $dimond->polish,
                    'symmetry' => $dimond->symmetry,
                    'amount' => $dimond->amount,
                    'created_at' => $dimond->created_at,
                    'delivery_date' => Carbon::parse($dimond->delevery_date)->format('d-m-Y'),
                ];
            }

            $company = Company::first();
            // Generate PDF using selected rows data
            $pdf = PDF::loadView('admin.reports.party_slip_janger_template', ['process' => $process, 'party_name' => $party_name, 'company' => $company]);

            return $pdf->download('diamond_slip_janger.pdf');
        }

        return redirect()->back()->with('error', "Please select Ids");
    }

    public function diamondSlipJangerExcel(Request $request)
    {
        $selectedRows = $request->input('selectedIds');
        $selectedIds = explode(',', $selectedRows);

        if (empty($selectedRows)) {
            return redirect()->back()->with('error', "Please select Ids");
        }

        $party = Party::where('id', $request->input('parties_id'))->first();
        $party_name = $party->fname ?? 'Party';

        return Excel::download(new DiamondSlipJangerStyledExport($selectedIds, $party_name), 'diamond_slip_janger.xlsx');
    }
}
