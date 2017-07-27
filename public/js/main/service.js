var open_modal = 0;



$(document).ready(function() {
	
	$(document).on('click','#btn-new-service',function(){

		var url = $(this).data('url');
		var dataIn = new FormData();

		var modal_id = $(this).data('target');

		var call = $.callAjax(dataIn,url,$(this));

		call.success(function(){

			if(open_modal){

				$(modal_id).modal('show');

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

			}

			open_modal = 0;

		});


	});



});