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
            <div class="card-title">Edit Dimond</div>
            <hr>
            {!! Form::model($dimond, ['method'=>'PATCH', 'action'=> ['AdminDimondController@update', $dimond->id],'files'=>true,'class'=>'form-horizontal', 'name'=>'editdimondform']) !!}
            @csrf
            <div class="row">
               <div class="col-4">
                  <div class="form-group">
                     <label for="parties_id">Party</label>
                     <select name="parties_id" id="parties_id" class="custom-select" required>
                        <option value="">Select Party</option>
                        @foreach ($partys as $party)
                        <option value="{{$party->id}}" {{$party->id == $dimond->parties_id ? 'selected' : ''}}>{{$party->party_code}}</option>
                        @endforeach
                     </select>
                  </div>
               </div>
               <div class="col-4">
                  <div class="form-group">
                     <label for="dimond_name">Stone Id</label>
                     <input type="text" name="dimond_name" class="form-control" id="dimond_name" placeholder="Enter Stone Id" value="{{$dimond->dimond_name}}" required>
                  </div>
               </div>
               <div class="col-4">
                  <div class="form-group">
                     <label for="weight">Weight</label>
                     <input type="text" name="weight" class="form-control" id="weight" placeholder="Enter weight" value="{{$dimond->weight}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" required>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-4">
                  <div class="form-group">
                     <label for="shape">Shape</label>
                     <select name="shape" id="shape" class="custom-select" required>
                        <option value="">Select shape</option>
                        <option value="Round" {{$dimond->shape == 'Round' ? 'selected' : ''}}>Round</option>
                        <option value="Oval" {{$dimond->shape == 'Oval' ? 'selected' : ''}}>Oval</option>
                        <option value="Pear" {{$dimond->shape == 'Pear' ? 'selected' : ''}}>Pear</option>
                        <option value="Cush Mod" {{$dimond->shape == 'Cush Mod' ? 'selected' : ''}}>Cush Mod</option>
                        <option value="Cush Brill" {{$dimond->shape == 'Cush Brill' ? 'selected' : ''}}>Cush Brill</option>
                        <option value="Emeraid" {{$dimond->shape == 'Emeraid' ? 'selected' : ''}}>Emeraid</option>
                        <option value="Radiant" {{$dimond->shape == 'Radiant' ? 'selected' : ''}}>Radiant</option>
                        <option value="Princess" {{$dimond->shape == 'Princess' ? 'selected' : ''}}>Princess</option>
                        <option value="Asscher" {{$dimond->shape == 'Asscher' ? 'selected' : ''}}>Asscher</option>
                        <option value="Square" {{$dimond->shape == 'Square' ? 'selected' : ''}}>Square</option>
                        <option value="Marquise" {{$dimond->shape == 'Marquise' ? 'selected' : ''}}>Marquise</option>
                        <option value="Heart" {{$dimond->shape == 'Heart' ? 'selected' : ''}}>Heart</option>
                        <option value="Trilliant" {{$dimond->shape == 'Trilliant' ? 'selected' : ''}}>Trilliant</option>
                        <option value="Euro Cut" {{$dimond->shape == 'Euro Cut' ? 'selected' : ''}}>Euro Cut</option>
                        <option value="Old Miner" {{$dimond->shape == 'Old Miner' ? 'selected' : ''}}>Old Miner</option>
                        <option value="Briolette" {{$dimond->shape == 'Briolette' ? 'selected' : ''}}>Briolette</option>
                     </select>
                  </div>
               </div>
               <div class="col-4">
                  <div class="form-group">
                     <label for="clarity">Clarity</label>
                     <select name="clarity" id="clarity" class="custom-select" required>
                        <option value="">Select clarity</option>
                        <option value="FL" {{$dimond->clarity == 'FL' ? 'selected' : ''}}>FL</option>
                        <option value="IF" {{$dimond->clarity == 'IF' ? 'selected' : ''}}>IF</option>
                        <option value="VVS1" {{$dimond->clarity == 'VVS1' ? 'selected' : ''}}>VVS1</option>
                        <option value="VVS2" {{$dimond->clarity == 'VVS2' ? 'selected' : ''}}>VVS2</option>
                        <option value="VS1" {{$dimond->clarity == 'VS1' ? 'selected' : ''}}>VS1</option>
                        <option value="VS2" {{$dimond->clarity == 'VS2' ? 'selected' : ''}}>VS2</option>
                        <option value="SI1" {{$dimond->clarity == 'SI1' ? 'selected' : ''}}>SI1</option>
                        <option value="SI2" {{$dimond->clarity == 'SI2' ? 'selected' : ''}}>SI2</option>
                        <option value="SI3" {{$dimond->clarity == 'SI3' ? 'selected' : ''}}>SI3</option>
                        <option value="I1" {{$dimond->clarity == 'I1' ? 'selected' : ''}}>I1</option>
                        <option value="I2" {{$dimond->clarity == 'I2' ? 'selected' : ''}}>I2</option>
                        <option value="I3" {{$dimond->clarity == 'I3' ? 'selected' : ''}}>I3</option>
                     </select>
                  </div>
               </div>
               <div class="col-4">
                  <div class="form-group">
                     <label for="color">Color</label>
                     <select name="color" id="color" class="custom-select" required>
                        <option value="">Select color</option>
                        <option value="D" {{$dimond->color == 'D' ? 'selected' : ''}}>D</option>
                        <option value="E" {{$dimond->color == 'E' ? 'selected' : ''}}>E</option>
                        <option value="F" {{$dimond->color == 'F' ? 'selected' : ''}}>F</option>
                        <option value="G" {{$dimond->color == 'G' ? 'selected' : ''}}>G</option>
                        <option value="H" {{$dimond->color == 'H' ? 'selected' : ''}}>H</option>
                        <option value="I" {{$dimond->color == 'I' ? 'selected' : ''}}>I</option>
                        <option value="J" {{$dimond->color == 'J' ? 'selected' : ''}}>J</option>
                        <option value="K" {{$dimond->color == 'K' ? 'selected' : ''}}>K</option>
                        <option value="L" {{$dimond->color == 'L' ? 'selected' : ''}}>L</option>
                        <option value="M" {{$dimond->color == 'M' ? 'selected' : ''}}>M</option>
                        <option value="N" {{$dimond->color == 'N' ? 'selected' : ''}}>N</option>
                        <option value="O" {{$dimond->color == 'O' ? 'selected' : ''}}>O</option>
                        <option value="P" {{$dimond->color == 'P' ? 'selected' : ''}}>P</option>
                        <option value="Q" {{$dimond->color == 'Q' ? 'selected' : ''}}>Q</option>
                        <option value="R" {{$dimond->color == 'R' ? 'selected' : ''}}>R</option>
                        <option value="S" {{$dimond->color == 'S' ? 'selected' : ''}}>S</option>
                     </select>
                  </div>
               </div>
            </div>
            <div class="row">
               <div class="col-4">
                  <div class="form-group">
                     <label for="cut">Cut</label>
                     <select name="cut" id="cut" class="custom-select" required>
                        <option value="">Select cut</option>
                        <option value="Ideal" {{$dimond->cut == 'Ideal' ? 'selected' : ''}}>Ideal</option>
                        <option value="EX" {{$dimond->cut == 'EX' ? 'selected' : ''}}>EX</option>
                        <option value="VG" {{$dimond->cut == 'VG' ? 'selected' : ''}}>VG</option>
                        <option value="GD" {{$dimond->cut == 'GD' ? 'selected' : ''}}>GD</option>
                     </select>
                  </div>
               </div>
               <div class="col-4">
                  <div class="form-group">
                     <label for="polish">Polish</label>
                     <select name="polish" id="polish" class="custom-select" required>
                        <option value="">Select polish</option>
                        <option value="Ideal" {{$dimond->polish == 'Ideal' ? 'selected' : ''}}>Ideal</option>
                        <option value="EX" {{$dimond->polish == 'EX' ? 'selected' : ''}}>EX</option>
                        <option value="VG" {{$dimond->polish == 'VG' ? 'selected' : ''}}>VG</option>
                        <option value="GD" {{$dimond->polish == 'GD' ? 'selected' : ''}}>GD</option>
                     </select>
                  </div>
               </div>
               <div class="col-4">
                  <div class="form-group">
                     <label for="symmetry">Symmetry</label>
                     <select name="symmetry" id="symmetry" class="custom-select" required>
                        <option value="Ideal" {{$dimond->symmetry == 'Ideal' ? 'selected' : ''}}>Ideal</option>
                        <option value="EX" {{$dimond->symmetry == 'EX' ? 'selected' : ''}}>EX</option>
                        <option value="VG" {{$dimond->symmetry == 'VG' ? 'selected' : ''}}>VG</option>
                        <option value="GD" {{$dimond->symmetry == 'GD' ? 'selected' : ''}}>GD</option>
                     </select>
                  </div>
               </div>
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
   $(function() {

      $("form[name='editdimondform']").validate({
         rules: {
            parties_id: {
               required: true,
            },
            dimond_name: {
               required: true,
            },
            shape: {
               required: true,
            },
            weight: {
               required: true,
            },
            clarity: {
               required: true,
            },
            color: {
               required: true,
            },
            cut: {
               required: true,
            },
            polish: {
               required: true,
            },
            symmetry: {
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