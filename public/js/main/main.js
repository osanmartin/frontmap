var removeAnimate = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';

var map = new GMaps(
					{div:'#map_canvas',
					lat:'-33.0539430',
					lng:'-71.6245970',
					zoomControl: false,
					mapTypeControl: false,
					scaleControl: false,
					streetViewControl: false,
					rotateControl: false,
					fullscreenControl: false,
					clickableIcons:false
				});
var markers = [];

var styles = [
  {
    stylers: [
      { hue: "#00ffe6" },
      { saturation: -20 }
    ]
  }, {
      featureType: "road",
      elementType: "geometry",
      stylers: [
          { lightness: 100 },
          { visibility: "simplified" }
    ]
  }, {
      featureType: "road",
      elementType: "labels",
      stylers: [
          { visibility: "off" }
    ]
  }
];

map.addStyle({
  styledMapName:"Styled Map",
  styles: styles,
  mapTypeId: "map_style"  
});

map.setStyle("map_style");





$(document).ready(function() {

	renderMarkers();
	/*
	GMaps.geolocate({
	  success: function(position) {
	    map.setCenter(position.coords.latitude, position.coords.longitude);
	  },
	  error: function(error) {
	    console.log('Geolocation failed: '+error.message);
	  },
	  not_supported: function() {
	    alert("Your browser does not support geolocation");
	  },
	  always: function() {
	    console.log("done");
	  }
	});

	*/

	$(document).on('keypress','#finder input',function(){

		if (event.which == 13) {

        	event.preventDefault();
        
        	GMaps.geocode({
        	  address: $(this).val(),
        	  callback: function(results, status) {
        	    if (status == 'OK') {
        	      var latlng = results[0].geometry.location;
        	      map.setCenter(latlng.lat(), latlng.lng());
        	      map.setZoom(16);
        	    }
        	  }
        	});
    	}
	});

	$(document).on('focus','#finder input',function(){

		$("#finder").removeClass('opacity-min').addClass('opacity-min');

	});

	$(document).on('blur','#finder input',function(){

		$("#finder").removeClass('opacity-min');

	});

	map.addListener('click', function() {

		$('#finder input').blur();

    });

    $(document).on('click','#main-menu',function(){

    	if($(this).hasClass('open')){

	    	$('.btn-menu').removeClass('hidden').addClass('animated fadeInUp').one(removeAnimate, 
				function(){
					$(this).removeClass('animated fadeInUp').removeClass('hidden');				
				});

    	} else {

	    	$('.btn-menu').addClass('animated fadeOutDown').one(removeAnimate, 
				function(){
					$(this).removeClass('animated fadeOutDown').addClass('hidden');				
				});

    	}

    });

    $(document).on('click','.validez',function(){


    	if($(this).hasClass('open')){

	    	$('.btn-validez').removeClass('hidden').addClass('animated fadeInUp').one(removeAnimate, 
				function(){
					$(this).removeClass('animated fadeInUp').removeClass('hidden');				
				});

    	} else {

	    	$('.btn-validez').addClass('animated fadeOutDown').one(removeAnimate, 
				function(){
					$(this).removeClass('animated fadeOutDown').addClass('hidden');				
				});

    	}

    });
	
});



// Añade un marcador según string

function addMarkerByString(dir){

	GMaps.geocode({
	  address: dir,
	  callback: function(results, status) {
	    if (status == 'OK') {
	      var latlng = results[0].geometry.location;
	      map.setCenter(latlng.lat(), latlng.lng());
	      map.addMarker({
	        lat: latlng.lat(),
	        lng: latlng.lng()
	      });
	    }
	  }
	});

	



}


function renderMarkers(){

	var url = $('#render-all-form').attr('action');
	var dataIn = new FormData();

	var call = $.callAjax(dataIn,url);

	call.success(function(){

		$.each(markers, function(index, val) {

			marker = map.addMarker(val);

			marker.addListener('click', function() {

				$('.iw-content').closest('.gm-style-iw').parent().addClass('custom-iw');

			});

		});

	});

	/*
	var directions = [	{id:1,lat:'-33.06',lng:'-71.63',icon:"img/markers/bathroom_30x30.png",infoWindow: { content: '<div>OK</div>'}},
						{id:2,lat:'-33.07',lng:'-71.64',icon:"img/markers/bathroom_30x30.png"},
						{id:3,lat:'-33.08',lng:'-71.65',icon:"img/markers/bathroom_30x30.png"},
						{id:4,lat:'-33.09',lng:'-71.66',icon:"img/markers/bathroom_30x30.png"},
					];

	*/




}

function setInfoWindow(){

	$('.iw-content').closest('.gm-style-iw').parent().addClass('custom-iw');

}

GMaps.prototype.markerById=function(id){
  for(var m=0;m<this.markers.length;++m){
    if(this.markers[m].get('id')===id){
      return this.markers[m];
    }
  }
  return new google.maps.Marker();
}