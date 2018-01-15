
var removeAnimate = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
var location_lat = '-33.0539430';
var location_lng = '-71.6245970';
var call_status = {"error":false};
var direction_invalid = {"error":false};
var clickData = null;
var stackClick = [];
var stackOver = [];
var stackClickMaps = [];

var location_lat_create = '-33.0539430';
var location_lng_create = '-71.6245970';
var creating_mode = false;
var direction_from_picker = "";
var lat_from_picker = "";
var lng_from_picker = "";
/*

GEOLOCALIZACIÓN, SIN IMPLEMENTAR

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



// Generamos mapa
map = new GMaps(
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


// Instanciamos geocoder
var geocoder = new google.maps.Geocoder;

// Instanciamos input de búsqueda
var input = /** @type {!HTMLInputElement} */(
            document.getElementById('finder-input'));

        var autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo('bounds', map.map);

        var infowindow = new google.maps.InfoWindow();
        var marker = new google.maps.Marker({
          map: map.map,
          anchorPoint: new google.maps.Point(0, -29)
        });

        autocomplete.addListener('place_changed', function() {
          infowindow.close();
          var place = autocomplete.getPlace();
          if (!place.geometry) {
            
            return;
          }

          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);  
          }

          location_lat = place.geometry.location.lat(); 
          location_lng = place.geometry.location.lng();

          changeMarkerPosition(0,location_lat,location_lng);

        });



// Localización inicial
map.addMarker({id:0,lat:location_lat,lng:location_lng,icon:'img/markers/marker_location.png'});

// markers services
var markers = [];


// markers creating
var markers_creating = [];

// Estilo para mapa
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

      bloquearValidez();

      dataIn.append('type','active');
      dataIn.append('vote',1);
      dataIn.append('service',service_id);

      var call = $.callAjax(dataIn,action,$(this));

      call.success(function(){

            if(!call_status['error']){

                $('.info-validez').find('a').removeClass('voted-negative').addClass('voted-positive');
                desbloquearValidez();

            }

            $('.validez').click();
            call_status['error'] = false;


      });

    });

    // Realiza votación negativa confiabilidad
    $(document).on('click','.btn-validez-negativa',function(){


        var action = $('#vote-action').data('url');
        var dataIn = new FormData();
        var service_id = $('#service').val();


        bloquearValidez();

        dataIn.append('type','active');
        dataIn.append('vote',0);
        dataIn.append('service',service_id);

        var call = $.callAjax(dataIn,action,$(this));

        call.success(function(){

            if(!call_status['error']){

            	$('.info-validez').find('a').removeClass('voted-positive').addClass('voted-negative');
                desbloquearValidez();

            }

            $('.validez').click();
            call_status['error'] = false;

        });

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

        var action = $('#vote-action').data('url');
        var dataIn = new FormData();
        var service_id = $('#service').val();

        bloquearCalidad();

        dataIn.append('type','quality');
        dataIn.append('vote',$(this).val());
        dataIn.append('service',service_id);


        clickData = $(this);

        var call = $.callAjax(dataIn,action,$(this));

        call.success(function(){



            if(!call_status['error']){
    
            	$('.info-calidad').find('img').addClass('background-transparent');
            	$('.info-calidad').find('.cssload-base')
            									.removeClass()
            									.addClass('cssload-base')
            									.addClass('level'+clickData.val());

                desbloquearCalidad();

            }

            $('.calidad').click();
            call_status['error'] = false;
            clickData = null;

        });

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


        var action = $('#vote-action').data('url');
        var dataIn = new FormData();
        var service_id = $('#service').val();

        bloquearPrecio();

        dataIn.append('type','price');
        dataIn.append('vote',$(this).data('id'));
        dataIn.append('service',service_id);

        clickData = $(this);

        var call = $.callAjax(dataIn,action,$(this));

        call.success(function(){

            if(!call_status['error']){

            	$('.info-precio').find('img:not(.transparent)').addClass('transparent');
        		$('.info-precio').find('img[data-target="'+clickData.data('target')+'"]').removeClass('transparent');    	

                desbloquearPrecio();

            }

            $('.precio').click();
            call_status['error'] = false;

        });   


    });

    $(document).on('click','#btn-erase-routes',function(){


    	$('#btn-erase-routes').addClass('animated fadeOutDown').one(removeAnimate, 
			function(){
				$(this).removeClass('animated fadeOutDown').addClass('hidden');				
			});

    	map.removePolylines();

    });

    // Captura de eventos

    $(document).on('click', function(evt) {
        if(typeof evt.toElement != 'undefined'){

            var id = evt.toElement.id;
            var classes = evt.toElement.className;

            if(classes.includes('glyphicon-heart')){
                id = 'btn-quality';
                var value = $(evt.toElement).parents('#btn-quality').find('input').val();
                id = id+'-'+value;
            }

            var dataElement = {id:id,pos_x:evt.pageX,pos_y:evt.pageY,timestamp:evt.timeStamp};
            stackClick.push(dataElement);

            if(stackClick.length>999){
                stackClick.shift();
            }
        }
        return false;
    });

    $(document).on('mousemove', function(evt){
        if(typeof evt.toElement != 'undefined'){

            var dataElement = {pos_x:evt.pageX,pos_y:evt.pageY,timestamp:evt.timeStamp};
            stackOver.push(dataElement);

            if(stackOver.length>999){
                stackOver.shift();
            }
        }
        return false;

    });

    map.addListener("click", function(event) {

        var lat = event.latLng.lat();
        var lng = event.latLng.lng();
        var timestamp = new Date().getTime()

        if(creating_mode){


            geocodeLatLng(geocoder,lat,lng);

        }


        var dataElement = {pos_x:lat,pos_y:lng,timestamp:timestamp};
        stackClickMaps.push(dataElement);

        if(stackClickMaps.length>999){
            stackClickMaps.shift();
        }

    });


    $(document).on('click','#logo-image',function(){


        if($('#btn-logout').hasClass('hidden')){

            $(this).find('i').removeClass('fa-caret-down').addClass('fa-caret-up');
            $('#btn-logout').removeClass('hidden');

        } else{


            $(this).find('i').removeClass('fa-caret-up').addClass('fa-caret-down');
            $('#btn-logout').addClass('hidden');

        }


    });


});

// Obtiene nomnbres de ubicación según latlng

function geocodeLatLng(geocoder,lat,lng) {
    var latlng = {lat: lat, lng: lng};
    geocoder.geocode({'location': latlng}, function(results, status) {

        if (status === 'OK') {

            if (results[1]) {

                removeAllMarkersCreating();

                map.addMarker({
                  lat: lat,
                  lng: lng,
                  icon: 'img/markers/marker_location_creating.png'
                });

                var direction = results[0].formatted_address;

                var text_to_show = direction.split(',');
                text_to_show = text_to_show[0]+','+text_to_show[1];

                text_to_show = text_to_show.replace(/([0-9]+)-[0-9]+/i, "$1");

                $('#text-pick-position').text(text_to_show);

                // cargamos variable global
                direction_from_picker = text_to_show;
                lat_from_picker = lat;
                lng_from_picker = lng;

            } else {

                $('#text-pick-position').text('Ninguna');
                direction_from_picker = text_to_show;
                lat_from_picker = lat;
                lng_from_picker = lng;

            }

    } else {

        // status para ver error
        $('#text-pick-position').text('Ninguna');
        direction_from_picker = text_to_show;
        lat_from_picker = lat;
        lng_from_picker = lng;
        removeAllMarkersCreating();

    }
  });
}


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
                $('[data-toggle="tooltip"]').tooltip({html:true});

			});

		});

	});

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

function bloquearCalidad(){

    $('.btn-calidad .rating-symbol').css('pointer-events','none');
    $('.btn-calidad .rating-symbol').css('color','grey');

}

function desbloquearCalidad(){

    $('.btn-calidad .rating-symbol').css('pointer-events','initial');
    $('.btn-calidad .rating-symbol').css('color','inherit');

}

function bloquearPrecio(){

    $('.btn-precio div').css('pointer-events','none');
    $('.btn-precio div').addClass('bgnd-grey');
}

function desbloquearPrecio(){

    $('.btn-precio div').css('pointer-events','initial');
    $('.btn-precio div').removeClass('bgnd-grey')

}

function bloquearValidez(){

    $('.btn-validez div').css('pointer-events','none');
    $('.btn-validez div').css('opacity','0.3');

}

function desbloquearValidez(){

    $('.btn-validez div').css('pointer-events','initial');
    $('.btn-validez div').css('opacity','1');

}

function removeAllMarkersCreating(){

    if(map.markers.length>0){

        $.each(map.markers, function(index, val) {
            if(val.icon == "img/markers/marker_location_creating.png"){
                map.removeMarker(val);
            }
        });

    }

}