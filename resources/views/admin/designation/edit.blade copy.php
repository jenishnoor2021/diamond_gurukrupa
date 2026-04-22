@extends('layouts.admin')
@section('content')
<div class="row mt-3">
   <div class="col-lg-8 mx-auto">
      <div class="card">
         <div class="card-body">
            @if(session()->has('message'))
            <div class="alert text-white" style="background-color:green">
               {{ session()->get('message') }}
            </div>
            @endif
            <div class="card-title">Edit Designation</div>
            <hr>
            {!! Form::model($designation, ['method'=>'PATCH', 'action'=> ['AdminDesignationController@update', $designation->id],'files'=>true,'class'=>'form-horizontal','name'=>'editdesignationform']) !!}
            @csrf
            <div class="row">
               <div class="col-4">
                  <div class="form-group">
                     <label for="category">Designation Category</label>
                     <select name="category" id="category" class="custom-select form-control form-control-rounded" style="width:100%" required>
                        <option value="">Select category</option>
                        <option value="Inner" {{ $designation->category == 'Inner' ? 'selected' : '' }}>Inner</option>
                        <option value="Outter" {{ $designation->category == 'Outter' ? 'selected' : '' }}>Outter</option>
                     </select>
                     @if($errors->has('category'))
                     <div class="error text-danger">{{ $errors->first('category') }}</div>
                     @endif
                  </div>
               </div>
               <div class="col-4">
                  <div class="form-group">
                     <label for="name">Designation Name</label>
                     <input type="text" name="name" class="form-control form-control-rounded" id="name" placeholder="Enter First name" onkeypress='return (event.charCode != 32)' value="{{$designation->name}}">
                     @if($errors->has(' name')) <div class="error text-danger">{{ $errors->first('name') }}
                     </div>
                     @endif
                  </div>
               </div>
               <div class="col-4">
                  <div class="form-group">
                     <label for="rate_apply_on">Rate Calculate On</label>
                     <select name="rate_apply_on" id="rate_apply_on" class="custom-select form-control form-control-rounded" style="width:100%" required>
                        <option value="">Select weight</option>
                        <option value="issue_weight" {{ $designation->rate_apply_on == 'issue_weight' ? 'selected' : '' }}>Issue weight</option>
                        <option value="return_weight" {{ $designation->rate_apply_on == 'return_weight' ? 'selected' : '' }}>Return weight</option>
                        <option value="diff_weight" {{ $designation->rate_apply_on == 'diff_weight' ? 'selected' : '' }}>Diffrence weight</option>
                     </select>
                     @if($errors->has('rate_apply_on'))
                     <div class="error text-danger">{{ $errors->first('rate_apply_on') }}</div>
                     @endif
                  </div>
               </div>
            </div>
            <div class="form-group">
               <button type="submit" class="btn btn-light btn-round px-5">Update</button>
            </div>
            </form>
         </div>
      </div>
   </div>
</div><!--End Row-->
@endsection

@section('script')
<script>
   $(function() {

      $("form[name='editdesignationform']").validate({
         rules: {
            name: {
               required: true,
            },
            category: {
               required: true,
            }
         },
         submitHandler: function(form) {
            form.submit();
         }
      });
   });
</script>
@endsection