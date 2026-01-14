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
            <div class="card-title">Edit Slider</div>
            <hr>
            {!! Form::model($gallery, ['method'=>'PATCH', 'action'=> ['AdminSlidersController@update', $gallery->id],'files'=>true,'class'=>'form-horizontal']) !!}
            @csrf
            <div class="form-group">
               <label for="text">Text</label>
               <input type="text" name="text" class="form-control form-control-rounded" id="text" placeholder="Enter Text" value="{{$gallery->text}}" required>
            </div>
            <div class="form-group">
               <img height="100px" src="{{$gallery->file ? $gallery->file : 'https://eitrawmaterials.eu/wp-content/uploads/2016/09/person-icon.png'}}" alt="">
            </div>
            <div class="form-group">
               <label for="file">Image</label>
               <input type="file" name="file" class="form-control form-control-rounded" id="file" placeholder="Enter image">
            </div>
            @if($errors->has('file'))
            <div class="error text-danger">{{ $errors->first('file') }}</div>
            @endif
            <div class="form-group">
               <img id="blah" src="#" alt="your image" style="display:none;max-height: 100px;width:100px" />
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