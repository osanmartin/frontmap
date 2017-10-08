var redirectTo = null;

$(document).ready(function(){


	$(document).on('click','#btn-login',function(){

		var action = $('#form-login').attr('action');
		var dataIn = new FormData($('#form-login')[0]);


		var call = $.callAjax(dataIn,action,$(this));


		call.success(function(){

			if(redirectTo != null){
				window.location.href = redirectTo;
			}

			redirectTo = null;

		})



	});




});
