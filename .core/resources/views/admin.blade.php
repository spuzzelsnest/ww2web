@extends ('layouts.admin')

@section('mainbody')
<script src="//maps.googleapis.com/maps/api/js?key=AIzaSyAF7IPUAyR60tGxGYXPoyNobdtOJJm8cIc&callback=initMap"></script>

<div class="container">
		<script type="text/javascript">
			function updateMarkerStatus(str) {
			  document.getElementById('markerStatus').innerHTML = str;
			}

			function updateMarkerPosition(latLng) {
				document.getElementById('lat').value = latLng.lat();
				document.getElementById('lng').value = latLng.lng();
			}

			function initialize() {
				var latLng = new google.maps.LatLng(49,1);
				  var map = new google.maps.Map(document.getElementById('mapAdmin'), {
				    zoom: 4,
				    center: latLng,
				    mapTypeId: 'hybrid'
				  });
				  var marker = new google.maps.Marker({
				    position: latLng,
				    map: map,
				    draggable: true
				  });

			  updateMarkerPosition(latLng);

			  google.maps.event.addListener(marker, 'drag', function() {
			    updateMarkerStatus('Dragging...');
			    updateMarkerPosition(marker.getPosition());
			  });

			  google.maps.event.addListener(marker, 'dragend', function() {
			    updateMarkerStatus('Dragg ended ');
			    geocodePosition(marker.getPosition());
			  });
			}

			google.maps.event.addDomListener(window, 'load', initialize);
		</script>
	<div class='col-lg-8'>
		<div id="markerStatus"><i>Click and drag the marker.</i></div>
		<div id="mapAdmin"></div>
	</div>
		<div class="formLayout">
{{--open form--}}



@endsection
