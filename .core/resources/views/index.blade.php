@extends ('layouts.default')

@section('mainbody')
<div id="search"><input placeholder="Search for a name" type=text class="searchField"/> <button onclick="search()">Search</button></div>
<div class="split left">
	<div id="map"></div>
</div>
<div class="split right">
   <div id='speakButton'></div>
   <div id='infoDiv'>
 	<div id='title'> </div>
	<div id='markerInfo'><center>Click the Map items to find the information!</center></div>
   </div>
</div>

<script>

$(function() {

        var markers = {!!$footages!!};

    var iconType = {};
        iconType['1'] = '/img/Afoto.png';
        iconType['2'] = '/img/Xfoto.png';
        iconType['3'] = '/img/Avideo.png';
        iconType['4'] = '/img/XVideo.png';

     var legName = {};
        legName['1'] = "Allied photo\'s";
        legName['2'] = "Axis photo\'s";
        legName['3'] = "Allied Video\'s";
        legName['4'] = "Axis Video\'s";

    var map = L.map('map').setView([50.1, 6], 6);
    mapLink = '<a href="http://www.esri.com/">Esri</a>';
    wholink = 'i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community';

    L.tileLayer( 'http://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',{
            attribution: '&copy; '+mapLink+', '+wholink,
            maxZoom: 18,
            }).addTo(map);

        var LeafIcon = L.Icon.extend({
            options: {
                    iconSize:[20, 22]
              }
        });

    var catMarkers = L.markerClusterGroup({

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

    markers = jQuery.grep(markers,function(item, i){return(item.published == "1" && i > 1);});

    var titleDiv = document.getElementById('title');
    var infoDiv = document.getElementById('markerInfo');
    var cat = [];

    for(i = 0; i< markers.length; i++){
        if(cat.indexOf(markers[i].typeId) === -1){
            cat.push(markers[i].typeId);
        }
    }

    for(i = 0; i< cat.length; i++){

        catData = jQuery.grep(markers,function(item, c){return(item.typeId == cat[i] && c > 1);});
        distCount = catData.length;

        for(m in catData){

            var lat     = catData[m].lat;
            var lng     = catData[m].lng;
            var dif     = catData[m].typeId;
            var head   = catData[m].shortdesc;
            var img     = catData[m].name;
            var place   = catData[m].place;
            var country = catData[m].country;
            var date    = catData[m].date;
            var info    = catData[m].info;

            var title = place+" - "+date;

            if (dif < 3){
                var cusCode = "<p><center><img src='/images/" + img + ".jpg' alt='' width='350px'/></center><br><u><h3>"+head+"</h3></u><br>"+info;
            }else{
                var cusCode = "<p>    <center><video id=\""+img+"\" poster=\"media/"+img+"/"+img+".jpg\" width=\"480\" height=\"360\" controls=\"autoplay\"><source src=\"media/"+img+"/"+img+".mp4\" type=\"video/mp4\"><source src=\"media/"+img+"/"+img+".ogg\" type=\"video/ogg\"></center><br>"+head+"<br>"+info;
            }

            var marker = L.marker([lat, lng], {icon:   new LeafIcon({iconUrl:[iconType[dif]]})});
            marker.title = title;
            marker.html = cusCode;
            marker.latLng = marker.getLatLng();
            marker.info = info.replace("'","&#39;");
            marker.on('click', sideDiv);

            catMarkers.addLayer(marker);
        }
    }
    map.addLayer(catMarkers);

    function sideDiv(e){

        var title= this.title;
        var text= this.html;
        var info = this.info;
        var latLng = this.latLng;

        titleDiv.innerHTML = "<h3><u>"+title+"</u></h3>";
        if (info !== ''){
            document.getElementById('speakButton').innerHTML = "<p><button onclick='responsiveVoice.speak(`"+info+"`);'>Read Me</button>";
        }else{
            document.getElementById('speakButton').innerHTML = "";
        }
        titleDiv.onmouseover = function(){titleDiv.style.color = '#428608';};
        titleDiv.onmouseout = function(){titleDiv.style.color = 'Black';};
        titleDiv.onclick = function(e){map.setView(latLng, '20', {animation: true});};

        infoDiv.innerHTML = text;
    }
});

function search(){

    var titleDiv = document.getElementById('title');
    var infoDiv = document.getElementById('markerInfo');
    var results =[];
    var term = document.getElementsByClassName('searchField')[0].value;
    var regex = new RegExp( term, 'ig');

    titleDiv.onclick = function() {return false;};
    titleDiv.onmouseover = function(){return false;};
    titleDiv.onmouseout = function(){return false;};

    if (term == ''){
        titleDiv.innerHTML = "<h3><u>Search</u></h3>";
        infoDiv.innerHTML = "<br>What are you looking for?";
    }else{
        titleDiv.innerHTML = "<h3><u>Search</u></h3>";
        infoDiv.innerHTML = "";
        for (m in markers) {
            name = JSON.stringify(markers[m].Name);
            if (name.match(regex)){
                results.push(name);

                infoDiv.innerHTML += "<li id="+m+" class='list-group-item link-class'><a href='#' onclick='fucntion(){map.setView(latLng("+markers[m].Lat+","+markers[m].Lng+"), 13, {animation: true});};'>"+markers[m].Name+"</a> | <span class='text-muted'>"+markers[m].Address+"</span></li>";

                document.getElementById(m).onClick = function(e){map.setView(markers[m].getLatLng(), '13', {animation: true});}
            }
        }
        titleDiv.innerHTML += "Found: "+results.length+" results for "+term;
    }
}


</script>

@endsection
