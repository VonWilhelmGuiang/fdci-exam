<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <script>
        if(localStorage.getItem('token')){
           location.replace('/contacts')
        }
    </script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    @foreach ($css as $cssitem)
        <link rel="stylesheet" href="{{asset($cssitem)}}">
    @endforeach
</head>
<body  class="hold-transition login-page">
    
    <div class="login-box">
        <!-- /.login-logo -->
        <div class="card card-outline card-primary">
          <div class="card-header text-center">
            <a href="../../index2.html" class="h1"><b>Contact</b>System</a>
          </div>
          <div class="card-body">
            <p class="login-box-msg">Login</p>
      
            <form id="login-form">
              <div class="input-group mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                  </div>
                </div>
              </div>
              <div class="input-group mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-8">
                  <div class="icheck-primary">
                    <input type="checkbox" id="remember">
                    <label for="remember">
                      Remember Me
                    </label>
                  </div>
                </div>
                <!-- /.col -->
                <div class="col-4">
                  <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                </div>
                <!-- /.col -->
              </div>
            </form>

            <div class="social-auth-links text-center mt-2 mb-3">
                <a href="/register" class="btn btn-block btn-danger">
                    Register a Account
                </a>
            </div>
            <!-- /.social-auth-links -->
        
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.login-box -->
</body>
    @foreach ($js as $item)
        <script src="{{asset($item)}}"></script>
    @endforeach
</html>