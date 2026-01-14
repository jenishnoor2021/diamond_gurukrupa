<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Worker;
use Illuminate\Http\Request;
use App\Models\WorkerBarcode;
use App\Models\WorkerAttendance;
use Illuminate\Support\Facades\Redirect;

class AdminWorkerAttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = WorkerAttendance::query();

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if ($request->has('worker_id') && $request->worker_id != '') {
            $query->where('worker_id', $request->worker_id);
        }

        $todayattendanceRecords = $query->orderBy('id', 'DESC')->get();
        $workers = Worker::where('is_active', 1)->orderBy('id', 'DESC')->get();

        return view('admin.worker_attendance.index', compact('workers', 'todayattendanceRecords'));
    }

    public function attendanceSummary(Request $request)
    {
        $todayattendanceRecords = [];
        $query = WorkerAttendance::query();

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('date', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('date', '<=', $request->end_date);
        }

        if ($request->start_date != '' || $request->end_date != '') {
            $todayattendanceRecords = $query->orderBy('id', 'DESC')->get();
        }

        $totaldays = null;
        if ($request->has('start_date') && $request->has('end_date') && $request->start_date != '' && $request->end_date != '') {
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $totaldays = $startDate->diffInDays($endDate) + 1; // +1 to include both start and end date
        }

        $workers = Worker::where('is_active', 1)->get();
        return view('admin.worker_attendance.summary', compact('workers', 'todayattendanceRecords', 'totaldays'));
    }

    public function checkIn()
    {
        $today = Carbon::today();
        $todayattendanceRecords = WorkerAttendance::whereDate('date', $today)->get();
        return view('admin.worker_attendance.checkin', compact('todayattendanceRecords'));
    }

    public function checkOut()
    {
        $today = Carbon::today();
        $todayattendanceRecords = WorkerAttendance::whereDate('date', $today)->get();
        return view('admin.worker_attendance.checkout', compact('todayattendanceRecords'));
    }

    public function checkInStore(Request $request)
    {
        $barcode = $request->inputField;
        $barcodeDetail = WorkerBarcode::where('barcode', $barcode)->first();
        if (isset($barcodeDetail)) {
            $today = Carbon::today();
            $currentTime = Carbon::now();
            $attendanceRecord = WorkerAttendance::where('worker_id', $barcodeDetail->worker_id)->whereDate('date', $today)->first();

            if ($attendanceRecord) {
                return redirect()->back()->with('error', "You have already checked in today.");
            } else {
                // Create a new check-in record
                WorkerAttendance::create([
                    'worker_id' => $barcodeDetail->worker_id,
                    'date' => $today,
                    'check_in' => $currentTime,
                ]);

                return redirect()->back()->with('success', "Check-in successfully.");
            }
        }
        return redirect()->back()->with('error', "Invalid Barcode");
    }

    public function checkOutStore(Request $request)
    {
        $barcode = $request->inputField;
        $barcodeDetail = WorkerBarcode::where('barcode', $barcode)->first();
        if (isset($barcodeDetail)) {
            $today = Carbon::today();
            $currentTime = Carbon::now();
            // Check if there is an attendance record for today
            $attendanceRecord = WorkerAttendance::where('worker_id', $barcodeDetail->worker_id)->whereDate('date', $today)->first();

            if ($attendanceRecord) {
                // Check if already checked out
                if ($attendanceRecord->check_out) {
                    return redirect()->back()->with('error', "You have already checked out today.");
                }

                // Update check-out time and calculate duration
                $attendanceRecord->check_out = $currentTime;
                // Calculate duration
                $checkInTime = Carbon::parse($attendanceRecord->check_in);
                $duration = $checkInTime->diff($currentTime);
                $hours = $duration->h;
                $minutes = $duration->i;
                $seconds = $duration->s;
                $formattedDuration = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

                $attendanceRecord->duration = $formattedDuration;
                $attendanceRecord->save();

                return redirect()->back()->with('success', "Check-out succesfully");
            } else {
                return redirect()->back()->with('error', "You need to check in first.");
            }
        }
        return redirect()->back()->with('error', "Invalid Barcode");
        // dd($request->all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $workers = Worker::where('is_active', 1)->orderBy('id', 'DESC')->get();
        return view('admin.worker_attendance.create', compact('workers'));
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
        $attendanceRecord = WorkerAttendance::where('worker_id', $request->worker_id)->whereDate('date', $request->date)->first();

        if ($attendanceRecord) {
            return redirect()->back()->with('error', "Record already present.");
        } else {
            WorkerAttendance::create($input);
        }
        return redirect('admin/attendance')->with('success', "Add Record Successfully");
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
        $workerattendance = WorkerAttendance::findOrFail($id);
        $workers = Worker::where('id', $workerattendance->worker_id)->get();
        return view('admin.worker_attendance.edit', compact('workers', 'workerattendance'));
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
        $workeratten = WorkerAttendance::findOrFail($id);
        $input = $request->all();
        $workeratten->update($input);
        return redirect('admin/attendance')->with('success', "update Record successfully");
        // dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $worker = WorkerAttendance::findOrFail($id);
        $worker->delete();
        return Redirect::back()->with('success', "Record Deleted successfully");;
    }
}
