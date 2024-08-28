<!DOCTYPE HTML>
<html lang="ru">
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>T</title>
    @vite('resources/css/reset.css')
    @vite('resources/css/fonts/fonts.css')
    @vite('resources/css/style.css')
    @vite('resources/css/auth.css')
    <style>
        body {
            background-color:#f1f5f9;
        }
    </style>
</head>
<body>
	<div id="mfa"></div>
    @vite('resources/js/auth/mfa.js')
</body>
</html>
