$(document).ready(function() {

	var map = new GMaps({div:'#map',lat:'-33.0539430',lng:'-71.6245970'});

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
        	      map.addMarker({
        	        lat: latlng.lat(),
        	        lng: latlng.lng()
        	      });
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


	
});


