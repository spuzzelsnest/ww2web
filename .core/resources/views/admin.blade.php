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

<div id="legenda">Add a New Footage: <i>Drag the marker and fill out the info</i></div>
<div class='split right'>
        <div id='infoDiv'>
                {!! Form::open(array('route' => 'admin.store', 'files' => true)) !!}
                <div class="form-group">
                       <div class="form-group-type">
                        <center>
                        {{--SELECT MEDIA--}}
                                @foreach ($types as $type)
                                        {!! Form::checkbox('typeId', $type->id) !!}
                                        {!! $type->description !!}

                                @endforeach
                        </center>
                        </div>
                        <br>
                        {{--NAME--}}
                                {!! Form::label('name','Name:', array('class' => 'col-lg-3 control-label')) !!}
                                {!! Form::text('name', '', array('class' => 'form-control')) !!}
                        <br>
                        {{--SELECT DATE--}}
                                {!! Form::label('date','Date:', array('class' => 'col-lg-3 control-label')) !!}
                                {!! Form::macro('date', function($name, $default = '1944/06/06', $attrs = array()){
                                        $item = '<input type="date" name="'. $name .'" ';
                                        if ($default) { $item .= 'value="'. $default .'" ';}
                                        if (is_array($attrs)){
                                                foreach ($attrs as $a => $k)
                                                $item .= $a .'="'. $k .'" ';
                                        } $item .= ">";
                                        return $item;}); !!}
                                {!!Form::date('date', '06/06/1944' ,['class'=>'form-control']) !!}
                        <br>
                        {{--PLACE--}}
                                {!! Form::label('place','Place:', array('class' => 'col-lg-3 control-label')) !!}
                                {!! Form::text('place', '', ['class'=>'form-control']) !!}
                        <br>
                        {{--COUNTRY--}}
                                {!! Form::label('countryId','Country:', array('class' => 'col-lg-3 control-label')) !!}
                                        @foreach ($countries as $country)
                                           {!! $country->country !!}
                                        @endforeach
                                {!! Form::select('countryId', 
                                        array( 
                                           '0' => '...'
                                        ),
                                        null,
                                         ['class'=>'form-control']) !!}
                        <br>
                        {{--OPERATION--}}
                                {!! Form::label('operation','Operation:', array('class' => 'col-lg-3 control-label')) !!}
                                {!! Form::select('operationId', 
                                        array(
                                           '0' => '...'
                                        ),
                                        null,
                                         ['class'=>'form-control']) !!}
                        <br>
                        {{--INFO--}}
                                {!! Form::label('info','Info:', array('class' => 'col-lg-3 control-label')) !!}
                                {!! Form::textarea('info') !!}
                        <br>
                        {{--SOURCE--}}
                                {!! Form::label('source','Source:', array('class' => 'col-lg-3 control-label')) !!}
                                {!! Form::select('sourceId', 
                                        array( 
                                           '0' => '...'
                                        ),
                                        null,
                                         ['class'=>'form-control']) !!}
                        <br>
                        {{--REMARKS--}}
                                {!! Form::label('remarks','Remarks:', array('class' => 'col-lg-3 control-label')) !!}
                                {!! Form::text('remarks', '', ['class'=>'form-control']) !!}
                        <br>
                        {{--LAT--}}
                                {!! Form::label('lat','Lat:',  array('class' => 'col-lg-3 control-label')) !!}
                                {!! Form::text('lat', '', ['id' => 'lat']) !!}
                        <br>
                        {{--LNG--}}
                                {!! Form::label('lng','Lng:', array('class' => 'col-lg-3 control-label')) !!}
                                {!! Form::text('lng', '', ['id' => 'lng']) !!}
                        <div class="form-group-media">
                        {{--ADD MEDIA--}}
                                {!! Form::label('Foto', 'Upload Picture:') !!}
                        <br>
                        <center>
                                {!! Form::file('file') !!}
                        <br>
                                <div id='message'>Upload your File...</div>
                        <br>
                        {{--Published--}}
                                {!! Form::label('published', 'Published:') !!}
                                {!! Form::checkbox('published') !!}
                        <br>
                                {!! Form::submit('Aanmaken', array('class' => 'btn btn-success')) !!}
                                {!! link_to_route('admin.index', 'Cancel', null, array('class' => 'btn btn-warning')) !!}
                        </center>
                        </div>
                </div>
                {!! Form::close() !!}
        </p>
</div>
@endsection