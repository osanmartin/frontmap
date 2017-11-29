var redirectTo = null;
call_status = {'error':false};

$(document).ready(function(){

	$(document).on('keyup','#username_registro',function(){

		var email = $(this).val();
		var str = email.split('@');
		$('#username_from_email').val(str[0]);

	});


	$(document).on('keydown','#form-login input',function(event){

		if(event.keyCode == 13) {

			$('#btn-login').click();
			event.preventDefault();
			return false;

		}

	});


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



	$(document).on('keydown','#form-registro input',function(event){

		if(event.keyCode == 13) {

			$('#btn-registro').click();
			event.preventDefault();
			return false;

		}

	});


	$(document).on('click','#btn-registro',function(){

		var action = $('#form-registro').attr('action');
		var dataIn = new FormData($('#form-registro')[0]);


		var call = $.callAjax(dataIn,action,$(this));


		call.success(function(){

			if(!call_status.error){

				$('#form-registro')[0].reset();
				$('#btn-volver-login').click();

			}

			call_status.error = false;

		})



	});

	$(document).on('click','#btn-ir-registro',function(){

		$('#form-login-container').animateCss('slideOutLeft',function(){

			$('#form-login-container').addClass('hidden');
			$('#form-registro-container').removeClass('hidden');
			$('#form-registro-container').animateCss('bounceIn');

		});

	});

	$(document).on('click','#btn-volver-login',function(){

		$('#form-registro-container').animateCss('slideOutLeft',function(){

			$('#form-registro-container').addClass('hidden');
			$('#form-login-container').removeClass('hidden');
			$('#form-login-container').animateCss('bounceIn');

		});

	});





});
