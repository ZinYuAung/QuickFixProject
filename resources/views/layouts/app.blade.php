<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'QuickFix')
    </title>

    <!-- Bootstrap 5 -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <!-- QuickFix Global CSS -->
    <link rel="stylesheet"
          href="{{ asset('css/quickfix.css') }}">

          <link rel="stylesheet" href="{{ asset('css/alert.css') }}">

    @stack('styles')
</head>

<body>

    @yield('content')

    <!-- Bootstrap JS -->
    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    <!-- QuickFix JS -->
    <script src="{{ asset('js/quickfix.js') }}"></script>

    @stack('scripts')

</body>
</html>