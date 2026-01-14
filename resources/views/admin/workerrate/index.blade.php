<?php

use App\Models\WorkerRate;
?>

@extends('layouts.admin')
@section('style')
<style>
   /* Add your styling here */
   .accordion {
      display: flex;
      flex-direction: column;
      max-width: 100%;
      /* Adjust as needed */
   }

   .accordion-item {
      border: 1px solid #ddd;
      margin-bottom: 5px;
      overflow: hidden;
   }

   .accordion-header {
      background-color: transparent;
      padding: 10px;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
   }

   .accordion-content {
      display: none;
      padding: 10px;
   }

   .accordion-arrow {
      transition: transform 0.3s ease-in-out;
   }

   .accordion-item.active .accordion-arrow {
      transform: rotate(180deg);
   }
</style>
@endsection
@section('content')

<div class="row">
   <div class="col-lg-12">
      <div class="card">
         <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Worker Rate</h4>
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
         <div class="accordion p-3">
            @foreach($designations as $designation)
            <div class="accordion-item">
               <div class="accordion-header">
                  <span>{{$designation->name}}</span>
                  <span class="accordion-arrow">&#9658;</span>
               </div>
               <div class="accordion-content">
                  <table class="table align-items-center table-flush table-borderless">
                     <thead>
                        <tr>
                           <th>Range</th>
                           <th>Rate</th>
                        </tr>
                     </thead>
                     <?php
                     $workerrates = WorkerRate::where('designation', $designation->name)->get();
                     ?>
                     <tbody>
                        @if(count($workerrates) > 0)
                        {{-- Loop for index 1 --}}
                        @foreach($workerrates as $workerrate)
                        @if ($workerrate->key == 'key_1')
                        <tr>
                           <td>1.00 to 1.49</td>
                           {!! Form::model($workerrate, ['method'=>'PATCH', 'action'=> ['AdminWorkerRateController@update', $workerrate->id],'files'=>true,'class'=>'form-horizontal']) !!}
                           @csrf
                           <td>
                              <input type="text" name="value" value="{{$workerrate->value}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..?)\../g, '$1');">&nbsp;
                              <button type="submit" class="btn btn-light btn-round px-5">Update</button>
                           </td>
                           </form>
                        </tr>
                        @endif
                        @endforeach

                        {{-- Loop for index 2 --}}
                        @foreach($workerrates as $workerrate)
                        @if ($workerrate->key == 'key_2')
                        <tr>
                           <td>1.50 to 1.99</td>
                           {!! Form::model($workerrate, ['method'=>'PATCH', 'action'=> ['AdminWorkerRateController@update', $workerrate->id],'files'=>true,'class'=>'form-horizontal']) !!}
                           @csrf
                           <td>
                              <input type="text" name="value" value="{{$workerrate->value}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..?)\../g, '$1');">&nbsp;
                              <button type="submit" class="btn btn-light btn-round px-5">Update</button>
                           </td>
                           </form>
                        </tr>
                        @endif
                        @endforeach


                        {{-- Loop for index 3 --}}
                        @foreach($workerrates as $workerrate)
                        @if ($workerrate->key == 'key_3')
                        <tr>
                           <td>2.00 to 2.99</td>
                           {!! Form::model($workerrate, ['method'=>'PATCH', 'action'=> ['AdminWorkerRateController@update', $workerrate->id],'files'=>true,'class'=>'form-horizontal']) !!}
                           @csrf
                           <td>
                              <input type="text" name="value" value="{{$workerrate->value}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..?)\../g, '$1');">&nbsp;
                              <button type="submit" class="btn btn-light btn-round px-5">Update</button>
                           </td>
                           </form>
                        </tr>
                        @endif
                        @endforeach



                        {{-- Loop for index 4 --}}
                        @foreach($workerrates as $workerrate)
                        @if ($workerrate->key == 'key_4')
                        <tr>
                           <td>3.00 to more</td>
                           {!! Form::model($workerrate, ['method'=>'PATCH', 'action'=> ['AdminWorkerRateController@update', $workerrate->id],'files'=>true,'class'=>'form-horizontal']) !!}
                           @csrf
                           <td>
                              <input type="text" name="value" value="{{$workerrate->value}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..?)\../g, '$1');">&nbsp;
                              <button type="submit" class="btn btn-light btn-round px-5">Update</button>
                           </td>
                           </form>
                        </tr>
                        @endif
                        @endforeach

                        @else
                        <p> No Data</p>
                        @endif
                     </tbody>
                  </table>
               </div>
            </div>
            @endforeach
         </div>

      </div>
   </div>
</div><!--End Row-->

@endsection

@section('script')
<script>
   $(document).ready(function() {
      // Toggle accordion content and arrow rotation when clicking on the header
      $('.accordion-header').click(function() {
         $(this).parent('.accordion-item').toggleClass('active');
         $(this).find('.accordion-arrow').text(function(_, text) {
            return text === '►' ? '▼' : '►';
         });
         $(this).next('.accordion-content').slideToggle();
         $(this).parent('.accordion-item').siblings('.accordion-item').removeClass('active').find('.accordion-content').slideUp();
         $(this).parent('.accordion-item').siblings('.accordion-item').find('.accordion-arrow').text('►');
      });
   });
</script>
@endsection