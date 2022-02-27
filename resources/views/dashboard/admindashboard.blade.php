<!doctype html>
<html lang="en">
{{-- At the top of every view page, this is used for user authentication. If the user is logged in and is either admin or viewer, they will
 see this page. Else, they will be redirected to the login page. --}}
@if(isset($_COOKIE["isLoggedIn"]) && (!strcmp($_COOKIE['user_role'],"admin") || !strcmp($_COOKIE['user_role'],"viewer")))
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
              integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
              crossorigin="anonymous">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <title>Dashboard</title>
    </head>
    <body>


    <!-- sidebar -->
    <div class="sidenav">
        <header>Progress Update System</header>
        <hr>
        <a href="{{ route('dashboard') }}" class="item-nav active ">Dashboard</a>
        <a href="{{ route('users.index') }}" class="item-nav">User Management</a>
        @if((!strcmp($_COOKIE['user_role'],"admin")))
            <a href="#settingmenu" data-bs-toggle="collapse" class="item-nav px-0 align-middle">
            <span class="ms-1 d-none d-sm-inline">Settings</span>
        </a>
        <ul class="collapse nav flex-column ms-1" id="settingmenu" data-bs-parent="#menu">
            <li class="w-100">
                <a href="{{ route('daily-report-setting.index') }}" class="item-nav custom-font-size px-0">Daily Report Settings</a>
            </li>
            <li>
                <a href="{{ route('teamsetting.index') }}" class="item-nav custom-font-size px-0">Team Settings</a>
            </li>
            <li class="w-100">
                <a href="{{ route('status-setting.index') }}" class="item-nav custom-font-size px-0">Status Settings</a>
            </li>
        </ul>
        @endif
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

        <!-- table for admin/viewer view -->
        <div class="card">
            <div class="card-header">
                Dashboard
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <tr>
                            <th>Team</th>
                            <th>Last Updated</th>
                        </tr>
                        @foreach($teams as $team)
                            <tr>
                                <td><a href="{{ route('teamview', $team->id) }}"
                                       class="a-custom-style">{{$team->team_name}}</a></td>
                                @php
                                    $latest_time = "2022-01-01 00:00:00";
                                    foreach ($team->users as $user){
                                        try {
                                            $newTime = \App\Models\Report::where('user_id',$user->id)->latest()->first()->created_at;
                                            if($newTime>=$latest_time){
                                                $latest_time = $newTime;
                                            }
                                        }catch(\Exception $e){
                                        }
                                    }
                                @endphp
                                <td>Last updated on {{$latest_time}}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
            crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="{{asset('js/script.js')}}"></script>
    </body>
@else
    <meta http-equiv="refresh" content="0;url={{route('logout')}}">
@endif
</html>
