@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">Edit Expense</h4>

            <!-- <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
                        <li class="breadcrumb-item active">Edit Expense</li>
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
                <h4 class="card-title mb-4">ADD</h4>

                {!! Form::model($expense, [
                'method' => 'PATCH',
                'action' => ['AdminExpenceController@update', $expense->id],
                'files' => true,
                'class' => 'form-horizontal',
                'name' => 'editexpenseform',
                ]) !!}
                @csrf

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="title">Title</label>
                            <input type="text" name="title" class="form-control" id="title"
                                placeholder="Enter title" value="{{ $expense->title }}" required>
                            @if ($errors->has('title'))
                            <div class="error text-danger">{{ $errors->first('title') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="amount">Amount</label>
                            <input type="number" name="amount" class="form-control" id="amount"
                                placeholder="Enter amount" value="{{ $expense->amount }}" required>
                            @if ($errors->has('amount'))
                            <div class="error text-danger">{{ $errors->first('amount') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="date">Date</label>
                            <input type="date" name="date" class="form-control" id="date"
                                placeholder="Enter date" value="{{ $expense->date }}" required>
                            @if ($errors->has('date'))
                            <div class="error text-danger">{{ $errors->first('date') }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description">Description</label>
                    <textarea type="text" name="description" class="form-control" id="description" placeholder="Enter detail">{{ $expense->description }}</textarea>
                    @if ($errors->has('description'))
                    <div class="error text-danger">{{ $errors->first('description') }}</div>
                    @endif
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary w-md">Submit</button>
                    <a class="btn btn-light w-md" href="{{ URL::to('/admin/expense') }}">Back</a>
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
        $("form[name='editexpenseform']").validate({
            rules: {
                title: {
                    required: true,
                },
                amount: {
                    required: true,
                },
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
@endsection