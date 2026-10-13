@extends ('layouts.default')

@section('mainbody')

<script type="text/javascript">
$(function() {
    mapLink = '<a href="http://www.esri.com/">Esri</a>';
    lableLink = '<a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="http://cartodb.com/attributions">CartoDB</a>';
    wholink = 'i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community';

    var imagery = L.tileLayer('https://tiles.maps.eox.at/wmts/1.0.0/s2cloudless-2020_3857/default/g/{z}/{y}/{x}.jpg', {
        maxZoom: 13,
        attribution: '&copy; <a href="https://s2maps.eu">Sentinel-2 cloudless</a> by EOX, modified Copernicus data'
    });

    var labels = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_only_labels/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors &copy; CARTO'
    });

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
        'layers': [imagery, labels]
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

<div class="split left">
    @if ($errors->any())
        <div class="alert alert-danger">
            <h1>Errors where encountered </h1>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>

<div class="split right">
    <div id="editDiv">

        <form method="POST" action="{{ route('admin.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">

                <div class="form-group-type">
                    <center>
                        {{-- SELECT MEDIA --}}
                        @foreach ($types as $type)
                            <input type="checkbox" name="typeId" value="{{ $type->id }}"
                                   @checked(old('typeId') == $type->id)>
                            {!! $type->description !!}
                        @endforeach
                    </center>
                </div>
                <br>

                {{-- NAME --}}
                <label for="name" class="col-lg-3 control-label">Name:</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                <br>

                {{-- SELECT DATE --}}
                <label for="date" class="col-lg-3 control-label">Date:</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ old('date', '1944-06-06') }}">
                <br>

                {{-- PLACE --}}
                <label for="place" class="col-lg-3 control-label">Place:</label>
                <input type="text" name="place" id="place" class="form-control" value="{{ old('place') }}">
                <br>

                {{-- COUNTRY --}}
                <label for="countryId" class="col-lg-3 control-label">Country:</label>
                <select name="countryId" id="countryId" class="form-control">
                    @foreach ($countryOptions as $value => $label)
                        <option value="{{ $value }}" @selected(old('countryId') == $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <br>

                {{-- OPERATION --}}
                <label for="operationId" class="col-lg-3 control-label">Operation:</label>
                <select name="operationId" id="operationId" class="form-control">
                    @foreach ($operationOptions as $value => $label)
                        <option value="{{ $value }}" @selected(old('operationId') == $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <br>

                {{-- INFO --}}
                <label for="info" class="col-lg-3 control-label">Info:</label>
                <textarea name="info" id="info" class="form-control">{{ old('info') }}</textarea>
                <br>

                {{-- SOURCE --}}
                <label for="sourceId" class="col-lg-3 control-label">Source:</label>
                <select name="sourceId" id="sourceId" class="form-control">
                    @foreach ($sourceOptions as $value => $label)
                        <option value="{{ $value }}" @selected(old('sourceId') == $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <br>

                {{-- REMARK --}}
                <label for="remark" class="col-lg-3 control-label">Remark:</label>
                <input type="text" name="remark" id="remark" class="form-control" value="{{ old('remark') }}">
                <br>

                {{-- LAT --}}
                <label for="lat" class="col-lg-3 control-label">Lat:</label>
                <input type="text" name="lat" id="lat" value="{{ old('lat') }}">
                <br>

                {{-- LNG --}}
                <label for="lng" class="col-lg-3 control-label">Lng:</label>
                <input type="text" name="lng" id="lng" value="{{ old('lng') }}">

                <div class="form-group-media">
                    {{-- ADD MEDIA --}}
                    <label for="image" class="col-lg-3 control-label">Upload Picture:</label>
                    <br>
                    <center>
                        <input type="file" name="image" id="image" class="col-lg-3 control-label">
                        <br>
                        <div id="message">Upload your File...</div>
                        <br>

                        {{-- Published --}}
                        <label for="published" class="col-lg-3 control-label">Published:</label>
                        <input type="checkbox" name="published" id="published" value="1" @checked(old('published'))>
                        <br>

                        <button type="submit" class="btn btn-success">Aanmaken</button>
                        <a href="{{ route('admin.index') }}" class="btn btn-warning">Cancel</a>
                    </center>
                </div>

            </div>
        </form>

    </div>
</div>

@endsection
