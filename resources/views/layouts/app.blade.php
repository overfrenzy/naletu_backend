<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- ... other head elements ... -->

    <!-- Include Axios via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body>
    <!-- ... your content ... -->

    <!-- Livewire Scripts -->
    @livewireScripts

    <!-- Filament Scripts -->
    @filamentScripts

    <!-- Include Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Include Your Custom Script After Filament's Scripts -->
    <script src="{{ asset('build/assets/filament.js') }}"></script>
</body>
</html>
