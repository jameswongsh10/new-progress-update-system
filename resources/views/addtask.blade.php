<!doctype html>
<html lang="en">
@if(!strcmp($_COOKIE["online"],"true"))

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Add task</title>
</head>
<body>
<!-- sidebar --->
<div class="sidenav">
    <header>Progress Update System</header>
    <hr>
    <a href="{{ route('userdashboard') }}" class="item-nav active ">Dashboard</a>
    <a href="{{ route('logout') }}" class="item-nav">Logout</a>
</div>

<!-- main content -->
<div class="main">
    <!-- navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
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
            <form autocomplete="off" method="post" action="#">
                <div class="form-group">
                    <label>Name of the task</label>
                    <select id="taskname" name="tasks" class="form-control">
                        <option value="task1">Task 1</option>
                        <option value="task2">Task 2</option>
                        <option value="task3">Task 3</option>
                        <option value="task4">Task 4</option>
                        <option value="newtask">Add a new task</option>
                    </select>
                </div>
                <div id="shownewtask" class="form-group">
                    <label>Name of new task</label>
                    <input autocomplete="off" type="search" name="newtask" class="form-control" />
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <input autocomplete="off" type="search" name="description" class="form-control" />
                </div>
                @foreach($settings as $setting)
                    <div class="form-group">
                        <label>{{$setting->progress_title}}</label>
                        <input autocomplete="=off" type="search" name={{$setting->id}} class="form-control" />
                    </div>
                @endforeach
                <br>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script type="text/javascript" src="{{asset('js/script.js')}}"></script>
</body>
@else
    <meta http-equiv="refresh" content="0;url={{route('logout')}}">
@endif
</html>
