@extends ('layouts.default')

@section('mainbody')
<script type="text/javascript">
        $(function() {
                mapLink = '<a href="http://www.esri.com/">Esri</a>';
                lableLink = '<a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="http://cartodb.com/attributions">CartoDB</a>';
                wholink = 'i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community';

                var tileLayer = new L.TileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',{
                       attribution: '&copy; '+mapLink+', '+wholink
                });

                var lablesLayer = new L.TileLayer('http://{s}.basemaps.cartocdn.com/dark_only_labels/{z}/{x}/{y}.png',{
                        id: 'cartodb_labels',
                        attribution: '&copy; '+lableLink
                });

                var map = new L.Map('map', {
                        'center': [51.1, 6],
                        'zoom': 6,
                        'layers': [tileLayer, lablesLayer]
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
                <div class="form-group">
                        {{--SELECT MEDIA--}}
                                @foreach ($types as $type)
                                        {!! Form::checkbox('typeid', $type->id) !!}
                                        {{$type->description}}
                                @endforeach
                        {{--NAME--}}
                                {!! Form::label('name','Name: ', array('class' => 'col-lg-3 control-label')) !!}
                                {!! Form::text('name') !!}
                        <br>
                        {{--SELECT DATE--}}
                                {!! Form::label('date','Date: ') !!}
                                {!! Form::macro('date', function($name, $default = '1944/06/06', $attrs = array()){
                                        $item = '<input type="date" name="'. $name .'" ';
                                        if ($default) { $item .= 'value="'. $default .'" ';}
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
                                {!! Form::label('country','Country: ') !!}
                                {!! Form::select('countryId') !!}
                        <br>
                        {{--SCHORTDESC--}}
                                {!! Form::label('shortdesc','Title: ', array('class' => 'col-lg-3 control-label')) !!}
                                {!! Form::text('shortdesc') !!}
                        <br>
                        {{--INFO--}}
                                {!! Form::label('info','Info: ', array('class' => 'col-lg-3 control-label')) !!}
                                {!! Form::textarea('info') !!}
                        <br>
                        {{--SOURCE--}}
                                {!! Form::label('source','Source: ', array('class' => 'col-lg-3 control-label')) !!}
                                {!! Form::text('source') !!}
                        {{--REMARKS--}}
                                {!! Form::label('remarks','Remarks: ') !!}
                                {!! Form::text('remarks') !!}
                        <br>
                        {{--LAT--}}
                                {!! Form::label('lat','Lat: ',  array('class' => 'col-lg-3 control-label')) !!}
                                {!! Form::text('lat', '', ['id' => 'lat']) !!}

                        {{--LNG--}}
                                {!! Form::label('lng','Lng: ') !!}
                                {!! Form::text('lng', '', ['id' => 'lng']) !!}
                        <center>
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
                        </div>
                        </center>

                {!! Form::close() !!}
                </p>
</div>
@endsection