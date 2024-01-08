<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Thank You Page</title>
    <script>
        if(localStorage.getItem('register')){
            localStorage.removeItem('register');
        }else{
            location.replace('/login')
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
            <p class="login-box-msg">Thank you for registering.</p>
            <div class="social-auth-links text-center mt-2 mb-3">
                <button id="proceed" class="btn btn-primary">Continue</button>
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
    <script>
        $(function(){
            $(document).on('click','#proceed',function(){
                location.replace('/contacts')
            })
        })
    </script>
</html>