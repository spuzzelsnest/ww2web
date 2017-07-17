<!doctype html>
<html>
<head>
  <meta  name="WW2Web" content="HTML5,CSS3,JavaScript" charset="utf-8">
  <meta name="viewport" content="target-densitydpi=device-dpi">
  <!--[if IE]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  {!! HTML::style('css/adminStyle.css') !!}
  {!! HTML::style('css/bootstrap.min.css') !!}
  {!! HTML::style('//code.jquery.com/ui/1.11.1/themes/smoothnss/jquery-ui.css') !!}
  {!! HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js') !!}
  {!! HTML::script('//code.jquery.com/ui/1.11.1/jquery-ui.js') !!}
  <Title>{!! $title !!}</Title>
    <script>
	@yield('script')
    </script>
</head>
<body>
   <section>
	@yield('mainbody')
  </section>

	 <script src="js/bootstrap.min.js"></script>
</body>
</html>