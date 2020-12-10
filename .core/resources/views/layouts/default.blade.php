<!doctype html>
<html>
<head>
	<meta name="destription" content="World War 2 then and now picture pinned on the more or less original location." charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
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

	<link href="{{ asset('css/MarkerCluster.css') }}" rel="stylesheet" >
	<link href="{{ asset('css/MarkerCluster.Default.css') }}" rel="stylesheet" >
	<script src="{{ asset('js/leaflet.markercluster-src.js') }}"></script>
	<Title>{{ $title }}</Title>
</head>
<body>
 <div id="map"></div>
	@yield('mainbody')
	@include('layouts.footer')
</body>
</html>
