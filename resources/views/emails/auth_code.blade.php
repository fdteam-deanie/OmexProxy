<!DOCTYPE html>
<html>
<head>
    <title>{{config('app.url')}}</title>
</head>
<body>
<div style="height: 55px;
    background-repeat: no-repeat;
    background-size: 55px;
    background-position: 0 50%;
    padding-left: 65px;
    display: flex;
    align-items: center;">
    <div ><h1>omex</h1>
    </div></div>
<p>Hello,</p>
<p>Your authentication code: </p>
<h1>{{ $code }}</h1>

<p>Thanks!</p>
</body>
</html>
