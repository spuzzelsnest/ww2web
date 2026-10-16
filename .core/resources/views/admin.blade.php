@extends ('layouts.default')

@section('mainbody')

{{-- TOP BAR --}}
<div id="legenda">
    <span id="adminBar">
        Add a New Footage: <i>drag the marker (or click the map) and fill out the info</i>
        <a href="/" class="btn btn-default btn-xs">Public map</a>
        {{-- Own form, outside the footage form: forms cannot be nested --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-default btn-xs">Log out</button>
        </form>
    </span>
</div>

{{-- MESSAGES --}}
<div class="split left">
    @if (session('status'))
        <div id="adminStatus" role="status">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Please fix the following:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <small>The picture is not kept after an error, please choose it again.</small>
        </div>
    @endif
</div>

{{-- FORM --}}
<div class="split right">
    <div id="editDiv">

        <form id="footageForm" method="POST" action="{{ route('admin.store') }}" enctype="multipart/form-data">
            @csrf

            {{-- 1. TYPE (one value is stored, so radio buttons) --}}
            <fieldset>
                <legend class="section-title">Type</legend>
                <div class="type-options">
                    @foreach ($types as $type)
                        <label>
                            <input type="radio" name="typeId" value="{{ $type->id }}"
                                   @checked((string) old('typeId') === (string) $type->id) required>
                            {{ $type->description }}
                        </label>
                    @endforeach
                </div>
            </fieldset>

            {{-- 2. DETAILS --}}
            <fieldset>
                <legend class="section-title">Details</legend>

                <div class="field">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control"
                           maxlength="60" value="{{ old('name') }}" required>
                    <small class="hint">Also the file name of the picture. Letters, numbers, dots, dashes, underscores and spaces only.</small>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="date">Date</label>
                        <input type="date" name="date" id="date" class="form-control"
                               max="{{ now()->toDateString() }}" value="{{ old('date', '1944-06-06') }}" required>
                    </div>
                    <div class="field">
                        <label for="place">Place</label>
                        <input type="text" name="place" id="place" class="form-control"
                               maxlength="100" value="{{ old('place') }}" required>
                    </div>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="countryId">Country</label>
                        <select name="countryId" id="countryId" class="form-control" required>
                            <option value="">Choose a country...</option>
                            @foreach ($countryOptions as $value => $label)
                                <option value="{{ $value }}" @selected((string) old('countryId') === (string) $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="operationId">Operation</label>
                        <select name="operationId" id="operationId" class="form-control" required>
                            <option value="">Choose an operation...</option>
                            @foreach ($operationOptions as $value => $label)
                                <option value="{{ $value }}" @selected((string) old('operationId') === (string) $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </fieldset>

            {{-- 3. DESCRIPTION --}}
            <fieldset>
                <legend class="section-title">Description</legend>

                <div class="field">
                    <label for="info">Info</label>
                    <textarea name="info" id="info" class="form-control" rows="5" maxlength="5000">{{ old('info') }}</textarea>
                </div>

                <div class="field-row">
                    <div class="field">
                        <label for="sourceId">Source</label>
                        <select name="sourceId" id="sourceId" class="form-control" required>
                            <option value="">Choose a source...</option>
                            @foreach ($sourceOptions as $value => $label)
                                <option value="{{ $value }}" @selected((string) old('sourceId') === (string) $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="field">
                        <label for="remark">Remark</label>
                        <input type="text" name="remark" id="remark" class="form-control"
                               maxlength="120" value="{{ old('remark') }}">
                    </div>
                </div>
            </fieldset>

            {{-- 4. LOCATION (filled by the map marker, or typed) --}}
            <fieldset>
                <legend class="section-title">Location</legend>

                <div class="field-row">
                    <div class="field">
                        <label for="lat">Latitude</label>
                        <input type="text" name="lat" id="lat" class="form-control" inputmode="decimal"
                               pattern="-?[0-9]{1,2}(\.[0-9]+)?" title="A number between -90 and 90, for example 50.41142237"
                               value="{{ old('lat') }}" required>
                    </div>
                    <div class="field">
                        <label for="lng">Longitude</label>
                        <input type="text" name="lng" id="lng" class="form-control" inputmode="decimal"
                               pattern="-?[0-9]{1,3}(\.[0-9]+)?" title="A number between -180 and 180, for example 5.84698700"
                               value="{{ old('lng') }}" required>
                    </div>
                </div>
                <small class="hint">Drag the marker or click the map to set the location. You can also type the coordinates.</small>
            </fieldset>

            {{-- 5. PICTURE AND PUBLISHING --}}
            <fieldset>
                <legend class="section-title">Picture</legend>

                <div class="field">
                    <label for="image">Picture (JPG, maximum 1.5 MB)</label>
                    <input type="file" name="image" id="image" accept=".jpg,.jpeg,image/jpeg" required>
                    <small id="message" class="hint" aria-live="polite">No file selected.</small>
                    <img id="preview" alt="Preview of the selected picture"
                         style="display:none; max-width:12rem; max-height:9rem; margin-top:0.4rem; border:3px solid white;">
                </div>

                <div class="field">
                    <label for="published">
                        <input type="checkbox" name="published" id="published" value="1" @checked(old('published'))>
                        Published (visible on the public map)
                    </label>
                </div>
            </fieldset>

            <div class="actions">
                <button type="submit" id="submitButton" class="btn btn-success">Add footage</button>
                <a href="{{ route('admin.index') }}" class="btn btn-warning">Reset</a>
            </div>

        </form>

    </div>
</div>

<script>
$(function () {
    var latInput = document.getElementById('lat');
    var lngInput = document.getElementById('lng');
 
    function validCoords(lat, lng) {
        return isFinite(lat) && isFinite(lng) && Math.abs(lat) <= 90 && Math.abs(lng) <= 180;
    }
 
    // ---- Map -------------------------------------------------------------
 
    var imagery = L.tileLayer(
        'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 18,
            attribution: '&copy; <a href="https://www.esri.com/">Esri</a>, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
        }
    );
 
    // After a validation error the form comes back filled in: start from those coordinates.
    var restoredLat = parseFloat(latInput.value);
    var restoredLng = parseFloat(lngInput.value);
    var restored    = validCoords(restoredLat, restoredLng);
    var start       = restored ? [restoredLat, restoredLng] : [51.441767, 5.470247];
 
    var map = new L.Map('map', {
        center: restored ? start : [51.1, 6],
        zoom: restored ? 10 : 6,
        layers: [imagery]
    });
 
    // City labels in their own pane so they always sit on top of the imagery.
    // pointer-events none: dragging the marker and clicking the map still work.
    map.createPane('labels');
    map.getPane('labels').style.zIndex = 650;
    map.getPane('labels').style.pointerEvents = 'none';
 
    // Esri's place-name layer: same server and same tile scheme as the imagery above,
    // so no extra key and no extra attribution is needed.
    var labels = L.tileLayer(
        'https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
            pane: 'labels',
            maxZoom: 18
        }
    ).addTo(map);
 
    // If labels are still missing, the browser console now says why.
    labels.on('tileerror', function (e) {
        console.warn('City-label tile failed to load:', e.tile.src);
    });
 
    // ---- Marker and coordinate fields -----------------------------------
 
    var marker = L.marker(start, { draggable: true }).addTo(map);
 
    function writeFields() {
        var pos = marker.getLatLng();
        latInput.value = pos.lat.toFixed(8);
        lngInput.value = pos.lng.toFixed(8);
    }
 
    function moveMarkerFromFields() {
        var lat = parseFloat(latInput.value);
        var lng = parseFloat(lngInput.value);
        if (validCoords(lat, lng)) {
            marker.setLatLng([lat, lng]);
            map.panTo([lat, lng]);
        }
    }
 
    marker.on('dragend', writeFields);
    map.on('click', function (e) {
        marker.setLatLng(e.latlng);
        writeFields();
    });
    $('#lat, #lng').on('change', moveMarkerFromFields);
 
    // The fields stay empty until a location is chosen on purpose (they are required),
    // so footage can never be saved at the default marker position by accident.
 
    // ---- Picture: check before upload, show a preview --------------------
 
    var MAX_BYTES  = 1500 * 1024;   // same limit as the server rule (max:1500 KB)
    var imageInput = document.getElementById('image');
    var message    = document.getElementById('message');
    var preview    = document.getElementById('preview');
    var previewUrl = null;
 
    imageInput.addEventListener('change', function () {
        var file = this.files[0];
 
        if (previewUrl) { URL.revokeObjectURL(previewUrl); previewUrl = null; }
        preview.style.display = 'none';
 
        if (!file) {
            message.textContent = 'No file selected.';
            return;
        }
        if (!/^image\/jpe?g$/.test(file.type)) {
            message.textContent = 'Only JPG pictures are accepted.';
            this.value = '';
            return;
        }
        if (file.size > MAX_BYTES) {
            message.textContent = 'That picture is ' + (file.size / 1048576).toFixed(1) + ' MB, the maximum is 1.5 MB.';
            this.value = '';
            return;
        }
 
        previewUrl = URL.createObjectURL(file);
        preview.src = previewUrl;
        preview.style.display = 'block';
        message.textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
    });
 
    // ---- Prevent double submits ------------------------------------------
 
    $('#footageForm').on('submit', function () {
        $('#submitButton').prop('disabled', true).text('Saving...');
    });
 
    // Coming back with the browser's back button must not leave the button disabled.
    window.addEventListener('pageshow', function () {
        $('#submitButton').prop('disabled', false).text('Add footage');
    });
});

</script>

@endsection
