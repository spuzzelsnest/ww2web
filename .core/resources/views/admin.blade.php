@extends ('layouts.default')

@section('mainbody')

<script>
$(function () {
    var cartoKey = @json(config('services.carto.key'));

    var imagery = L.tileLayer(
        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 18,
            attribution: '&copy; <a href="https://www.esri.com/">Esri</a>, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        }
    );

    var labels = L.tileLayer(
        'https://{s}.basemaps.cartocdn.com/dark_only_labels/{z}/{x}/{y}.png?key=' + cartoKey, {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>'
        }
    );

    var latInput = document.getElementById('lat');
    var lngInput = document.getElementById('lng');

    // After a validation error the form is repopulated, so start from those values.
    var restored = latInput.value !== '' && lngInput.value !== '';
    var start = restored
        ? [parseFloat(latInput.value), parseFloat(lngInput.value)]
        : [51.441767, 5.470247];

    var map = new L.Map('map', {
        center: restored ? start : [51.1, 6],
        zoom: restored ? 10 : 6,
        layers: [imagery, labels]
    });

    var marker = L.marker(start, { draggable: true }).addTo(map);

    function updateFields() {
        var pos = marker.getLatLng();
        latInput.value = pos.lat.toFixed(8);
        lngInput.value = pos.lng.toFixed(8);
    }

    marker.on('dragend', updateFields);
    map.on('click', function (e) {
        marker.setLatLng(e.latlng);
        updateFields();
    });

    // Fill the fields right away so the form never submits empty coordinates.
    updateFields();
});
</script>

<div id="legenda">Add a New Footage: <i>Drag the marker (or click the map) and fill out the info</i></div>

@if (session('status'))
    <div id="legenda" style="top:3.2rem; font-size:0.8rem;">{{ session('status') }}</div>
@endif

<div class="split left">
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Errors were encountered:</strong>
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

                {{-- SELECT MEDIA TYPE (one value is stored, so radio buttons) --}}
                <div class="form-group-type text-center">
                    @foreach ($types as $type)
                        <label>
                            <input type="radio" name="typeId" value="{{ $type->id }}"
                                   @checked(old('typeId') == $type->id) required>
                            {{ $type->description }}
                        </label>
                    @endforeach
                </div>
                <br>

                {{-- NAME --}}
                <label for="name" class="col-lg-3 control-label">Name:</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
                <br>

                {{-- DATE --}}
                <label for="date" class="col-lg-3 control-label">Date:</label>
                <input type="date" name="date" id="date" class="form-control" value="{{ old('date', '1944-06-06') }}" required>
                <br>

                {{-- PLACE --}}
                <label for="place" class="col-lg-3 control-label">Place:</label>
                <input type="text" name="place" id="place" class="form-control" value="{{ old('place') }}" required>
                <br>

                {{-- COUNTRY --}}
                <label for="countryId" class="col-lg-3 control-label">Country:</label>
                <select name="countryId" id="countryId" class="form-control" required>
                    @foreach ($countryOptions as $value => $label)
                        <option value="{{ $value }}" @selected(old('countryId') == $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <br>

                {{-- OPERATION --}}
                <label for="operationId" class="col-lg-3 control-label">Operation:</label>
                <select name="operationId" id="operationId" class="form-control" required>
                    @foreach ($operationOptions as $value => $label)
                        <option value="{{ $value }}" @selected(old('operationId') == $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <br>

                {{-- INFO --}}
                <label for="info" class="col-lg-3 control-label">Info:</label>
                <textarea name="info" id="info" class="form-control" rows="5">{{ old('info') }}</textarea>
                <br>

                {{-- SOURCE --}}
                <label for="sourceId" class="col-lg-3 control-label">Source:</label>
                <select name="sourceId" id="sourceId" class="form-control" required>
                    @foreach ($sourceOptions as $value => $label)
                        <option value="{{ $value }}" @selected(old('sourceId') == $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <br>

                {{-- REMARK --}}
                <label for="remark" class="col-lg-3 control-label">Remark:</label>
                <input type="text" name="remark" id="remark" class="form-control" value="{{ old('remark') }}">
                <br>

                {{-- LAT / LNG (filled by the map marker) --}}
                <label for="lat" class="col-lg-3 control-label">Lat:</label>
                <input type="text" name="lat" id="lat" inputmode="decimal" value="{{ old('lat') }}" required>
                <br>

                <label for="lng" class="col-lg-3 control-label">Lng:</label>
                <input type="text" name="lng" id="lng" inputmode="decimal" value="{{ old('lng') }}" required>

                <div class="form-group-media">
                    {{-- ADD MEDIA --}}
                    <label for="image" class="col-lg-3 control-label">Upload Picture:</label>
                    <br>
                    <div class="text-center">
                        <input type="file" name="image" id="image" accept="image/*">
                        <br>
                        <div id="message">Upload your File...</div>
                        <br>

                        {{-- PUBLISHED --}}
                        <label for="published" class="col-lg-3 control-label">Published:</label>
                        <input type="checkbox" name="published" id="published" value="1" @checked(old('published'))>
                        <br>

                        <button type="submit" class="btn btn-success">Aanmaken</button>
                        <a href="{{ route('admin.index') }}" class="btn btn-warning">Cancel</a>
                        <form method="POST" action="{{ route('logout') }}" style="display:inline">
    			@csrf
    			<button type="submit" class="btn btn-default">Log out</button>
			</form>
		     </div>
                </div>

            </div>
        </form>

    </div>
</div>

@endsection
