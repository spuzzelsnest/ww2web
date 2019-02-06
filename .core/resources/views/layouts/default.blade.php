<!doctype html>
<html>
<head>
	<meta  name="WW2Web" content="Html5,CSS3,JavaScript" charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<!--[if IE]>
		<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<link href="css/mainStyle.css" rel="stylesheet" >
	<script src="js/jquery-3.3.1.slim.min.js"></script>
	<link href="css/bootstrap.min.css" rel="stylesheet" >
	<script src="js/bootstrap.min.js"></script>

	<link href="css/leaflet.css" rel="stylesheet" >
	<script src="js/leaflet.js"></script>


	<link href="css/Markercluster.css" rel="stylesheet" >
	<link href="css/Markercluster.default.css" rel="stylesheet" >
	<script src="js/leaflet.markercluster-src.js"></script>

	<script src="js/responsivevoice.js"></script>
	<Title>{{ $title }}</Title>
</head>
<body>
	<img src="img/banner.jpg" id="banner"/>
	<img src="img/question.png" id="question" alt="question" title="Click the checkbox to add / remove the markers. Scroll into the map to see more details"/>
	@yield('mainbody')
	@include('layouts.legenda')
	@include('layouts.footer')
</body>
</html>
