<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - @yield('title')</title>
    <!-- Compiled CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    @include('admin.layouts.navbar')

    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- Compiled JS -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css">


<!-- Include Bootstrap JS if needed -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JS for Cart Count -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" 
            integrity="sha384-XaTkjbZyUVlCFN+KmXjkVgKucOTvEBhzS5aUB+K/njDR8aONvyP6YL+Q71Kj7Qc9" 
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" 
            integrity="sha384-pPkmV66OFEjt68bxobSxMVqkhO/ENROeTOWSXssGq0HE11cbiS6L4AaFTxJ5k0l0" 
            crossorigin="anonymous"></script>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.jquery.min.js"></script>

    <!-- Initialize Chosen -->
    <script>
        $(document).ready(function() {
            $('.chosen-select').chosen({
                no_results_text: "Oops, nothing found!"
            });
        });
    </script>