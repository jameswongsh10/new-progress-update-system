@php
    use App\Models\StatusTask;
    use App\Models\Status;
@endphp

<!doctype html>
<html lang="en">
@if(isset($_COOKIE["isLoggedIn"]) && (!strcmp($_COOKIE['user_role'],"admin") || !strcmp($_COOKIE['user_role'],"viewer")))
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
              crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <title>Month View</title>
    </head>
    <body>
    <!-- sidebar -->
    <div class="sidenav">
        <header>Progress Update System</header>
        <hr>
        <a href="{{ route('dashboard') }}" class="item-nav active ">Dashboard</a>
        <a href="{{ route('users.index') }}" class="item-nav">User Management</a>
        <a href="#settingmenu" data-bs-toggle="collapse" class="item-nav px-0 align-middle">
            <span class="ms-1 d-none d-sm-inline">Settings</span>
        </a>
        <ul class="collapse nav flex-column ms-1" id="settingmenu" data-bs-parent="#menu">
            <li class="w-100">
                <a href="{{ route('daily-report-setting.index') }}" class="item-nav custom-font-size px-0">Daily Report
                    Settings</a>
            </li>
            <li>
                <a href="{{ route('teamsetting.index') }}" class="item-nav custom-font-size px-0">Team Settings</a>
            </li>
        </ul>
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

        <div class="card" >
            <div class="card-header">
                <div class="row">
                    <div class="col-sm">
                        <h3><?php echo DateTime::createFromFormat('!m', $date)->format('F'); ?></h3>
                    </div>
                    <div class="col-sm-3 bg-light">
                        <div class="input-group">
                            @csrf
                            <form>
                                <input type="text" id="filterKeyword" class="form-control"/>
                            </form>
                            <button class="btn btn-submit btn-secondary">Filter</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if( session()->get('success'))
                    <div class="alert alert-success">
                        {{ session()->get('success') }}
                    </div>
                @endif
                <?php $i = 0; ?>
                @foreach($groupByTaskID as $singleTask)
                    <h5><?php $newTask = $taskTitleArray[$i]; echo $newTask->task_title; $i++; ?></h5>
                    <div class="table-responsive" >
                        <table class="table table-striped table-bordered" id="taskTable">
                            <tr>
                                <th>Date</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Remark</th>
                                @if(!strcmp($_COOKIE['user_role'],'admin'))
                                    <th>Edit</th>
                                @endif
                            </tr>

                            @forelse($statusTask as $task)
                                @if($task->task_id == $newTask->id)
                                <tr>
                                    <td>{{date('Y-m-d', strtotime($task->created_at))}}</td>
                                    <td>{{$task->task_description}}</td>
                                    @foreach($statuses as $status)
                                        @if($task->status_id == $status->id)
                                            <td>{{$status->status_title}}</td>
                                        @endif
                                    @endforeach
                                    <td>{{$task->task_remark}}</td>
                                    @if(!strcmp($_COOKIE['user_role'],'admin'))
                                        <td><a href="{{route('editTask',$task->id, )}}"
                                               class="btn btn-sm btn-warning">Edit</a></td>
                                    @endif
                                </tr>
                                @endif
                            @empty
                                <p>There is no task</p>
                            @endforelse

                        </table>
                    </div>
                    @endforeach
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="{{asset('js/script.js')}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(".btn-submit").click(function (e) {

            e.preventDefault();

            var keyword = document.getElementById("filterKeyword").value;
            $.ajax({
                type: 'POST',
                url: "{{route('getKeyword')}}",
                data: {myKeyword: keyword},
                success: function (data) {
                    window.location.href = "{{route('filteredView')}}";
                }
            });
        });
    </script>
    </body>
@else
    <meta http-equiv="refresh" content="0;url={{route('logout')}}">
@endif
</html>
