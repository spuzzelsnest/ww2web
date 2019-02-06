@extends ('layouts.default')

@section('mainbody')

<div class="split left">
	<div id="map"></div>
</div>
<div class="split right">
	<div id='title'></div>
	<div id='speakButton'></div>
	<div id='markerInfo'></div>
</div>

<script>


$(function() {

        var map = L.map('map').setView([50.1, 6], 5);
        mapLink = '<a href="http://www.esri.com/">Esri</a>';
        wholink = 'i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community';

        L.tileLayer(
            'http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; '+mapLink+', '+wholink,
            maxZoom: 18,
            }).addTo(map);


        var LeafIcon = L.Icon.extend({
            options: {
                    iconSize:[20, 25]
              }
        });

        var iconType = {};
                iconType['1'] = 'img/Afoto.png';
                iconType['2'] = 'img/Xfoto.png';
                iconType['3'] = 'img/Avideo.png';
                iconType['4'] = 'img/Xvideo.png';

});


</script>

@endsection
