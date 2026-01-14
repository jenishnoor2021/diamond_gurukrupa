@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Worker Attendance</h4>

            <!-- <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Edit Worker Attendance</li>
                    </ol>
                </div> -->

        </div>
    </div>
</div>
<!-- end page title -->

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">

                {!! Form::model($workerattendance, [
                'method' => 'PATCH',
                'action' => ['AdminWorkerAttendanceController@update', $workerattendance->id],
                'files' => true,
                'class' => 'form-horizontal',
                'name' => 'editworkerattendanceform',
                ]) !!}
                @csrf

                <div class="row">
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="worker_id">Worker</label>
                            <select name="worker_id" id="worker_id" class="form-select" required>
                                <option value="">Select worker</option>
                                @foreach ($workers as $worker)
                                <option value="{{ $worker->id }}"
                                    {{ $worker->id == $workerattendance->worker_id ? 'selected' : '' }}>
                                    {{ $worker->fname }}&nbsp;{{ $worker->lname }}
                                </option>
                                @endforeach
                            </select>
                            @if ($errors->has('worker_id'))
                            <div class="error text-danger">{{ $errors->first('worker_id') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="date">Date</label>
                            <input type="date" name="date" class="form-control"
                                id="date" placeholder="Enter number"
                                value="{{ $workerattendance->date ? \Carbon\Carbon::parse($workerattendance->date)->format('Y-m-d') : '' }}"
                                required>
                            @if ($errors->has('date'))
                            <div class="error text-danger">{{ $errors->first('date') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="check_in">Check In time</label>
                            <input type="datetime-local" name="check_in" class="form-control"
                                id="check_in"
                                value="{{ $workerattendance->check_in ? \Carbon\Carbon::parse($workerattendance->check_in)->format('Y-m-d\TH:i') : '' }}">
                            @if ($errors->has('check_in'))
                            <div class="error text-danger">{{ $errors->first('check_in') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="check_out">Check Out time</label>
                            <input type="datetime-local" name="check_out" class="form-control"
                                id="check_out"
                                value="{{ $workerattendance->check_out ? \Carbon\Carbon::parse($workerattendance->check_out)->format('Y-m-d\TH:i') : '' }}">
                            @if ($errors->has('check_out'))
                            <div class="error text-danger">{{ $errors->first('check_out') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-lg-2">
                        <div class="mb-3">
                            <label for="duration">Duration </label>
                            <input type="text" name="duration" class="form-control"
                                id="duration" placeholder="Enter duration" value="{{ $workerattendance->duration }}"
                                readonly>
                            @if ($errors->has('duration'))
                            <div class="error text-danger">{{ $errors->first('duration') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-md">update</button>
                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/attendance') }}">Back</a>
                </div>
                </form>
            </div>
            <!-- end card body -->
        </div>
        <!-- end card -->
    </div>
    <!-- end col -->
</div>
<!-- end row -->
@endsection

@section('script')
<script>
    $(function() {
        $("form[name='editworkerattendanceform']").validate({
            rules: {
                worker_id: {
                    required: true,
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const checkInInput = document.getElementById('check_in');
        const checkOutInput = document.getElementById('check_out');
        const durationInput = document.getElementById('duration');

        function calculateDuration() {
            const checkInTime = new Date(checkInInput.value);
            const checkOutTime = new Date(checkOutInput.value);

            if (checkInTime > checkOutTime) {
                alert("Check-in time cannot be later than check-out time");
                // checkOutInput.value = '';
                // durationInput.value = '';
                return;
            }

            if (checkInInput.value && checkOutInput.value) {
                const duration = Math.abs(checkOutTime - checkInTime) / 1000;
                const hours = Math.floor(duration / 3600);
                const minutes = Math.floor((duration % 3600) / 60);
                const seconds = Math.floor(duration % 60);
                const formattedDuration =
                    `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                durationInput.value = formattedDuration;
            } else {
                durationInput.value = '';
            }
        }

        checkInInput.addEventListener('input', calculateDuration);
        checkOutInput.addEventListener('input', calculateDuration);

        // Initial calculation
        calculateDuration();
    });
</script>
@endsection