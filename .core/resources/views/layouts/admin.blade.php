<!doctype html>
<html>
<head>
  <meta  name="WW2Web" content="HTML5,CSS3,JavaScript" charset="utf-8">
  <meta name="viewport" content="target-densitydpi=device-dpi">
  <!--[if IE]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
        <link href="css/adminStyle.css" rel="stylesheet" >
        <script src="js/jquery-3.3.1.slim.min.js"></script>
        <link href="css/bootstrap.min.css" rel="stylesheet" >
        <script src="js/bootstrap.min.js"></script>

        <link href="css/leaflet.css" rel="stylesheet" >
        <script src="js/leaflet.js"></script>

  <Title>{!! $title !!}</Title>
    <script>
	@yield('script')
    </script>
</head>
<body>
   <section>
	@yield('mainbody')
  </section>
</body>
</html>
