@extends ('layouts.default')

@section('mainbody')
<div id="search"><input placeholder="Search for a name" type=text class="searchField"/> <button onclick="search()">Search</button></div>
<div class="split left">
	<div id="map"></div>
</div>
<div class="split right">
	<div id='title'></div>
	<div id='speakButton'></div>
	<div id='markerInfo'>Click the Map items to find the information!</div>
</div>

<script>

$(function() {

        var markers = {!!$footages!!};
	var iconType = {};
                iconType['1'] = 'img/Afoto.png';
                iconType['2'] = 'img/Xfoto.png';
                iconType['3'] = 'img/Avideo.png';
                iconType['4'] = 'img/Xvideo.png';
                iconType['5'] = 'img/Aadio.png';
                iconType['6'] = 'img/Xadio.png';

        var map = L.map('map').setView([50.1, 6], 6);
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

        for(var i in markers){

                var lat                 = markers[i].lat;
                var lng                 = markers[i].lng;
                var dif                 = markers[i].typeId;
                var title		= markers[i].shortdesc;
                var img			= markers[i].name;
                var place               = markers[i].place;
		var country		= markers[i].country;
                var source              = markers[i].source;
                var date                = markers[i].date;
                var info                = markers[i].info;
                var remarks             = markers[i].remarks;

                if (dif < 3) {
                        customCode = "<big><u>"+title+" "+place+" ("+country+")</u></big><p><center><img src='/images/" + img + ".jpg' width='350px'/></center><br>"+date+"<br>"+info;
                } else {
                        customCode = "<big><u>"+title+" "+place+" ("+country+")</u></big><p><center><video id=\""+img+"\" poster=\"media/"+img+"/"+img+".jpg\" width=\"480\" height=\"360\" controls=\"autoplay\"><source src=\"media/"+img+"/"+img+".mp4\" type=\"video/mp4\"><source src=\"media/"+img+"/"+img+".ogg\" type=\"video/ogg\"></center><br>"+date+"<br>"+info;

                }

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
                        document.getElementById('speakButton').innerHTML = "<p><button onclick='responsiveVoice.speak(`"+info+"`);'>Read Me</button>";
		}else{
        		document.getElementById('speakButton').innerHTML = "";
    		}

		document.getElementById('markerInfo').innerHTML = text;
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

 function search(){

        var markers = {!!$footages!!};
	document.getElementById('speakButton').innerHTML = "";
	document.getElementById('markerInfo').innerHTML ="";

	var results =[];
	var term = document.getElementsByClassName('searchField')[0].value;

	var regex = new RegExp( term, 'ig');

	if (term == ''){
                        document.getElementById('markerInfo').innerHTML = "What are you looking for?";
                }else{
                        document.getElementById('markerInfo').innerHTML = "";
                        for (m in markers) {
                                name = JSON.stringify(markers[m].info);

                                if (name.match(regex)){
                                        results.push(name);
                                        document.getElementById('markerInfo').innerHTML += "<li class='list-group-item link-class'>"+markers[m].shortdesc+" | <span class='text-muted'>"+markers[m].info+"</span></li>";
                                }
                        }
                 document.getElementById('markerInfo').innerHTML += "Found: "+results.length+" results for "+term;
                }
        }

</script>

@endsection
