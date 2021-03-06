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

	$(document).on('keydown','#search-form input',function(event){

		if(event.keyCode == 13) {

			$('.btn-search-modal').click();
			event.preventDefault();
			return false;

		}

	});


    $(document).on('click','.btn-search-modal',function(){


		var url = $(this).data('url');
		var dataIn = new FormData($('#search-form')[0]);
	
		dataIn.append('location_lat',location_lat);
		dataIn.append('location_lng',location_lng);

		var call = $.callAjax(dataIn,url,$(this));

		call.success(function(){

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

				var elements = [];

				$.each(response.rows[0].elements, function(index, val) {

					if(typeof val.distance == 'undefined' || val.distance == null || val.distance == ''){

						$('#distance-'+services[index].id).parents('.row').first().remove();


					} else{

						var distance = Number((val.distance.value/1000).toFixed(1));
						var duration = Math.round(val.duration.value/60);

						$('#distance-'+services[index].id).html(distance);
						$('#duration-'+services[index].id).html(duration);

						var aux = [];
						aux['distance'] = distance;
						aux['html'] = $('#duration-'+services[index].id).parents('.row').first()[0];

						elements.push(aux);
						$('#duration-'+services[index].id).parents('.row').first().remove();						

					}


					


				});

				elements.sort(orderDesc);

				$.each(elements, function(index, val) {
					$('.table-header').after(val.html);
				});

				$('.icon-note').tooltip()

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

   			if(!call_status.error){

   				renderMarkers();
   				temp_place = null;
   				$('#modal-new-service').modal('hide');
   			}

   			if(!direction_invalid.error){

   				$('#address_service').focus();

   			}


   			call_status.error = false;
   			direction_invalid.error = false;

   		});

   	});

   	$(document).on('click','#btn-select-position',function(){

   		creating_mode = true;
   		$('#modal-new-service').modal('hide');
   		$('#container-pick-position').removeClass('hidden');

   	});

   	$(document).on('click','#cancel-pick-position',function(){

   		creating_mode = false;
   		$('#modal-new-service').modal('show');
   		$('#container-pick-position').addClass('hidden');

   		$('#text-pick-position').text('Ninguna');
   		removeAllMarkersCreating();

   	});

   	$(document).on('click','#btn-pick-position',function(){

   		creating_mode = false;
   		$('#modal-new-service').modal('show');
   		$('#address_service').val(direction_from_picker);
   		$('#container-pick-position').addClass('hidden');
   		removeAllMarkersCreating();
   		setTimeout(function(){

   			$('#address_service').focus();

   		},500);
   		

   	});


   	$(document).on('click','.btn-modal-close',function(){


   		creating_mode = false;
   		direction_from_picker = "";
   		$('#text-pick-position').text('Ninguna');
   		removeAllMarkersCreating();


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
      
        return;
      }

      if (place.geometry.viewport) {
        map.fitBounds(place.geometry.viewport);
      } else {
        map.setCenter(place.geometry.location);
        map.setZoom(17); 
      }


      changeMarkerPosition(0,location_lat,location_lng);

      temp_place = place;

      $('#address-selected').html(temp_place.name);
      $('#container-address').removeClass('hidden');

    });

}


function orderDesc(a,b) {
  if (a.distance < b.distance)
    return 1;
  if (a.distance > b.distance)
    return -1;
  return 0;
}

function orderAsc(a,b) {
  if (a.distance < b.distance)
    return 1;
  if (a.distance > b.distance)
    return -1;
  return 0;
}