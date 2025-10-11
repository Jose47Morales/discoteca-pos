<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso - Discoteca POS</title>
    @vite('resources/css/app.css')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="h-screen flex items-center justify-center bg-gradient-to-br from-gray-900 via-black-900 to-gray-800">
    <div class="w-full max-w-md p-8 space-y-6 bg-gray-900/90 backdrop-blur-lg rounded-xl shadow-2xl">
        <h1 class="text-2xl font-bold text-center text-transparent bg-clip-text bg-gradient-to-r from-pink-500 to-purple-500 mb-6">
            Acceso - Discoteca POS
        </h1>

        @yield('content')
    </div>
</body>

</html>