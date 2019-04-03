@extends ('layouts.default')

@section('mainbody')
<div id="search"><input placeholder="Search for a name" type=text class="searchField"/> <button onclick="search()">Search</button></div>
<div class="split left">
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
        iconType['0'] = '/img/Afoto.png';
        iconType['1'] = '/img/Xfoto.png';
        iconType['2'] = '/img/Avideo.png';
        iconType['3'] = '/img/Xvideo.png';

     var legName = {};
         legName['0'] = "Allied photo\'s";
         legName['1'] = "Axis photo\'s";
         legName['2'] = "Allied Video\'s";
         legName['3'] = "Axis Video\'s";

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

    var catLayers = L.markerClusterGroup();
    var legenda = document.getElementById('legenda');
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

	catLayers[i] = new L.markerClusterGroup({
              spiderfyOnMaxZoom: true,
		      showCoverageOnHover: false,
		      zoomToBoundsOnClick: true,
		      removeOutsideVisibleBounds:true,
		      maxClusterRadius: 5,
		      spiderLegPolylineOptions: {
				    weight: 1.5,
				    color: '#222',
				    opacity: 0.5
		      }
        });

        for(m in catData){

            var lat     = catData[m].lat;
            var lng     = catData[m].lng;
            var dif     = catData[m].typeId;
            var head    = catData[m].shortdesc;
            var img     = catData[m].name;
            var place   = catData[m].place;
            var country = catData[m].country;
            var date    = catData[m].date;
            var info    = catData[m].info;

            var title = place+" - "+date;

            if (dif < 3){
                var cusCode = "<p><center><img src='/images/" + img + ".jpg' alt='' width='350px'/></center><br>"+info;
            }else{
                var cusCode = "<p>    <center><video id=\""+img+"\" poster=\"media/"+img+"/"+img+".jpg\" width=\"480\" height=\"360\" controls=\"autoplay\"><source src=\"media/"+img+"/"+img+".mp4\" type=\"video/mp4\"><source src=\"media/"+img+"/"+img+".ogg\" type=\"video/ogg\"></center><br>"+info;
            }

            var marker = L.marker([lat, lng], {icon:   new LeafIcon({iconUrl:[iconType[i]]})});
            marker.title = title;
            marker.html = cusCode;
            marker.latLng = marker.getLatLng();
            marker.info = info.replace("'","&#39;");
            marker.on('click', sideDiv);

            catLayers[i].addLayer(marker);
        }
    	    catLayers[i].addTo(map);
            distCount = catData.length;
            var icon            = document.createElement('img');
                icon.width      = 20;
                icon.height     = 20;
                icon.src        = iconType[i];

            var checkbox        = document.createElement('input');
                checkbox.type   = 'checkbox';
                checkbox.name   = 'typeId';
                checkbox.id     = i;
                checkbox.checked= true;

            var label = document.createElement('label')
                label.htmlFor = i;
                label.appendChild(document.createTextNode("\u00A0\u00A0\u00A0"+distCount+"\u00A0\u00A0"+legName[i]+"\u00A0\u00A0.\u00A0\u00A0"));

            legenda.appendChild(checkbox);
            legenda.appendChild(icon);
            legenda.appendChild(label);

            checkbox.addEventListener('change', function(e){
                var id = this.id;
                 console.log (id);
                if (map.hasLayer(catLayers[id])) {
                    map.removeLayer(catLayers[id]);
                } else {
                    map.addLayer(catLayers[id]);
                }
            });

	}

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

    var markers = {!!$footages!!};
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
