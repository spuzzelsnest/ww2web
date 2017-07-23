@extends ('layouts.admin')

@section('mainbody')

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
			{!! Form::open(array('route' => 'admin.store', 'files' => true)) !!}

	   		{{--SELECT MEDIA--}}
	   			<div class="form-group">
					@foreach ($types as $type)
						{!! Form::checkbox('typeId', $type->id) !!}
						{{$type->description}}
					@endforeach
				</div>
			<div class='col-lg-4'>
			{{--NAME--}}
						<div class="form-group">
							{!! Form::label('name','Name: ', array('class' => 'col-lg-3 control-label')) !!}
							{!! Form::text('name') !!}
						</div>
			{{--SELECT DATE--}}
						<div class="form-group">
							{!! Form::label('date','Date: ',  array('class' => 'col-lg-3 control-label')) !!}
							{!! Form::macro('date', function($name, $default = '1944/06/06', $attrs = array()){
									$item = '<input type="date" name="'. $name .'" ';
			 						if ($default) {	$item .= 'value="'. $default .'" ';}
			 						if (is_array($attrs)){
			 						 foreach ($attrs as $a => $k)
			      							$item .= $a .'="'. $k .'" ';
			  							} $item .= ">";
			 						  return $item;}); !!}
			 				{!!Form::date('date')!!}
						</div>
			{{--PLACE--}}
						<div class="form-group">
								{!! Form::label('place','Place: ', array('class' => 'col-lg-3 control-label')) !!}
								{!! Form::text('place') !!}
						</div>
			{{--COUNTRY--}}
						<div class="form-group">
								{!! Form::label('country','Country: ', array('class' => 'col-lg-3 control-label')) !!}
								{!! Form::text('country') !!}
						</div>
			{{--SOURCE--}}
						<div class="form-group">
								{!! Form::label('source','Source: ', array('class' => 'col-lg-3 control-label')) !!}
								{!! Form::text('source') !!}
						</div>
			{{--SCHORTDESC--}}
						<div class="form-group">
								{!! Form::label('shortdesc','Title: ', array('class' => 'col-lg-3 control-label')) !!}
								{!! Form::text('shortdesc') !!}
						</div>
			{{--INFO--}}
						<div class="form-group">
								{!! Form::label('info','Info: ', array('class' => 'col-lg-3 control-label')) !!}
								{!! Form::textarea('info') !!}
						</div>
			{{--REMARKS--}}
						<div class="form-group">
								{!! Form::label('remarks','Remarks: ', array('class' => 'col-lg-3 control-label')) !!}
								{!! Form::text('remarks') !!}
						</div>
			{{--LAT--}}
						<div class="form-group">
								{!! Form::label('lat','Latitude: ', array('class' => 'col-lg-3 control-label')) !!}
								{!! Form::text('lat', '', ['id' => 'lat']) !!}
						</div>
			{{--LNG--}}
						<div class="form-group">
								{!! Form::label('lng','longitude: ', array('class' => 'col-lg-3 control-label')) !!}
								{!! Form::text('lng', '', ['id' => 'lng']) !!}
						</div>
			{{--Published--}}
						<div class="form-group">
								{!! Form::label('published', 'Published: ', array('class' => 'col-lg-3 control-label')) !!}
								{!! Form::checkbox('published')!!}
						</div>
			{{--ADD MEDIA--}}
						<div class="form-group">
								{!! Form::label('Foto', 'Upload Picture: ', array('class' => 'col-lg-3 control-label')) !!}
								{!! Form::file('file') !!}
								<div id='message'>Upload your File...</div>
						</div>
						{!! Form::submit('Aanmaken', array('class' => 'btn btn-success')) !!}
						{!! link_to_route('admin.index', 'Cancel', null, array('class' => 'btn btn-warning')) !!}
					</div>
			{!! Form::close() !!}
	</div>
</div>
@endsection
