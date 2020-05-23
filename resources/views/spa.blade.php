<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>FAF Map picker</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div id="app">
            <main-page></main-page>
        </div>
    </body>
    @routes
    <script>
        @if (isset($loggedInUser))
            var loggedInUser = {!! $loggedInUser !!};
        @else
            var loggedInUser = false;
        @endif
    </script>
    <script src="{{ asset('js/app.js') }}?version=2"></script>
</html>
