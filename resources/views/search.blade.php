<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Parser</title>

</head>
<body>
<form method="post">
    <p>
        <label>
            <input type="text" name="str">
        </label>
    </p>
    <p><input type="submit" value="Отправить"></p>
</form>
@foreach($email as $city => $val)
    @foreach($val as $key => $value)
       {{$value}}
    @endforeach
@endforeach
<body/>
