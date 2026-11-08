
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'AttendanceTrack')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Arabic Font -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }
    </style>

    @stack('styles')
</head>

<body class="min-h-screen bg-slate-100">

    <div class="min-h-screen flex flex-col">

        <!-- Header -->
        @include('partials.header')

        <!-- Main Layout -->
        <div class="flex flex-1 min-h-0">

            <!-- Sidebar -->
            @include('partials.sidebar')

            <!-- Page Content -->
            <main class="flex-1 min-w-0 p-5 md:p-8 overflow-x-auto">

                @yield('content')

            </main>

        </div>

        <!-- Footer -->
        @include('partials.footer')

    </div>

    @stack('scripts')

</body>
</html>
