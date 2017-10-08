{% extends "layouts/login.volt" %}

{% block content %}

<video poster="background.gif" id="bgvid" playsinline autoplay muted loop>

<source src="background.mp4" type="video/mp4">
</video>

	<span id="logo-login">
	    <div id="logo">
	        <a id="logo-image" href="#">
	            <img class="navbar-brand-image" src="img/favicon-32x32.png"> <span>FrontMap</span>
	        </a>
	    </div>
	</span>


	<div id="container-login" class="container">

		<form id="form-login" action="{{ url('session/loginpost') }}">
		
	    	<div class="form-group width-100">
	    		<div class="row width-100 no-margin">
					<div class="col-md-12 width-100">
						
						<label for="">
							Usuario
						</label>

					</div>

		    		<div class="col-md-12 width-100">
		    			<input type="text" class="form-control custom" placeholder="Dirección de correo" id="username" name="username">	
		    		</div>
	    		</div>
	    		
	    	</div>
	    	<div class="box-error"><p id="username-error" class="error"></p></div>

	    	<div class="form-group width-100">
	    		<div class="row width-100 no-margin">
					<div class="col-md-12 width-100">
						
						<label for="">
							Contraseña
						</label>

					</div>

		    		<div class="col-md-12 width-100">
		    			<input type="password" class="form-control custom" id="password" name="password">	
		    		</div>
	    		</div>
	    	</div>
	    	<div class="box-error"><p id="password-error" class="error"></p></div>

	    	<div class="form-group width-100">
	    		<div class="row width-100 no-margin">

					<a id="btn-login" class="btn btn-login">Login</a>
					
	    		</div>
	    	</div>

    	</form>

	</div>


	<!--<input class="fb-login-button" type="button" value="Sign in using Facebook" onclick="checkFacebookLogin();"/>-->




{% endblock %}
