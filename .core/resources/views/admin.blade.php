@extends ('layouts.default')

@section('mainbody')
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

	<div id="legenda">EDIT THE DB: <i>Drag the marker and fill out the info</i></div>
<div class='split right'>
	<div id='infoDiv'>
		{!! Form::open(array('route' => 'admin.store', 'files' => true)) !!}

	   		{{--SELECT MEDIA--}}
	   			<div class="form-group">
					@foreach ($types as $type)
						{!! Form::checkbox('typeId', $type->id) !!}
						{{$type->description}}
					@endforeach
				</div>
			{{--NAME--}}
				{!! Form::label('name','Name: ', array('class' => 'col-lg-3 control-label')) !!}
				{!! Form::text('name') !!}
			<br>
			{{--SELECT DATE--}}
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
			<br>
			{{--PLACE--}}
				{!! Form::label('place','Place: ', array('class' => 'col-lg-3 control-label')) !!}
				{!! Form::text('place') !!}
			<br>
			{{--COUNTRY--}}
				{!! Form::label('country','Country: ', array('class' => 'col-lg-3 control-label')) !!}
				{!! Form::text('country') !!}
			<br>
			{{--SOURCE--}}
				{!! Form::label('source','Source: ', array('class' => 'col-lg-3 control-label')) !!}
				{!! Form::text('source') !!}
			<br>
			{{--SCHORTDESC--}}
				{!! Form::label('shortdesc','Title: ', array('class' => 'col-lg-3 control-label')) !!}
				{!! Form::text('shortdesc') !!}
			<br>
			{{--INFO--}}
				{!! Form::label('info','Info: ', array('class' => 'col-lg-3 control-label')) !!}
				{!! Form::textarea('info') !!}
			<br>
			{{--REMARKS--}}
				{!! Form::label('remarks','Remarks: ', array('class' => 'col-lg-3 control-label')) !!}
				{!! Form::text('remarks') !!}
			<br>
			<center>
			{{--LAT--}}
				{!! Form::label('lat','Lat: ') !!}
				{!! Form::text('lat', '', ['id' => 'lat']) !!}

			{{--LNG--}}
				{!! Form::label('lng','Lng: ') !!}
				{!! Form::text('lng', '', ['id' => 'lng']) !!}
			<br>
			{{--Published--}}
				{!! Form::label('published', 'Published: ') !!}
				{!! Form::checkbox('published') !!}
			<br>
			{{--ADD MEDIA--}}
				{!! Form::label('Foto', 'Upload Picture: ') !!}
				{!! Form::file('file') !!}
				<br>
				<div id='message'>Upload your File...</div>

				{!! Form::submit('Aanmaken', array('class' => 'btn btn-success')) !!}
				{!! link_to_route('admin.index', 'Cancel', null, array('class' => 'btn btn-warning')) !!}
			</center>
		{!! Form::close() !!}
</div>
@endsection
