@extends ('layouts.default')
@include('layouts.legenda')
@section('mainbody')
<div class="split right">
   <div id='infoDiv'>
	<div id="close" onclick="closeDiv()"><u>Close <big><b>X</b></big></u></div>
    <div id="title"></div>
	<div id="speakButton"></div>
	<div id="markerInfo"><p><center>Scroll to zoom into the map and click the markers to find more information!</center></p></div>
   </div>
</div>

<script>

$(function() {

    var data = {!! $footages !!};
    var markers = [];
   
    var iconType = {};
        iconType['1'] = '/img/Afoto.png';
        iconType['2'] = '/img/Xfoto.png';
        iconType['3'] = '/img/Avideo.png';
        iconType['4'] = '/img/Xvideo.png';
        iconType['5'] = '/img/Aadio.png';
		iconType['6'] = '/img/Xadio.png';

    var map = L.map('map').setView([50.1, 6], 6);
    mapLink = '<a href="http://www.esri.com/">Esri</a>';
    lableLink = '<a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="http://cartodb.com/attributions">CartoDB</a>';
    wholink = 'i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community';

    L.tileLayer('http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',{
            attribution: '&copy; '+mapLink+', '+wholink,
            maxZoom: 18,
            }).addTo(map);

    L.tileLayer('http://{s}.basemaps.cartocdn.com/dark_only_labels/{z}/{x}/{y}.png',{
	    id: 'cartodb_labels',
	    attribution: '&copy; '+lableLink
            }).addTo(map);
    
    var LeafIcon = L.Icon.extend({
            options: {iconSize:[20, 22]}
     });

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

        for(var i in data){

                var lat                 = data[i].lat;
                var lng                 = data[i].lng;
                var dif                 = data[i].typeid;
                var shortdesc           = data[i].shortdesc;
                var name                = data[i].name;
                var place               = data[i].place;
                var date                = data[i].date;
                var info                = data[i].info;
                var source              = data[i].source;
                var remarks             = data[i].remarks;
                var cusIcon             = iconType[dif];

                var title = place+" - "+date;

                if (dif < 3) {
                        customCode = "<p><center><img src='/images/" + name + ".jpg' alt='' width='450px'/></center><br>";
                } else {
                        customCode = "<p><center><video id=\"VideoPlayer\" poster=\"media/"+name+"/"+name+".jpg\" width=\"480\" height=\"360\" controls=\"autoplay\"><source src=\"media/"+name+"/"+name+".mp4\" type=\"video/mp4\"><source src=\"media/"+name+"/"+name+".ogg\" type=\"video/ogg\"></center><br>";
                }
                
                var marker = L.marker([lat, lng], {icon: new LeafIcon({iconUrl:[iconType[dif]]})});
                    marker.title = title;
                    marker.latLng = marker.getLatLng();
                    marker.html = customCode;
                    marker.info = info;
                    marker.source = source;
                    marker.on('click', displayInfo);
                markers.push(marker);
                cluster.addLayer(marker);
        }
        
        map.addLayer(cluster);

    function displayInfo(e){

        document.getElementById('infoDiv').style.display = 'block';

        var latLng = this.latLng;
        var title = this.title;
        var code = this.html;
        var info = this.info;
        
        var titleDiv = document.getElementById('title');
        titleDiv.innerHTML = "<h3><u>"+title+"</u></h3>";
        titleDiv.onmouseover = function(){titleDiv.style.color = '#428608';};
        titleDiv.onmouseout = function(){titleDiv.style.color = 'Black';};
        titleDiv.onclick = function(e){map.setView(latLng, '20', {animation: true});};

        if (info !== ''){
            document.getElementById('speakButton').innerHTML = "<p><button onclick='responsiveVoice.speak(`"+info+"`);'>Read Me</button>";
        }else{
            document.getElementById('speakButton').innerHTML = "";
        }

        markerInfo.innerHTML = code + "<p>" + info + "<br>"+ "<small><i>"+source+"</i></small></p>";
    }

    $("input:checkbox").bind( "change", function(){

        console.log('change!!!');

	    $.each(data, function(index, i){

	        if(
		        $("input:checkbox[name='type'][value='"+i.typeid+"']").is(':checked')
			){
                cluster.addLayer(markers[index]);
	        }else{
                cluster.removeLayer(markers[index]);
	        }
		})
	});
});

function closeDiv(){
   document.getElementById('infoDiv').style.display = 'none';


   var video = document.getElementById('VideoPlayer');
   
   video.pause();
   video.currentTime = 0;
}

function search(){

    var markers = {!! $footages !!};
    var titleDiv = document.getElementById('title');
    var infoDiv = document.getElementById('markerInfo');
    var results =[];
    var term = document.getElementsByClassName('searchField')[0].value;
    var regex = new RegExp( term, 'ig');

    if (term == ''){
        titleDiv.innerHTML = "<h3><u>Search</u></h3>";
        infoDiv.innerHTML = "<br>What are you looking for?";
    }else{
        titleDiv.innerHTML = "<h3><u>Search</u></h3>";
        infoDiv.innerHTML = "";
        for (m in markers) {
            info = JSON.stringify(markers[m].info);
            if (info.match(regex)){
                results.push(info);

                infoDiv.innerHTML += "<li id="+m+" class='list-group-item link-class'>"+markers[m].shortdesc+"</a> | <span class='text-muted'>"+markers[m].place+"</span></li>";

                document.getElementById(m).onClick = function(e){map.setView(latLng(markers[m].Lat,markers[m].Lng), '13', {animation: true});};
            }
        }
        titleDiv.innerHTML += "Found: "+results.length+" results for "+term;
    }
}

</script>

@endsection
