<!DOCTYPE html>
<html class="h-full bg-gray-100">
<head>
    <title>MC Manager</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0"/>
    @vite(['resources/js/app.ts', 'resources/css/app.css'])
    @inertiaHead
</head>
<body class="h-full">
@routes
@inertia
</body>
</html>
