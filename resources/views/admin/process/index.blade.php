@extends('layouts.admin')
@section('content')

<div class="row">
   <div class="col-lg-12">
      <div class="card">
         <div class="card-header">Dimond List
            <div class="card-action">
               <div class="dropdown">
                  <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">
                     <i class="icon-options"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                     <a class="dropdown-item" href="{{route('admin.dimond.create')}}"><i class="fa fa-plus editable" style="font-size:15px;">&nbsp;ADD</i></a>
                  </div>
               </div>
            </div>
         </div>
         <div class="table-responsive">
            <table id="dimondprocesstable" class="table align-items-center table-flush table-borderless">
               <thead>
                  <tr>
                     <th>Action</th>
                     <th>Party</th>
                     <th>Stone ID</th>
                     <th>Weight</th>
                     <th>barcode</th>
                     <th>Shape</th>
                     <th>clarity</th>
                     <th>color</th>
                     <th>cut</th>
                     <th>polish</th>
                     <th>symmetry</th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     @foreach($dimonds as $dimond)
                     <td>
                        <a href="{{route('admin.dimond.edit', $dimond->id)}}"><i class="fa fa-edit" style="color:white;font-size:15px;background-color:rgba(255, 255, 255, 0.25);padding:8px;"></i></a>
                        <a href="{{route('admin.dimond.destroy', $dimond->id)}}" onclick="return confirm('Sure ! You want to delete ?');"><i class="fa fa-trash" style="color:white;font-size:15px;background-color:rgba(255, 255, 255, 0.25);padding:8px;"></i></a>
                     </td>
                     <td>{{$dimond->parties->party_code}}</td>
                     <td>{{$dimond->dimond_name}}</td>
                     <td>{{$dimond->weight}}</td>
                     <?php $data = $dimond->barcode; ?>
                     <td><?= DNS1D::getBarcodeHTML($data, 'PHARMA2T', 2, 50, 'red');  ?>
                        {{$dimond->barcode}}
                     </td>
                     <td>{{$dimond->shape}}</td>
                     <td>{{$dimond->clarity}}</td>
                     <td>{{$dimond->color}}</td>
                     <td>{{$dimond->cut}}</td>
                     <td>{{$dimond->polish}}</td>
                     <td>{{$dimond->symmetry}}</td>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div><!--End Row-->

@endsection