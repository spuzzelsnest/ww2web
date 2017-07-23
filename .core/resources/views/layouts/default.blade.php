<!doctype html>
<html>
<head>
	<meta  name="WW2Web" content="Html5,CSS3,JavaScript" charset="utf-8">
	<meta name="viewport" content="width=device-width">
	<!--[if IE]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyAF7IPUAyR60tGxGYXPoyNobdtOJJm8cIc&callback"></script>
	<script src="js/markerclusterer.js"></script>

	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>

	<link href="css/mainStyle.css" rel="stylesheet" >
	<link href="css/bootstrap.min.css" rel="stylesheet" >
	<link href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css" rel="stylesheet" >

	<Title>{{ $title }}</Title>
		<script>
			@yield('script')
		</script>
</head>
<body>
	<img src="img/bg.jpg" id="bg"/>
	<img src="img/banner.jpg" id="banner"/>
	<img src="img/question.png" id="question" alt="question" title="Click the checkbox to add / remove the markers. Scroll into the map to see more details"/>
	<img src="img/camera1.png" id="camera1"/>
	<img src="img/camera2.png" id="camera2"/>
		@include('layouts.header')
	<section>
		@yield('mainbody')
	</section>
	@include('layouts.footer')
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
