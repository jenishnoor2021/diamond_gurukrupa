@extends('layouts.admin')
@section('content')

<!-- start page title -->
<div class="row">
   <div class="col-12">
      <div class="page-title-box d-sm-flex align-items-center justify-content-between">
         <h4 class="mb-sm-0 font-size-18">Edit Party</h4>

         <!-- <div class="page-title-right">
            <ol class="breadcrumb m-0">
               <li class="breadcrumb-item"><a href="javascript: void(0);">Forms</a></li>
               <li class="breadcrumb-item active">Edit Party</li>
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
            <h4 class="card-title mb-4">Edit</h4>

            {!! Form::model($party, ['method'=>'PATCH', 'action'=> ['AdminPartyController@update', $party->id],'files'=>true,'class'=>'form-horizontal', 'name'=>'editpartyform']) !!}
            @csrf

            <div class="row">
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="fname" class="form-label">First Name</label>
                     <input type="text" name="fname" class="form-control" id="fname" placeholder="Enter First name" onkeypress='return (event.charCode != 32)' value="{{$party->fname}}" required>
                     @if($errors->has('fname'))
                     <div class="error text-danger">{{ $errors->first('fname') }}</div>
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="lname" class="form-label">Last Name</label>
                     <input type="text" name="lname" class="form-control" id="lname" placeholder="Enter Last Name" onkeypress='return (event.charCode != 32)' value="{{$party->lname}}" required>
                     @if($errors->has('lname'))
                     <div class="error text-danger">{{ $errors->first('lname') }}</div>
                     @endif
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="party_code">Party Code</label>
                     <input type="text" name="party_code" class="form-control" id="party_code" placeholder="Enter party code" onkeypress='return (event.charCode != 32)' value="{{$party->party_code}}" required>
                     @if($errors->has('party_code'))
                     <div class="error text-danger">{{ $errors->first('party_code') }}</div>
                     @endif
                  </div>
               </div>
            </div>

            <div class="mb-3">
               <label for="address">Address</label>
               <textarea type="text" name="address" class="form-control" id="address" placeholder="Enter Address">{{$party->address}}</textarea>
               @if($errors->has('address'))
               <div class="error text-danger">{{ $errors->first('address') }}</div>
               @endif
            </div>

            <div class="row">
               <div class="col-lg-4">
                  <div class="mb-3">
                     <label for="mobile">Mobile no</label>
                     <input type="number" name="mobile" class="form-control" id="mobile" placeholder="Enter number" value="{{$party->mobile}}">
                     @if($errors->has('mobile'))
                     <div class="error text-danger">{{ $errors->first('mobile') }}</div>
                     @endif
                  </div>
               </div>
               <div class="col-lg-4">
                  <div class="mb-3">
                     <label for="gst_no">GST No</label>
                     <input type="text" name="gst_no" class="form-control" id="gst_no" placeholder="Enter GST No" value="{{$party->gst_no}}">
                     @if($errors->has('mobile'))
                     <div class="error text-danger">{{ $errors->first('mobile') }}</div>
                     @endif
                  </div>
               </div>
            </div>

            <h4 class="card-title mb-4 mt-2">Party Rate (Round)</h4>

            <div class="row">
               @foreach ($roundpartyrangs as $roundpartyrang)
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="{{ $roundpartyrang->key }}" class="form-label">Rate ({{ $roundpartyrang->min_value }} to {{ $roundpartyrang->max_value }})</label>
                     <input type="number" name="{{ $roundpartyrang->key }}" class="form-control" id="{{ $roundpartyrang->key }}" placeholder="Enter amount" value="{{ $partyrateValues[$roundpartyrang->key] ?? '' }}">
                     @if($errors->has('$roundpartyrang->key'))
                     <div class="error text-danger">{{ $errors->first('$roundpartyrang->key') }}</div>
                     @endif
                  </div>
               </div>
               @endforeach
            </div>

            <h4 class="card-title mb-4 mt-2">Party Rate (Fancy)</h4>

            <div class="row">
               @foreach ($otherpartyrangs as $otherpartyrang)
               <div class="col-md-4">
                  <div class="mb-3">
                     <label for="{{ $otherpartyrang->key }}" class="form-label">Rate ({{ $otherpartyrang->min_value }} to {{ $otherpartyrang->max_value }})</label>
                     <input type="number" name="{{ $otherpartyrang->key }}" class="form-control" id="{{ $otherpartyrang->key }}" placeholder="Enter amount" value="{{ $partyrateValues[$otherpartyrang->key] ?? '' }}">
                     @if($errors->has('$otherpartyrang->key'))
                     <div class="error text-danger">{{ $errors->first('$otherpartyrang->key') }}</div>
                     @endif
                  </div>
               </div>
               @endforeach
            </div>

            <div class="d-flex gap-2">
               <button type="submit" class="btn btn-primary w-md">Update</button>
               <a class="btn btn-light w-md" href="{{ URL::to('/admin/party') }}">Back</a>
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

      $("form[name='editpartyform']").validate({
         rules: {
            fname: {
               required: true,
            },
            lname: {
               required: true,
            },
            // address: {
            //    required: true,
            // },
            // mobile: {
            //    required: true,
            // },
            party_code: {
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