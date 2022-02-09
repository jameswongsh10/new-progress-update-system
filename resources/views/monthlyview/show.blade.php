<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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
            <a href="{{ route('settings') }}" class="item-nav custom-font-size px-0">Progress Update Settings</a>
        </li>
        <li>
            <a href="{{ route('teamsetting.index') }}" class="item-nav custom-font-size px-0">Team Settings</a>
        </li>
        <li>
            <a href="{{ route('settings') }}" class="item-nav custom-font-size px-0">Role Access</a>
        </li>
    </ul>
    <a href="#" class="item-nav">Logout</a>
</div>

<!-- main content -->
<div class="main">
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <h6 class="nav-link">Name</h6>
                </li>
            </ul>
        </div>
    </nav>

    <!-- table -->

    <div class="card">
        <div class="card-header">
            <div class="row">
                <div class="col-sm">
                    Month
                </div>
                <div class="col-sm-3 bg-light">
                    <div class="input-group">
                        <div class="form-outline">
                            <input type="search" id="form1" class="form-control" />
                        </div>
                        <button type="button" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Tasks</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Remark</th>
                        <th>Edit</th>
                    </tr>
                    @forelse($tasks as $task)
                        <tr>
                            <td>{{$task->task_title}}</td>
                            <td>{{$task->task_description}}</td>
                            <td>
                                <select id="status" name="status" class="form-control">
                                    <option value="delay">Delay</option>
                                    <option value="completed">Completed</option>
                                    <option value="ongoing">On Going</option>
                                </select>
                            </td>
                            <td>{{$task->remark}}</td>
                            <td><a href="#" class="btn btn-sm btn-warning">Edit</a> </td>
                        </tr>
                    @empty
                        <p>There is no task</p>
                    @endforelse
                </table>
                <div class="d-flex justify-content-center">
                    {{$tasks->links()}}
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{asset('js/script.js')}}"></script>
</body>

</html>
