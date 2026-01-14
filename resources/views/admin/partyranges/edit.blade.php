@extends('layouts.admin')
@section('content')
<!-- start page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Party Range List</h4>

         <!-- <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Tables</a></li>
               <li class="breadcrumb-item active">Party Range List</li>
            </ol>
         </div> -->

      </div>
   </div>
</div>
<!-- end page title -->

<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-body">

            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               <i class="mdi mdi-check-all me-2"></i>
               {{ session('success') }}
               <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            {!! Form::model($partyR, ['method'=>'PATCH', 'action'=> ['AdminPartyRangeController@update', $partyR->id],'files'=>true,'class'=>'form-horizontal', 'name'=>'editpartyRform']) !!}
            @csrf

            <div class="row">
               <div class="col-md-2">
                  <div class="mb-3">
                     <label for="shape" class="form-label">Shape</label>
                     <select name="shape" id="shape" class="form-select" required>
                        <option value="">Select shape</option>
                        <option value="Round" {{ $partyR->shape == 'Round' ? 'selected' : '' }}>Round</option>
                        <option value="Other" {{ $partyR->shape == 'Other' ? 'selected' : '' }}>Other</option>
                     </select>
                     @if($errors->has('shape'))
                     <div class="error text-danger">{{ $errors->first('shape') }}</div>
                     @endif
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="mb-3">
                     <label for="min_value" class="form-label">Min</label>
                     <input type="text" name="min_value" class="form-control" style="background-color:#ccc;" id="min_value" placeholder="Enter min value" value="{{ $partyR->min_value }}" readonly required>
                     @if($errors->has('min_value'))
                     <div class="error text-danger">{{ $errors->first('min_value') }}</div>
                     @endif
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="mb-3">
                     <label for="max_value" class="form-label">Max</label>
                     <input type="text" name="max_value" class="form-control" id="max_value" placeholder="Enter max value" value="{{ $partyR->max_value }}" oninput="formatWeight(this);" required>
                     @if($errors->has('max_value'))
                     <div class="error text-danger">{{ $errors->first('max_value') }}</div>
                     @endif
                  </div>
               </div>
               <div class="col-md-2">
                  <div class="mb-3">
                     <label for="value" class="form-label">Default Value</label>
                     <input type="text" name="value" class="form-control" id="value" placeholder="Enter value" value="{{ $partyR->value }}" oninput="formatValue(this);" required>
                     @if($errors->has('value'))
                     <div class="error text-danger">{{ $errors->first('value') }}</div>
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="d-flex gap-2">
                     <button type="submit" class="btn btn-primary w-md">Submit</button>
                     <a class="btn btn-light w-md" href="{{ URL::to('/admin/party_range') }}">Back</a>
                  </div>
               </div>
            </div>
            </form>

            <hr style="border:1px solid #000;">
            <table id="" class="table table-bordered dt-responsive nowrap w-100 mt-3">
               <thead>
                  <tr>
                     <th>Action</th>
                     <th>Key</th>
                     <th>Shape</th>
                     <th>Min</th>
                     <th>Max</th>
                     <th>Default Value</th>
                  </tr>
               </thead>

               <tbody>
                  @foreach ($partyranges as $partyrange)
                  <tr>
                     <td>
                        <a href="{{ route('admin.party_range.edit', $partyrange->id) }}"
                           class="btn btn-outline-primary waves-effect waves-light"><i
                              class="fa fa-edit"></i></a>
                        <a href="{{ route('admin.party_range.destroy', $partyrange->id) }}"
                           onclick="return confirm('Sure ! You want to delete ?');"
                           class="btn btn-outline-danger waves-effect waves-light"><i
                              class="fa fa-trash"></i></a>
                     </td>
                     <td>{{$partyrange->key}}</td>
                     <td>{{$partyrange->shape}}</td>
                     <td>{{$partyrange->min_value}}</td>
                     <td>{{$partyrange->max_value}}</td>
                     <td>{{$partyrange->value}}</td>
                  </tr>
                  @endforeach
               </tbody>
            </table>

         </div>
      </div>
   </div> <!-- end col -->
</div> <!-- end row -->
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
   $(function() {
      $("form[name='editpartyRform']").validate({
         rules: {
            shape: {
               required: true,
            },
            min_value: {
               required: true,
            },
            max_value: {
               required: true,
            },
         },
         submitHandler: function(form) {
            form.submit();
         }
      });
   });

   function formatWeight(input) {
      // Remove any non-numeric characters
      var cleanedValue = input.value.replace(/[^0-9.]/g, '');

      // Ensure valid pattern: either empty, '0.00', or '00.00'
      var match = cleanedValue.match(/^(\d{0,2}(\.\d{0,2})?)?$/);

      // Update the input value with the formatted result
      input.value = match ? match[1] || '' : '';

      // Get min and max values
      var minValue = parseFloat(document.getElementById('min_value').value) || 0;
      var maxValue = parseFloat(input.value) || 0;

      // Check if max_value is greater than min_value
      if (maxValue < minValue) {
         alert('Max value must be greater than Min value!');
         input.value = ''; // Clear invalid input
      }
   }

   $(document).ready(function() {
      $('#shape').change(function() {
         var selectedShape = $(this).val();

         if (selectedShape) {
            $.ajax({
               url: '{{ route("getMinValue") }}',
               type: 'GET',
               data: {
                  shape: selectedShape
               },
               success: function(response) {
                  $('#min_value').val(response.min_value);
               }
            });
         }
      });
   });
</script>
@endsection