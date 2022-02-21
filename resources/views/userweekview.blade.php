<!doctype html>
<html lang="en">
@if(session()->has('isLoggedIn') && (strcmp($_COOKIE['user_role'],"user")))
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>Week View</title>
</head>
<body>
<!-- sidebar -->
<div class="sidenav">
    <header>Progress Update System</header>
    <hr>
    <a href="#" class="item-nav active ">Dashboard</a>
    <a href="{{ route('logout') }}" class="item-nav">Logout</a>
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
                    Date: dd/mm/yyyy
                </div>
                <div class="col-sm-1 bg-light">
                    <a href="#" class="btn btn-sm btn-success">Add Tasks</a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Tasks</th>
                        <th>Description</th>
                        <th>Task status</th>
                    </tr>
                    <tr>
                        <td>
                            <select class="form-control">
                                <option>Task 1</option>
                                <option>Task 2</option>
                                <option>Task 3</option>
                                <option>Task 4</option>
                            </select>
                        </td>
                        <td>Done XYZ, meeting with AAA</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <select class="form-control">
                                <option>Task 1</option>
                                <option>Task 2</option>
                                <option>Task 3</option>
                                <option>Task 4</option>
                            </select>
                        </td>
                        <td>Done XYZ, meeting with AAA</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <select class="form-control">
                                <option>Task 1</option>
                                <option>Task 2</option>
                                <option>Task 3</option>
                                <option>Task 4</option>
                            </select>
                        </td>
                        <td>Done XYZ, meeting with AAA</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <select class="form-control">
                                <option>Task 5</option>
                                <option>Task 2</option>
                                <option>Task 3</option>
                                <option>Task 4</option>
                            </select>
                        </td>
                        <td>Done XYZ, meeting with AAA</td>
                        <td></td>
                    </tr>
                </table>
            </div>
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
