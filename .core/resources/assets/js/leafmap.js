function loadMap() {

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
            options: {
                    iconSize:[20, 22]
              }
     });

    var iconType = {};
        iconType['1'] = new LeafIcon({iconUrl: 'img/Afoto.png'});
        iconType['2'] = new LeafIcon({iconUrl: 'img/Xfoto.png'});
        iconType['3'] = new LeafIcon({iconUrl: 'img/Avideo.png'});
        iconType['4'] = new LeafIcon({iconUrl: 'img/XVideo.png'});

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

        var lat = markers[i].lat;
        var lng = markers[i].lng;
		var dif = markers[i].typeid;
		var title = markers[i].shortdesc;
		var img = markers[i].name;
		var place = markers[i].place;
		var country = markers[i].country;
		var date = markers[i].date;
		var info = markers[i].info;

		if (dif < 3){

			var customPopup = "<big><u>"+title+" "+place+" ("+country+")</u></big><p><center><img src='/images/" + img + ".jpg' alt='' width='350px'/></center><br>"+date+"<br>"+info;
		}else{
			var customPopup = "<big><u>"+title+" "+place+" ("+country+")</u></big><p>    <center><video id=\""+img+"\" poster=\"media/"+img+"/"+img+".jpg\" width=\"480\" height=\"360\" controls=\"autoplay\"><source src=\"media/"+img+"/"+img+".mp4\" type=\"video/mp4\"><source src=\"media/"+img+"/"+img+".ogg\" type=\"video/ogg\"></center><br>"+date+"<br>"+info;
		}

		var marker = L.marker([lat, lng], {icon: iconType[dif]});
        marker.html = customPopup;
        marker.info = info.replace("'","&#39;");
        marker.on('click', sideDiv);
		cluster.addLayer(marker);
	}
	map.addLayer(cluster);
    
}

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
