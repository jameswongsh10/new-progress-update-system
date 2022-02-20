<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<h1 style="font-family:'Courier New',serif">Report of {{$user->name}} on {{$date}}</h1>
<br>
@csrf
@foreach($question_array as $ans)
    <div class="form-group">
        <h4><?php echo "Question: " . $ans[0]; ?></h4>
        <pre><?php echo "Answer: " . $ans[1]; ?></pre>
    </div>
@endforeach
</body>
</html>
