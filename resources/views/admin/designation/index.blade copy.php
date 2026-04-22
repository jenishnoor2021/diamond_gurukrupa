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
        <div class="card-title">
          <h4>ADD Designation</h4>
        </div>
        <hr>
        {!! Form::open(['method'=>'POST', 'action'=> 'AdminDesignationController@store','files'=>true,'class'=>'form-horizontal','name'=>'designationform']) !!}
        @csrf
        <div class="form-group">
          <label for="category">Designation Category</label>
          <select name="category" id="category" class="custom-select form-control form-control-rounded" style="width:100%" required>
            <option value="">Select category</option>
            <option value="Inner">Inner</option>
            <option value="Outter">Outter</option>
          </select>
          @if($errors->has('category'))
          <div class="error text-danger">{{ $errors->first('category') }}</div>
          @endif
        </div>
        <div class="form-group">
          <label for="name">Designation Name</label>
          <input type="text" name="name" class="form-control form-control-rounded" id="name" placeholder="Enter Designation" value="{{ old('name') }}" onkeypress='return (event.charCode != 32)' required>
          @if($errors->has('name'))
          <div class="error text-danger">{{ $errors->first('name') }}</div>
          @endif
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-light btn-round px-5"><i class="fa fa-plus"></i> ADD</button>
        </div>
        </form>
      </div>
    </div>
  </div>
  <div class="col-lg-8">
    <div class="card">
      @if (session('success'))
      <div class="alert text-white pl-3 pt-2 pb-2" style="background-color:green">
        {{ session('success') }}
      </div>
      @endif
      <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Designation List</h4>
      </div>
      <div class="table-responsive">
        <table class="table align-items-center table-flush table-borderless">
          <thead>
            <tr>
              <th>Action</th>
              <th>Category</th>
              <th>Designation</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              @foreach($designations as $designation)
              <td>
                @if($designation->name != 'Grading')
                <a href="{{route('admin.designation.edit', $designation->id)}}"><i class="fa fa-edit" style="color:white;font-size:15px;background-color:#0275d8;padding:8px;border-radius:500px;"></i></a>
                <a href="{{route('admin.designation.destroy', $designation->id)}}" onclick="return confirm('Sure ! You want to delete ?');"><i class="fa fa-trash" style="color:white;font-size:15px;background-color:red;padding:8px;border-radius:500px;"></i></a>
                @endif
              </td>
              <td>{{$designation->category}}</td>
              <td>{{$designation->name}}</td>
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
    $("form[name='designationform']").validate({
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