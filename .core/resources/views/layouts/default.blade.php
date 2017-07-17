<!doctype html>
<html>
<head>
	<meta  name="WW2Web" content="HTML5,CSS3,JavaScript" charset="utf-8">
	<meta name="viewport" content="target-densitydpi=device-dpi">
	<!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	{!! HTML::script('http://maps.google.com/maps/api/js?sensor=false') !!}
	{!! HTML::script('js/markerclusterer.js') !!}
	
	{!! HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js') !!}
	{!! HTML::script('//code.jquery.com/ui/1.11.1/jquery-ui.js') !!}

	{!! HTML::style('css/mainStyle.css') !!}
	{!! HTML::style('css/bootstrap.min.css') !!}
	{!! HTML::style('//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css') !!}

	<Title>{{ $title }}</Title>
		<script>
			@yield('script')
		</script>
</head>
<body>
	<img src="img/bg.jpg" id="bg"/>
	<img src="img/banner.jpg" id="banner"/>
	<img src="img/question.png" id="question" alt="question" title="Click the checkbox to add / remove the markers. 
	Scroll into the map to see more details"/>
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