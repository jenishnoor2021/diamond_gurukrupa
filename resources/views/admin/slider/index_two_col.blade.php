@extends('layouts.admin')
@section('content')
<div class="row mt-3">
  <div class="col-lg-4">
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
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header">Slider List
        <div class="card-action">
          <!-- <div class="dropdown">
            <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
              <i class="icon-options"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="{{route('admin.slider.create')}}"><i class="fa fa-plus editable" style="font-size:15px;">&nbsp;ADD</i></a>
            </div>
          </div> -->
        </div>
      </div>
      <div class="table-responsive">
        <table class="table align-items-center table-flush table-borderless">
          <thead>
            <tr>
              <th>Action</th>
              <th>Product</th>
              <th>Photo</th>
              <th>Product ID</th>
              <th>Amount</th>
              <th>Date</th>
              <th>Shipping</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              @foreach($gallerys as $gallery)
              <td>
                <a href="{{route('admin.slider.edit', $gallery->id)}}"><i class="fa fa-edit" style="color:white;font-size:15px;background-color:#0275d8;padding:8px;border-radius:500px;"></i></a>
                <a href="{{route('admin.slider.destroy', $gallery->id)}}" onclick="return confirm('Sure ! You want to delete ?');"><i class="fa fa-trash" style="color:white;font-size:15px;background-color:red;padding:8px;border-radius:500px;"></i></a>
              </td>
              <td>{{$gallery->text}}</td>
              <td><img src="{{$gallery->file ? $gallery->file : 'https://via.placeholder.com/110x110'}}" class="product-img" alt="product img"></td>
              <td></td>
              <td></td>
              <td></td>
              <td>
                @if($gallery->is_show == 1)
                <a href="/admin/slider/active/{{$gallery->id}}" class="btn btn-success">Active</a>
                @else
                <a href="/admin/slider/active/{{$gallery->id}}" class="btn btn-danger">De-active</a>
                @endif
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
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