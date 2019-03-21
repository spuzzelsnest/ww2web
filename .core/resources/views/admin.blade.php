@extends ('layouts.admin')

@section('mainbody')

<div class="container">
<div class="row">
	<script type="text/javascript">
		$(function() {
			var tileLayer = new L.TileLayer('http://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}.png',{
  				attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, &copy; <a href="http://cartodb.com/attributions">CartoDB</a>'
			});

			var map = new L.Map('map', {
  				'center': [51.441767, 5.480247],
  				'zoom': 4,
  				'layers': [tileLayer]
			});

			var marker = L.marker([51.441767, 5.470247],{
  				draggable: true
			}).addTo(map);

			marker.on('dragend', function (e) {
				document.getElementById('lat').value = marker.getLatLng().lat;
  				document.getElementById('lng').value = marker.getLatLng().lng;
			});
});
	</script>
	<div class='col-sm'>
		<div id="markerStatus"><i>Click and drag the marker.</i></div>
		<div id="map"></div>
	</div>

	<div class='col-sm'>'

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
								{!! Form::checkbox('published') !!}
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
</div>
@endsection
