
@extends ('layouts.default')
 
@section('mainbody')
 
@include('layouts.legenda')
 
<div id="search">
    <input type="search" class="searchField" placeholder="Search footage..." aria-label="Search footage" autocomplete="off">
    <button type="button" id="searchButton">Search</button>
</div>
 
<div class="split left">
    <div id="homeDiv">
        <div id="close" onclick="closeHDiv()"><u>Close <big><b>X</b></big></u></div>
        <h1>WW 2 Maps</h1>
        <p>
            This page is dedicated to footage from the Second World War, pinned to its original location. It's a work in progress and will be updated regularly.
            I started this project in 2007 out of respect to all combat journalists and people in conflict zones. Many thanks to the community, the journalists, the historians and locals who helped me along the way.
        </p>
        <p class="text-center"><b>Scroll to zoom into the map and click the markers to find more information!</b></p>
        <p>Last updated: {{ $lastUpdate }}</p>
    </div>
</div>
 
<div class="split right">
    <div id="infoDiv">
        <div id="close" onclick="closeIDiv()"><u>Close <big><b>X</b></big></u></div>
        <div id="title"></div>
        <div id="speakButton"></div>
        <div id="markerInfo"></div>
    </div>
</div>
 
<script>
$(function () {
 
    var data = @json($footages);
    var cartoKey = @json(config('services.carto.key'));
    var voice = 'UK English Female';
 
    // ---- Map -------------------------------------------------------------
 
    var map = L.map('map').setView([50.1, 4], 7);
 
    L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        maxZoom: 18,
        attribution: '&copy; <a href="https://www.esri.com/">Esri</a>'
    }).addTo(map);
 
    L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_only_labels/{z}/{x}/{y}.png?key=' + cartoKey, {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>, This Project is meant as Historic reference only'
    }).addTo(map);
 
    // ---- Icons -----------------------------------------------------------
 
    var iconFiles = {
        1: 'Afoto',
        2: 'Xfoto',
        3: 'Avideo',
        4: 'Xvideo',
        5: 'Aaudio',
        6: 'Xadio'      // check the file name: is this meant to be Xaudio.png?
    };
 
    var icons = {};
    $.each(iconFiles, function (typeId, file) {
        icons[typeId] = L.icon({ iconUrl: '/css/images/' + file + '.png', iconSize: [18, 22] });
    });
 
    // ---- Helpers ---------------------------------------------------------
 
    // Escape text before it is put into innerHTML.
    function esc(value) {
        return $('<div>').text(value == null ? '' : value).html();
    }
 
    // Lower-case and strip accents so "Kraków" matches "krakow".
    function normalise(text) {
        return String(text == null ? '' : text)
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    }
 
    function mediaHtml(item) {
        var name = item.name;
 
        if (item.typeId < 3) {
            return "<a id='picbox' href='#popup'></a>" +
                   "<div id='popup'><a id='picbox' href='#popup'><center>" +
                   "<img src='/images/" + name + ".jpg' id='img' width='350'/></center></a>" +
                   "<a id='popup-close' href='#'></a></div><br>";
        }
 
        return '<center><video id="VideoPlayer" poster="/media/' + name + '/' + name + '.jpg" width="350" height="263" controls>' +
               '<source src="/media/' + name + '/' + name + '.mp4" type="video/mp4">' +
               '<source src="/media/' + name + '/' + name + '.ogg" type="video/ogg">' +
               '</video></center><br>';
    }
 
    // ---- Markers ---------------------------------------------------------
 
    var markers = [];
 
    var cluster = L.markerClusterGroup({
        spiderfyOnMaxZoom: true,
        showCoverageOnHover: false,
        zoomToBoundsOnClick: true,
        removeOutsideVisibleBounds: true,
        maxClusterRadius: 20,
        spiderLegPolylineOptions: {
            weight: 1.5,
            color: '#222',
            opacity: 0.5
        }
    });
 
    data.forEach(function (item) {
        var marker = L.marker([item.lat, item.lng], {
            icon: icons[item.typeId] || new L.Icon.Default()
        });
 
        marker.meta = {
            typeId: item.typeId,
            title: item.place + ' - ' + item.date,
            place: item.place,
            date: item.date,
            info: item.info,
            source: item.source,
            remark: item.remark,
            html: mediaHtml(item),
            latLng: marker.getLatLng(),
            haystack: normalise([item.place, item.date, item.info, item.remark].join(' '))
        };
 
        marker.on('click', displayInfo);
        markers.push(marker);
    });
 
    cluster.addLayers(markers);
    map.addLayer(cluster);
 
    // ---- Info panel ------------------------------------------------------
 
    function displayInfo() {
        var meta = this.meta;
        var titleDiv = document.getElementById('title');
        var speakDiv = document.getElementById('speakButton');
 
        document.getElementById('infoDiv').style.display = 'block';
 
        if (window.responsiveVoice) { responsiveVoice.cancel(); }
 
        titleDiv.innerHTML = '<h5><u>' + esc(meta.title) + '</u></h5>';
        titleDiv.onmouseover = function () { titleDiv.style.color = '#428608'; };
        titleDiv.onmouseout  = function () { titleDiv.style.color = 'Black'; };
        titleDiv.onclick     = function () { map.setView(meta.latLng, map.getMaxZoom(), { animate: true }); };
 
        speakDiv.innerHTML = '';
        if (meta.info) {
            var button = document.createElement('button');
            button.textContent = 'Read Me';
            button.onclick = function () { responsiveVoice.speak(meta.info, voice); };
 
            var wrap = document.createElement('p');
            wrap.appendChild(button);
            speakDiv.appendChild(wrap);
        }
 
        document.getElementById('markerInfo').innerHTML =
            meta.html +
            '<p>' + esc(meta.info) +
            '<br><small><i>' + esc(meta.source) + ' ' + esc(meta.remark) + '</i></small></p>';
    }
 
    // ---- Legend filter ---------------------------------------------------
 
    var typeBoxes = "input:checkbox[name='type']";
 
    function applyFilter() {
        var checked = $(typeBoxes + ':checked').map(function () {
            return String(this.value);
        }).get();
 
        cluster.clearLayers();
        cluster.addLayers(markers.filter(function (marker) {
            return checked.indexOf(String(marker.meta.typeId)) !== -1;
        }));
    }
 
    $(typeBoxes).on('change', applyFilter);
 
    // ---- Search ----------------------------------------------------------
 
    var MAX_RESULTS = 50;
    var searchTimer = null;
 
    function search() {
        var $title = $('#title');
        var $info  = $('#markerInfo');
        var raw    = ($('.searchField').first().val() || '').trim();
 
        document.getElementById('infoDiv').style.display = 'block';
        $title.html('<h3><u>Search</u></h3>');
 
        if (raw === '') {
            $info.html('<br>What are you looking for?');
            return;
        }
        if (raw.length < 2) {
            $info.html('<br>Type at least 2 characters.');
            return;
        }
 
        // Every word must appear somewhere in place, date, info or remark.
        var words = normalise(raw).split(/\s+/);
 
        // Only markers that are currently visible (legend types switched on).
        var results = markers.filter(function (marker) {
            return cluster.hasLayer(marker) && words.every(function (word) {
                return marker.meta.haystack.indexOf(word) !== -1;
            });
        });
 
        var list = $('<ul class="list-group"></ul>');
 
        results.slice(0, MAX_RESULTS).forEach(function (marker) {
            var link = $('<a href="#"></a>').append($('<strong></strong>').text(marker.meta.title));
 
            if (marker.meta.info) {
                var snippet = marker.meta.info.substring(0, 80) + (marker.meta.info.length > 80 ? '...' : '');
                link.append('<br>').append($('<small class="text-muted"></small>').text(snippet));
            }
 
            link.on('click', function (e) {
                e.preventDefault();
                cluster.zoomToShowLayer(marker, function () {
                    displayInfo.call(marker);
                });
            });
 
            $('<li class="list-group-item link-class"></li>').append(link).appendTo(list);
        });
 
        $title.append('Found: ' + results.length + ' results for ' + esc(raw));
 
        $info.empty();
        if (results.length > MAX_RESULTS) {
            $info.append($('<p></p>').text('Showing the first ' + MAX_RESULTS + '. Narrow your search to see more.'));
        }
        $info.append(list);
    }
 
    $('.searchField')
        .on('input', function () {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(search, 250);
        })
        .on('keydown', function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                clearTimeout(searchTimer);
                search();
            }
        });
 
    $('#searchButton').on('click', search);
});
 
function closeHDiv() {
    document.getElementById('homeDiv').style.display = 'none';
}
 
function closeIDiv() {
    document.getElementById('infoDiv').style.display = 'none';
    if (window.responsiveVoice) { responsiveVoice.cancel(); }
}
</script>
 
@endsection