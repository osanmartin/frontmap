var removeAnimate = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
var location_lat = '-33.0539430';
var location_lng = '-71.6245970';
var call_status = {"error":false};

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

// Marker location

map.addMarker({id:0,lat:location_lat,lng:location_lng,icon:'img/markers/marker_location.png'});



/*
var infoWindow = new google.maps.InfoWindow({map: map});				

if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };

            infoWindow.setPosition(pos);
            infoWindow.setContent('Location found.');
            map.setCenter(pos);
          }, function() {
            handleLocationError(true, infoWindow, map.getCenter());
          });
        } else {
          // Browser doesn't support Geolocation
          handleLocationError(false, infoWindow, map.getCenter());
        }


        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
          infoWindow.setPosition(pos);
          infoWindow.setContent(browserHasGeolocation ?
                                'Error: The Geolocation service failed.' :
                                'Error: Your browser doesn\'t support geolocation.');
        }*/

/*
navigator.geolocation.getCurrentPosition(
    function(position) {
         alert("Lat: " + position.coords.latitude + "\nLon: " + position.coords.longitude);
    },
    function(error){
         alert(error.message);
    }, {
         enableHighAccuracy: true
              ,timeout : 5000
    }
);
*/
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
	


	$(document).on('keypress','#finder input',function(){

		if (event.which == 13) {

        	event.preventDefault();
        
        	GMaps.geocode({
        	  address: $(this).val(),
        	  callback: function(results, status) {
        	    if (status == 'OK') {
        	        var latlng = results[0].geometry.location;
        	        map.setCenter(latlng.lat(), latlng.lng());
                    location_lat = latlng.lat(); 
                    location_lng = latlng.lng();

                    changeMarkerPosition(0,location_lat,location_lng);

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

    // Abre menú votación confiabilidad 
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

    // Realiza votación positiva confiabilidad
    $(document).on('click','.btn-validez-positiva',function(){

      var action = $('#vote-action').data('url');
      var dataIn = new FormData();
      var service_id = $('#service').val();

      dataIn.append('type','active');
      dataIn.append('vote',1);
      dataIn.append('service',service_id);

      var call = $.callAjax(dataIn,action,$(this));

      call.success(function(){

            if(!call_status['error']){

                $('.info-validez').find('.validez').click();
                $('.info-validez').find('a').removeClass('voted-negative').addClass('voted-positive');

            }


      });

    });

    // Realiza votación negativa confiabilidad
    $(document).on('click','.btn-validez-negativa',function(){

    	$(this).parents('.info-validez').find('.validez').click();
    	$(this).parents('.info-validez').find('a').removeClass('voted-positive').addClass('voted-negative');

    });

    // Abre menú votación calidad
    $(document).on('click','.calidad',function(){

    	$(this).parents('.info-calidad').find('.btn-calidad input').rating({
			filled: 'glyphicon glyphicon-heart',
			empty: 'glyphicon glyphicon-heart-empty'
    	});


    	if($(this).hasClass('open')){

	    	$('.btn-calidad').removeClass('hidden').addClass('animated fadeInUp').one(removeAnimate, 
				function(){
					$(this).removeClass('animated fadeInUp').removeClass('hidden');				
				});




    	} else {

	    	$('.btn-calidad').addClass('animated fadeOutDown').one(removeAnimate, 
				function(){
					$(this).removeClass('animated fadeOutDown').addClass('hidden');				
				});

    	}

    });

    // Realiza votación calidad
    $(document).on('change','.btn-calidad input',function () {
    
    	$(this).parents('.info-calidad').find('img').addClass('background-transparent');
    	$(this).parents('.info-calidad').find('.cssload-base')
    									.removeClass()
    									.addClass('cssload-base')
    									.addClass('level'+$(this).val());

    });


    // Abre menú votación precio
    $(document).on('click','.precio',function(){


    	if($(this).hasClass('open')){

	    	$('.btn-precio div').removeClass('hidden').addClass('animated fadeInUp').one(removeAnimate, 
				function(){
					$(this).removeClass('animated fadeInUp').removeClass('hidden');				
				});

    	} else {

	    	$('.btn-precio div').addClass('animated fadeOutDown').one(removeAnimate, 
				function(){
					$(this).removeClass('animated fadeOutDown').addClass('hidden');				
				});

    	}

    });


    // Realiza votación precio
    $(document).on('click','.btn-precio div',function(){

    	$(this).parents('.info-precio').find('.precio').click();
    	$(this).parents('.info-precio').find('img:not(.transparent)').addClass('transparent');
		$(this).parents('.info-precio').find('img[data-target="'+$(this).data('target')+'"]').removeClass('transparent');    	

    });

    $(document).on('click','#btn-erase-routes',function(){


    	$('#btn-erase-routes').addClass('animated fadeOutDown').one(removeAnimate, 
			function(){
				$(this).removeClass('animated fadeOutDown').addClass('hidden');				
			});

    	map.removePolylines();

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

function changeMarkerPosition(id,lat,lng) {
    var marker = map.markers[id];
    var latlng = new google.maps.LatLng(lat, lng);
    marker.setPosition(latlng);
}