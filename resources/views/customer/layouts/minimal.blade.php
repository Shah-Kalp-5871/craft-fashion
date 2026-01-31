<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('constants.SITE_NAME'))</title>
    <!-- Add minimal styles here -->
</head>
<body class="bg-gray-50">
    @yield('content')
</body>
</html>