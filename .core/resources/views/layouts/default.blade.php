<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="World War 2 then and now footage from 1939 to 1945 pinned on the more or less original location.">
    <meta name="keywords" content="then and now, second world war, world war 2, tracesofwar, pictures, footages">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ $title }}</title>

    {{-- Library CSS first, our own CSS last so it wins --}}
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/leaflet.css') }}" rel="stylesheet">
    <link href="{{ asset('css/MarkerCluster.css') }}" rel="stylesheet">
    <link href="{{ asset('css/MarkerCluster.Default.css') }}" rel="stylesheet">
    <link href="{{ asset('css/mainStyle.css') }}?v=2" rel="stylesheet">

    {{-- Scripts: jQuery before anything that uses it, Leaflet before its plugins --}}
    <script src="{{ asset('js/jquery-3.3.1.slim.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/leaflet.js') }}"></script>
    <script src="{{ asset('js/leaflet.markercluster-src.js') }}"></script>
    <script src="{{ asset('js/responsivevoice.js') }}"></script>
</head>
<body>
    <div id="map"></div>
    @yield('mainbody')
    @include('layouts.footer')
</body>
</html>