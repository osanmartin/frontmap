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


	<input class="fb-login-button" type="button" value="Sign in using Facebook" onclick="checkFacebookLogin();"/>




{% endblock %}
