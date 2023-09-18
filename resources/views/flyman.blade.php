<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body class="text-center py-5 bg-light" style="max-width: 400px; margin: 0 auto;">
    <div class="container">

        <h2 class="mb-4">Flyman Creater</h2>

        @if ($path ?? null)
            <img class="animate__animated animate__backInDown my-4" src={{ asset($path) }} alt="Flyman Image">
        @endif

        {{-- homeに戻る --}}
        <div class="mt-4">
            <a href="/" class="btn btn-primary">戻る</a>
        </div>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

</body>

</html>
