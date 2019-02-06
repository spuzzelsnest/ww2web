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
                iconType['5'] = 'img/Aadio.png';
                iconType['6'] = 'img/Xadio.png';

        var cluster = L.markerClusterGroup({

                spiderfyOnMaxZoom: true,
                showCoverageOnHover: false,
                zoomToBoundsOnClick: true,
                removeOutsideVisibleBounds:true,
                maxClusterRadius: 20,
                spiderLegPolylineOptions: {
                                weight: 1.5,
                                color: '#222',
                                opacity: 0.5
                }
        });

        var markers = {!!$footages!!};

        for(var i in markers){

                var lat                 = markers[i].lat;
                var lng                 = markers[i].lng;
                var dif                 = markers[i].typeId;
                var cusIcon             = iconType[dif];
                var shortdesc           = markers[i].shortdesc;
                var name                = markers[i].name;
                var place               = markers[i].place;
                var source              = markers[i].source;
                var date                = markers[i].date;
                var info                = markers[i].info;
                var remarks             = markers[i].remarks;

                if (dif < 3) {
                        customCode = "<p><center><img src=\"images/"+name+".jpg\" alt=\""+shortdesc+"\"></center>";
                } else {
                        customCode = "<p><center><video id=\""+name+"\" poster=\"media/"+name+"/"+name+".jpg\" width=\"480\" height=\"360\" controls=\"autoplay\"><source src=\"media/"+name+"/"+name+".mp4\" type=\"video/mp4\"><source src=\"media/"+name+"/"+name+".ogg\" type=\"video/ogg\"></center>";
                }

                customCode += "<br><b>"+place+" - </b>"+date+"<br>source: "+source+"<p>"+info;

                var marker = L.marker([lat, lng], {icon: new LeafIcon({iconUrl:[iconType[dif]]})});
                marker.html = customCode;
                marker.info = info.replace("'","&#39;");
                marker.on('click', sideDiv);
                cluster.addLayer(marker);

        }

        map.addLayer(cluster);


        function sideDiv(e){
                var text= this.html;
                var info = this.info;
                if (info !== ''){
                        document.getElementById('speakButton').innerHTML = "<p><button onclick='read(`"+info+"`);'>Read Me</button>";
                }
                document.getElementById('markerInfo').innerHTML = text;
        }

        function read(info){
                var text = info;
                responsiveVoice.speak(text);
        }
        $("input:checkbox").bind( "change", function(){
            $.each(markers, function(index, i){
                if($("input:checkbox[name='type'][value='"+i.typeId+"']").is(':checked')){
                        markers[index]._container.style.visibility = "visible";
                        cluster.setMap( this.map);
                    }else{
                    markers[index]._container.style.visibility = "hidden";
                }
                })
        });
});

</script>

@endsection
