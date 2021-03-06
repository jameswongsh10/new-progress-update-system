<!DOCTYPE html>
<html lang="en">
@if(isset($_COOKIE["isLoggedIn"]) && (!strcmp($_COOKIE['user_role'],"admin") || !strcmp($_COOKIE['user_role'],"viewer")))
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <title>Daily Report</title>
    </head>
    <body>

    <img src="..\images\Landscape.png" width="100px" height="50px"/>
    <h1>Daily Report</h1>

    <h4 style="font-family:'Courier New',serif; font-weight:normal;">Name: {{$user->name}}</h4>
    <h4 style="font-family:'Courier New',serif; font-weight:normal;">Date: {{$date}}</h4>
    <hr>

    @csrf
    <?php $i = 1; ?>
    @foreach ($groupByDay as $day => $value)
        <h3><?php echo $day; ?></h3>
        @foreach($question_array as $ans)
            @if(!strcmp($day,date("Y-m-d",strtotime($ans[0]))))
                <h5><?php echo $i . ". " . $ans[1]; ?></h5>
                <pre><?php echo "Answer: " . $ans[2]; $i++;?></pre>
            @endif
        @endforeach
    @endforeach

    </body>
@else
    <meta http-equiv="refresh" content="0;url={{route('logout')}}">
@endif
</html>

<style>
    h1, h4 {
        font-family: 'Courier New', serif;
    }
</style>

