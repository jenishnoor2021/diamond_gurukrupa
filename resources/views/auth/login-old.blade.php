<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta name="description" content="" />
  <meta name="author" content="" />
  <title>Dashtreme Admin - Free Dashboard for Bootstrap 4 by Codervent</title>
  <!-- loader-->
  <!-- <link href="{{asset('assets/css/pace.min.css')}}" rel="stylesheet" /> -->
  <!-- <script src="{{asset('assets/js/pace.min.js')}}"></script> -->
  <!--favicon-->
  <link rel="icon" href="{{asset('assets/images/favicon.ico')}}" type="image/x-icon">
  <!-- Bootstrap core CSS-->
  <link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" />
  <!-- animate CSS-->
  <link href="{{asset('assets/css/animate.css')}}" rel="stylesheet" type="text/css" />
  <!-- Icons CSS-->
  <link href="{{asset('assets/css/icons.css')}}" rel="stylesheet" type="text/css" />
  <!-- Custom Style-->
  <link href="{{asset('assets/css/app-style.css')}}" rel="stylesheet" />

</head>

<body class="bg-theme bg-theme1">

  <!-- Start wrapper-->
  <div id="wrapper">

    <div class="loader-wrapper">
      <div class="lds-ring">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
      </div>
    </div>
    <div class="card card-authentication1 mx-auto my-5">
      <div class="card-body">
        <div class="card-content p-2">
          <div class="text-center">
            <img src="{{asset('assets/images/logo-icon.png')}}" alt="logo icon">
          </div>
          <div class="card-title text-uppercase text-center py-3">Sign In</div>
          @if (\Session::has('alert'))
          <div class="alert alert-danger p-1">
            {!! \Session::get('alert') !!}
          </div>
          @endif
          <form action="{{route('login')}}" method="POST">
            @csrf
            <div class="form-group">
              <label for="username" class="sr-only">Username</label>
              <div class="position-relative has-icon-right">
                <input type="text" name="username" id="username" class="form-control input-shadow" placeholder="Enter Username">
                <div class="form-control-position">
                  <i class="icon-user"></i>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label for="password" class="sr-only">Password</label>
              <div class="position-relative has-icon-right">
                <input type="password" name="password" id="password" class="form-control input-shadow" placeholder="Enter Password">
                <div class="form-control-position">
                  <i class="icon-lock"></i>
                </div>
              </div>
            </div>
            <button type="submit" class="btn btn-light btn-block">Sign In</button>

          </form>
        </div>
      </div>
    </div>

  </div><!--wrapper-->

  <!-- Bootstrap core JavaScript-->
  <script src="{{asset('assets/js/jquery.min.js')}}"></script>
  <script src="{{asset('assets/js/popper.min.js')}}"></script>
  <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>

  <!-- sidebar-menu js -->
  <script src="{{asset('assets/js/sidebar-menu.js')}}"></script>

  <!-- Custom scripts -->
  <script src="{{asset('assets/js/app-script.js')}}"></script>

</body>

</html>