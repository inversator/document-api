<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Documents api by vue</title>
    <link rel="stylesheet" href="{{asset('/css/app.css')}}" type="text/css"/>
</head>
<body>
<div id="app">
    <router-view name="Documents"></router-view>
    <router-view></router-view>
</div>
<script src="{{asset('/js/app.js')}}"></script>
</body>
</html>
