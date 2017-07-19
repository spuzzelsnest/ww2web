<!doctype html>
<html>
<head>
  <meta  name="WW2Web" content="HTML5,CSS3,JavaScript" charset="utf-8">
  <meta name="viewport" content="target-densitydpi=device-dpi">
  <!--[if IE]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <link href="css/adminStyle.css" rel="stylesheet">
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="//code.jquery.com/ui/1.11.1/themes/smoothnss/jquery-ui.css" rel="stylesheet">
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
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
