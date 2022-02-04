<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Log in</title>
</head>
<body>
<div class="container-fluid py-4 vh-100 custom-gradient">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-8 col-lg-6 col-xl-5">
            <div class="card shadow" style="border-radius: 1rem;">
                <div class="card-body p-5 text-center">
                    <h3 class="mb-5">Sign in</h3>

                    @if( session()->get('fail'))
                        <div class="alert alert-danger">
                            {{ session()->get('fail') }}
                        </div>
                    @endif

                    <form autocomplete="off" action="{{ route('check') }}" method="post">
                        @csrf
                        <div class="form-outline mb-4">
                            <input name="email" type="email" class="form-control form-control-lg" placeholder="Email" >
                            @if ($errors->has('email'))
                                <span class="text-danger">
                                    {{ $errors->first('email') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-outline mb-4">
                            <input name="password" type="password" class="form-control form-control-lg" placeholder="Password">
                            <span class="text-danger">@error('password'){{ $message }} @enderror</span>
                        </div>
                        <button type="submit" class="btn btn-block btn-primary">Sign In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
