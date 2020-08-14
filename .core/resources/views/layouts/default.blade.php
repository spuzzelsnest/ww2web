<!doctype html>
<html>
<head>
	<meta  name="WW2Web" content="Html5,CSS3,JavaScript" charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--[if IE]>
		<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<link href="{{ asset('css/mainStyle.css') }}" rel="stylesheet" >
	<script src="{{ asset('js/jquery-3.3.1.slim.min.js') }}"></script>
	<link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" >
	<script src="{{ asset('js/bootstrap.min.js') }}"></script>

	<link href="{{ asset('css/leaflet.css') }}" rel="stylesheet" >
	<script src="{{ asset('js/leaflet.js') }}"></script>

	<script src="{{ asset('js/responsivevoice.js') }}"></script>

	<link href="{{ asset('css/Markercluster.css') }}" rel="stylesheet" >
	<link href="{{ asset('css/Markercluster.default.css') }}" rel="stylesheet" >
	<script src="{{ asset('js/leaflet.markercluster-src.js') }}"></script>
	<Title>{{ $title }}</Title>
</head>
<body>
 <div id="map"></div>
	@include('layouts.header')
	@yield('mainbody')
	@include('layouts.footer')
</body>
</html>
