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
            <div class="card-title">ADD Slider</div>
            <hr>
            {!! Form::open(['method'=>'POST', 'action'=> 'AdminSlidersController@store','files'=>true,'class'=>'form-horizontal','name'=>'sliderform']) !!}
            @csrf
            <div class="form-group">
               <label for="text">Text</label>
               <input type="text" name="text" class="form-control form-control-rounded" id="text" placeholder="Enter Text" required>
            </div>
            <div class="form-group">
               <label for="file">Image</label>
               <input type="file" name="file" class="form-control form-control-rounded" id="file" placeholder="Enter image" required>
            </div>
            @if($errors->has('file'))
            <div class="error text-danger">{{ $errors->first('file') }}</div>
            @endif
            <img id="blah" src="#" alt="your image" style="display:none;max-height: 100px;width:100px" />
            <div class="form-group">
               <button type="submit" class="btn btn-light btn-round px-5"><i class="fa fa-plus"></i> ADD</button>
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

      $("form[name='sliderform']").validate({
         rules: {
            file: {
               required: true,
            },
         },
         submitHandler: function(form) {
            form.submit();
         }
      });
   });

   $("#file").change(function() {
      let reader = new FileReader();
      reader.onload = (e) => {
         $("#blah").attr("src", e.target.result);
      };
      reader.readAsDataURL(this.files[0]);
      $("#blah").css("display", "block");
   });
</script>
@endsection