<!doctype html>
<html class="no-js" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
        @section('title')
            Wiki | Default
        @stop
    </title>
    <link rel="stylesheet" href="css/foundation.css" />
    @yield('styles')
    <script src="js/vendor/modernizr.js"></script>
</head>
<body>
@yield('content')
<script src="js/vendor/jquery.js"></script>
<script src="js/foundation.min.js"></script>
<script>
    $(document).foundation();
</script>
@yield('scripts')
</body>
</html>
