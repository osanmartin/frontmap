var open_modal = 0;
var services = {};
var temp_place = null;

$(document).ready(function() {
	
	$(document).on('click','#btn-new-service',function(){

		var url = $(this).data('url');
		var dataIn = new FormData();

		var modal_id = $(this).data('target');

		var call = $.callAjax(dataIn,url,$(this));

		call.success(function(){

			if(open_modal){

				$(modal_id).modal('show');
				$('#main-menu').click();

				activeAutocomplete('address_service');


			}

			open_modal = 0;

		});


	});

	$(document).on('click','#btn-search-service',function(){

		var url = $(this).data('url');
		var dataIn = new FormData();

		var modal_id = $(this).data('target');

		var call = $.callAjax(dataIn,url,$(this));

		call.success(function(){

			if(open_modal){

				$(modal_id).modal('show');
				$('#main-menu').click();



			}

			open_modal = 0;

		});


	});


    $(document).on('click','.btn-search-modal',function(){


		var url = $(this).data('url');
		var dataIn = new FormData($('#search-form')[0]);
	
		dataIn.append('location_lat',location_lat);
		dataIn.append('location_lng',location_lng);

		var call = $.callAjax(dataIn,url,$(this));

		call.success(function(){

			$('.icon-note').tooltip();

			var origin = new google.maps.LatLng(location_lat, location_lng);

			var destinations = [];

			$.each(services, function(index, val) {
				
				var destination = new google.maps.LatLng(val.lat,val.lng);
				destinations.push(destination);

			});


			var service = new google.maps.DistanceMatrixService();
			service.getDistanceMatrix(
			  {
			    origins: [origin],
			    destinations: destinations,
			    travelMode: 'WALKING',
			    avoidHighways: true,
			    avoidTolls: true,
			  }, callback);

			function callback(response, status) {

				$.each(response.rows[0].elements, function(index, val) {

					if(typeof val.distance == 'undefined' || val.distance == null || val.distance == ''){

						$('#distance-'+services[index].id).parents('.row').first().remove();


					} else{

						var distance = Number((val.distance.value/1000).toFixed(1));
						var duration = Math.round(val.duration.value/60);

						$('#distance-'+services[index].id).html(distance);
						$('#duration-'+services[index].id).html(duration);

					}
					


				});

			}



		});    	

    });

   	$(document).on('click','.btn-action-search',function(){

   		$('#modal-search-service').modal('hide');

   		map.drawRoute({
   		  origin: [location_lat, location_lng],
   		  destination: [$(this).data('lat'), $(this).data('lng')],
   		  travelMode: 'WALKING',
   		  strokeColor: '#131540',
   		  strokeOpacity: 0.6,
   		  strokeWeight: 6
   		});

   		map.setCenter($(this).data('lat'), $(this).data('lng'));

    	$('#btn-erase-routes').removeClass('hidden').addClass('animated fadeInUp').one(removeAnimate, 
			function(){
				$(this).removeClass('animated fadeInUp').removeClass('hidden');				
			});



   	});

   	$(document).on('click','#btn-add-service',function(){

   		var action = $('#form-add-service').attr('action');
   		var dataIn = new FormData($('#form-add-service')[0]);


   		if(temp_place != null && typeof temp_place != 'undefined'){

	   		var temp_location_add = temp_place.geometry.location;

	   		dataIn.append('lat',temp_location_add.lat());
	   		dataIn.append('lng',temp_location_add.lng());

   		}

   		var call = $.callAjax(dataIn,action,$(this));

   		call.success(function(){

   			if(!call_status['error']){

   				renderMarkers();
   				temp_place = null;
   				$('#modal-new-service').modal('hide');
   			}


   		});

   	});



});

function activeAutocomplete(id){

	var input = /** @type {!HTMLInputElement} */(
	document.getElementById(id));

    var autocomplete_form = new google.maps.places.Autocomplete(input);
    autocomplete_form.bindTo('bounds', map.map);

    autocomplete_form.addListener('place_changed', function() {
      //marker.setVisible(false);
      
      var place = autocomplete_form.getPlace();

      if (!place.geometry) {
        // User entered the name of a Place that was not suggested and
        // pressed the Enter key, or the Place Details request failed.
        
        return;
      }

      // If the place has a geometry, then present it on a map.
      if (place.geometry.viewport) {
        map.fitBounds(place.geometry.viewport);
      } else {
        map.setCenter(place.geometry.location);
        map.setZoom(17);  // Why 17? Because it looks good.
      }

      //location_lat = place.geometry.location.lat(); 
      //location_lng = place.geometry.location.lng();

      changeMarkerPosition(0,location_lat,location_lng);

      temp_place = place;

      $('#address-selected').html(temp_place.name);
      $('#container-address').removeClass('hidden');

    });

}