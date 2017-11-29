{% extends "layouts/login.volt" %}

{% block content %}


<video poster="background.gif" id="bgvid" playsinline autoplay muted loop>

<source src="background.mp4" type="video/mp4">
</video>



<div id="navbar-login" class="row bgnd-blue ">
	
	<div class="col-sm-12font-white">
		
		<div class="mgn-bottom-10 mgn-top-10">
			<div class="text-center">
				<a id="logo-image">
				    <img class="navbar-brand-image" src="img/favicon-32x32.png"> <span>DondeEsta.CL</span>
				</a>
			</div>
		</div>

	</div>


</div>

{#

	<span id="logo-login">
	    <div id="logo">
	        <a id="logo-image" href="#">
	            <img class="navbar-brand-image" src="img/favicon-32x32.png"> <span>FrontMap</span>
	        </a>
	    </div>
	</span>

#}

{# Login #}
<div id="form-login-container" class="row">

	<div class="col-md-4 col-md-offset-4 col-xs-10 col-xs-offset-1"  id="container-login">

		<form id="form-login" action="{{ url('session/loginpost') }}">
		
	    	<div class="form-group width-100">
	    		<div class="row width-100 no-margin">
					<div class="col-md-12 width-100">
						
						<label for="">
							<i class="fa fa-user"></i> Usuario
						</label>

					</div>

		    		<div class="col-md-12 width-100">
		    			<input type="text" class="form-control validate custom" placeholder="" id="username" name="username">	
		    		</div>
	    		</div>
	    		
	    	</div>
	    	<div class="box-error"><p id="username-error" class="error"></p></div>

	    	<div class="form-group width-100">
	    		<div class="row width-100 no-margin">
					<div class="col-md-12 width-100">
						
						<label for="">
							<i class="fa fa-key"></i> Contraseña
						</label>

					</div>

		    		<div class="col-md-12 width-100">
		    			<input type="password" class="form-control validate custom" id="password" name="password">	
		    		</div>
	    		</div>
	    	</div>
	    	<div class="box-error"><p id="password-error" class="error"></p></div>

	    	<div class="form-group width-100">
	    		<div class="row width-100 no-margin">

					<button id="btn-login" type="button" class="btn btn-login"><i class="fa fa-sign-in"></i> Login</button>

					<button id="btn-ir-registro" type="button" class="btn btn-login"><i class="fa fa-user-plus"></i> Registro</button>
					
	    		</div>
	    	</div>

		</form>

	</div>

</div>

{# Registro #}
<div id="form-registro-container" class="row hidden">

	<div class="col-md-4 col-md-offset-4 col-xs-10 col-xs-offset-1"  id="container-registro">

		<form id="form-registro" action="{{ url('session/register') }}">
		
	    	<div class="form-group width-100">
	    		<div class="row width-100 no-margin">
					<div class="col-md-12 width-100">
						
						<label for="">
							<i class="fa fa-email"></i> Ingrese email
						</label>

					</div>

		    		<div class="col-md-12 width-100">
		    			<input type="text" class="form-control validate custom" placeholder="Email" id="username_registro" name="username_registro">	
		    		</div>
	    		</div>
	    		
	    	</div>
	    	<div class="box-error"><p id="username_registro-error" class="error"></p></div>

	    	<div class="form-group width-100">
	    		<div class="row width-100 no-margin">
					<div class="col-md-12 width-100">
						
						<label for="">
							<i class="fa fa-user"></i> Nombre de Usuario
						</label>

					</div>

		    		<div class="col-md-12 width-100">
		    			<input type="text" class="form-control validate custom" placeholder="" id="username_from_email" name="" disabled="">	
		    		</div>
	    		</div>
	    		
	    	</div>

	    	<div class="form-group width-100">
	    		<div class="row width-100 no-margin">
					<div class="col-md-12 width-100">
						
						<label for="">
							<i class="fa fa-key"></i> Ingrese contraseña
						</label>

					</div>

		    		<div class="col-md-12 width-100">
		    			<input type="password" class="form-control validate custom" id="password_registro" name="password_registro">	
		    		</div>
	    		</div>
	    	</div>
	    	<div class="box-error"><p id="password_registro-error" class="error"></p></div>

    	    	<div class="form-group width-100">
    	    		<div class="row width-100 no-margin">
    					<div class="col-md-12 width-100">
    						
    						<label for="">
    							<i class="fa fa-key"></i> Ingrese contraseña nuevamente
    						</label>

    					</div>

    		    		<div class="col-md-12 width-100">
    		    			<input type="password" class="form-control validate custom" id="password_repeat" name="password_repeat">	
    		    		</div>
    	    		</div>
    	    	</div>
    	    	<div class="box-error"><p id="password_repeat-error" class="error"></p></div>

	    	<div class="form-group width-100">
	    		<div class="row width-100 no-margin">


					<button id="btn-registro" type="button" class="btn btn-login"><i class="fa fa-user-plus"></i> Registrar </button>

					<button id="btn-volver-login" type="button" class="btn btn-login"><i class="fa fa-sign-in"></i> Ir a Login</button>


					
	    		</div>
	    	</div>

		</form>

	</div>

</div>




	<!--<input class="fb-login-button" type="button" value="Sign in using Facebook" onclick="checkFacebookLogin();"/>-->




{% endblock %}
