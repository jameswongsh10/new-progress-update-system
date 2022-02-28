<!doctype html>
<html lang="en">
@php
    use App\Models\Task;
    use Illuminate\Support\Facades\Cookie;
@endphp
@if(isset($_COOKIE["isLoggedIn"]) && (!strcmp($_COOKIE['user_role'],"user")))
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
              crossorigin="anonymous">
        <link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <title>Add task</title>
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

                    @if($didReport)
                        <div class="col-sm-1 bg-light">
                            <a href=" {{ route('dailyreportedit') }}" class="btn btn-sm btn-success">Edit Daily
                                Report</a>
                        </div>
                    @else
                        <div class="col-sm-1 bg-light">
                            <a href=" {{ route('dailyreport') }}" class="btn btn-sm btn-success">Add Daily Report</a>
                        </div>
                    @endif

                </div>
            </div>
            <div class="card-body">
                <form autocomplete="off" method="post" action="{{route("userdashboard.store")}}">
                    @csrf
                    <div class="form-group">
                        <label>Name of the task</label>
                        <select id="taskname" name="tasks" class="form-control">
                            <option value="" selected="selected" disabled>Please select a task</option>
                            @foreach($tasks as $task)
                                <option value="{{$task->id}}">{{$task->task_title}}</option>
                            @endforeach
                            <option value="newTaskOption">Add a new task</option>
                        </select>
                    </div>
                    <div id="shownewtask" class="form-group">
                        <label>Name of new task</label>
                        <input autocomplete="off" type="search" name="newtask" class="form-control"/>
                        <span class="text-danger">@error ("newtask") {{$message}} @enderror</span>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input autocomplete="off" type="search" id="description" name="description"
                               class="form-control"/>
                        <span class="text-danger">@error ("description") {{$message}} @enderror</span>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="" selected="selected" disabled>Please select a status</option>
                            @foreach($statuses as $status)
                                <option value="{{$status->id}}"
                                        style="color:{{$status->colour}}">{{$status->status_title}}</option>
                            @endforeach
                        </select>
                        <span class="text-danger">@error ("status") {{$message}} @enderror</span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="start_date">Start Date</label>
                        <input class="form-control" id="start_date" name="start_date" placeholder="YYYY-MM-DD"
                               type="text"/>
                        <span class="text-danger">@error ("start_date") {{$message}} @enderror</span>
                    </div>
                    <div class="form-group">
                        <label class="control-label" for="end_date">End Date</label>
                        <input class="form-control" id="end_date" name="end_date" placeholder="YYYY-MM-DD" type="text"/>
                        <span class="text-danger">@error ("end_date") {{$message}} @enderror</span>
                    </div>
                    <br>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"
            integrity="sha256-eTyxS0rkjpLEo16uXTS0uVCS4815lc40K2iVpWDvdSY=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{asset('js/script.js')}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#taskname').change(function (e) {
            e.preventDefault();

            let task_id = $(this).val()
            if (task_id !== 'newTaskOption') {
                $.ajax({
                    type: 'POST',
                    url: "{{route('getTaskId')}}",
                    data: {taskId: task_id},
                    dataType: 'json',
                    success: function (data) {

                        $('#status option[value="' + data.status_id + '"]').prop('selected', true).trigger("change");
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
