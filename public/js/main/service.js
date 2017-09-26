var open_modal = 0;
var services = {};

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
		var dataIn = new FormData();

		var call = $.callAjax(dataIn,url,$(this));

		call.success(function(){

			$('.icon-note').tooltip();

			var origin = new google.maps.LatLng(-33.0539430, -71.6245970);

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

					var distance = Number((val.distance.value/1000).toFixed(1));
					var duration = Math.round(val.duration.value/60);
					
					$('#distance-'+services[index].id).html(distance);
					$('#duration-'+services[index].id).html(duration);

				});

			}



		});    	

    });

   	$(document).on('click','.btn-action-search',function(){

   		$('#modal-search-service').modal('hide');

   		map.drawRoute({
   		  origin: [-33.0539430, -71.6245970],
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



});