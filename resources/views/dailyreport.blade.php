@php
    use App\Models\Task;
@endphp
<!doctype html>
<html lang="en">
@if(isset($_COOKIE["isLoggedIn"]) && (!strcmp($_COOKIE['user_role'],"user")))
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Daily Report</title>
</head>
<body>
<!-- sidebar --->
<div class="sidenav">
    <header>Progress Update System</header>
    <hr>
    <a href="{{route('userdashboard.index')}}" class="item-nav active ">Dashboard</a>
    <a href="{{ route('logout') }}" class="item-nav">Logout</a>
</div>

<!-- main content -->
<div class="main">
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <h3><a href="javascript:history.back()" class="btn btn-sm btn-secondary">Back</a></h3>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <h6 class="nav-link"><?php echo \App\Models\User::find($_COOKIE["isLoggedIn"])->name; ?></h6>
                </li>
            </ul>
        </div>
    </nav>

    <!-- table -->
    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm">
                    Date: {{$today}}
                </div>
            </div>
        </div>
        <div class="card-body">

            @if( session()->get('success'))
                <div class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif

            <form autocomplete="off" method="post" action="{{route("dailyreportstore")}}">
                @csrf
                Daily Report
                <hr>
                @foreach($settings as $setting)
                    <div class="form-group">
                        <label>{{$setting->progress_title}}</label>
                        <input autocomplete="=off" type="search" name="{{$setting->html_name}}" class="form-control" />
                        <span class="text-danger">@error ($setting->html_name) {{$message}} @enderror</span>
                    </div>
                @endforeach
                <br>
                <br>
                <div class="form-group">
                    <button type="submit" onClick="return confirm('Please make sure that the details are correct.')" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="{{asset('js/script.js')}}"></script>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#taskname').change(function(e){
        e.preventDefault();

        let task_id = $(this).val()
        console.log(task_id)
        if (task_id !== 'newTaskOption') {
            $.ajax({
                type:'POST',
                url:"{{route('getTaskId')}}",
                data:{taskId:task_id},
                dataType: 'json',
                success:function(data){
                    $("#description").val(data.desc).prop("disabled", true)
                    $("#start_date").val(data.start_date)
                    $("#end_date").val(data.end_date)
                }
            });
        }
    })

</script>
</body>
@else
    <meta http-equiv="refresh" content="0;url={{route('logout')}}">
@endif
</html>
