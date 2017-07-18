@extends ('layouts.default')

@section('mainbody')

	<div id="map"></div>
	<div id="popUp"></div>

<script>
$(function() {
	var iconType = {};
		iconType['1'] = 'img/Afoto.png';
		iconType['2'] = 'img/Xfoto.png';
		iconType['3'] = 'img/Avideo.png';
		iconType['4'] = 'img/Xvideo.png';
		iconType['5'] = 'img/Aadio.png';
		iconType['6'] = 'img/Xadio.png';

	var markers = [];
	var data = {!!$footages!!};
	var cluster = new MarkerClusterer(map, markers, {gridSize:20});
	cluster.setMap(null);

	var map = new google.maps.Map(document.getElementById("map"), {
		center: new google.maps.LatLng(47.9, 12),
	    zoom: 5,
	    mapTypeId: 'satellite',
	    mapTypeControl: true
	});

	for(var i = 0; i < data.length; i++){

		var dif			= data[i].typeId;
		var cusIcon		= iconType[dif];
		var shortdesc		= data[i].shortdesc;
		var name		= data[i].name;
		var place		= data[i].place;
		var source		= data[i].source;
		var date		= data[i].date;
		var info		= data[i].info;
		var remarks		= data[i].remarks;

		if (dif < 3) {
			html = "<p><center><img src=\"images/"+name+".jpg\" alt=\""+shortdesc+"\"></center>";
		} else {
			html = "<p><center><video id=\""+name+"\" poster=\"media/"+name+"/"+name+".jpg\" width=\"480\" height=\"360\" controls=\"autoplay\"><source src=\"media/"+name+"/"+name+".mp4\" type=\"video/mp4\"><source src=\"media/"+name+"/"+name+".ogg\" type=\"video/ogg\"></center>";
		}
		html += "<br><b>"+place+" - </b>"+date+"<br>source: "+source+"<p>"+info;

		var point = new google.maps.LatLng( parseFloat(data[i].lat), parseFloat(data[i].lng));
		var marker = new google.maps.Marker({position: point, icon: cusIcon, type: dif});

		markers.push(marker);
		cluster.addMarkers(markers);
		showInDiv(marker, html, shortdesc);
	}

	cluster.setMap(map, markers, {gridSize:20});

	function showInDiv(marker, html, shortdesc) {
		google.maps.event.addListener(marker, 'click', function() {
			var sidediv = $('#popUp');
			sidediv.html(html);
			sidediv.dialog({
				modal: true,
				title: shortdesc,
				buttons: [{text: "Close", click: function() {$(this).dialog("close")}}],
				minHeight: "500px",
				width: "600px",
				position: { my: "top", at: "top", of: window }
			});
		});
	}

	$("input:checkbox").bind( "change", function(){
	    $.each(data, function(index, i){
	        if(
		        $("input:checkbox[name='type'][value='"+i.typeId+"']").is(':checked')
			){
	            markers[index].setVisible(true);
	            cluster.setMap( this.map);
	        }else{
	            markers[index].setVisible(false);
	        }
		})
	});
});

</script>


@endsection
