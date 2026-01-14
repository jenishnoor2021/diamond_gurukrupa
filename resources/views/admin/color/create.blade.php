@extends('layouts.admin')
@section('content')

<!-- start page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">ADD Color</h4>

         <!-- <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
               <li class="breadcrumb-item active">ADD Color</li>
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

            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               <i class="mdi mdi-check-all me-2"></i>
               {{ session('success') }}
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            {!! Form::open(['method'=>'POST', 'action'=> 'AdminColorController@store','files'=>true,'class'=>'form-horizontal','name'=>'addcolorform']) !!}
            @csrf

            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="c_name" class="form-label">Name</label>
                     <input type="text" name="c_name" class="form-control" id="c_name" placeholder="Enter Name" value="{{ old('c_name') }}" onkeypress='return (event.charCode != 32)' required>
                     @if($errors->has('c_name'))
                     <div class="error text-danger">{{ $errors->first('c_name') }}</div>
                     @endif
                  </div>
               </div>
            </div>

            <div class="d-flex gap-2">
               <button type="submit" class="btn btn-primary w-md">Submit</button>
               <a class="btn btn-light w-md" href="{{ URL::to('/admin/color') }}">Back</a>
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

      $("form[name='addcolorform']").validate({
         rules: {
            c_name: {
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