@extends('layouts.admin')
@section('content')

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">Slider List
        <div class="card-action">
          <div class="dropdown">
            <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
              <i class="icon-options"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" href="{{route('admin.slider.create')}}"><i class="fa fa-plus editable" style="font-size:15px;">&nbsp;ADD</i></a>
            </div>
          </div>
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
                <a href="{{route('admin.slider.edit', $gallery->id)}}"><i class="fa fa-edit" style="color:white;font-size:15px;background-color:rgba(255, 255, 255, 0.25);padding:8px;"></i></a>
                <a href="{{route('admin.slider.destroy', $gallery->id)}}" onclick="return confirm('Sure ! You want to delete ?');"><i class="fa fa-trash" style="color:white;font-size:15px;background-color:rgba(255, 255, 255, 0.25);padding:8px;"></i></a>
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