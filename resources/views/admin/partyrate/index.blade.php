@extends('layouts.admin')
@section('content')

<div class="row">
   <div class="col-lg-12">
      <div class="card">
         <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Party Rate</h4>
            <div class="card-action">
            </div>
         </div>
         @if (session('success'))
         <div class="alert text-white pl-3 pt-2 pb-2" style="background-color:green">
            {{ session('success') }}
         </div>
         @endif
         @if (session('error'))
         <div class="alert text-white pl-3 pt-2 pb-2" style="background-color:red">
            {{ session('error') }}
         </div>
         @endif
         <div class="table-responsive">
            <table class="table align-items-center table-flush table-borderless">
               <thead>
                  <tr>
                     <th>Range</th>
                     <th>value</th>
                     <!-- <th>update</th> -->
                  </tr>
               </thead>
               <tbody>
                  @foreach($partyrates as $partyrate)
                  <tr>
                     <td>
                        @if($partyrate->key == 'key_1')
                        1.00 to 1.99
                        @elseif ($partyrate->key == 'key_2')
                        2.00 to 2.99
                        @else
                        3.00 to more
                        @endif
                     </td>
                     {!! Form::model($partyrate, ['method'=>'PATCH', 'action'=> ['AdminPartyRateController@update', $partyrate->id],'files'=>true,'class'=>'form-horizontal']) !!}
                     @csrf
                     <td>
                        <input type="text" name="value" value="{{$partyrate->value}}">
                        &nbsp;
                        <button type="submit" class="btn btn-light btn-round px-5">Update</button>
                     </td>
                     </form>
                  </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div><!--End Row-->

@endsection