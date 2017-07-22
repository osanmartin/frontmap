{% extends "layouts/main.volt" %}


{% block content %}

<div class="container">
	<div class="row margin-top-50">
	    <div class="col-sm-12 text-center">
	        {{ image("img/logo-cdmaule.png", "width":"200", "style":"margin-top:10px;") }}
	        <br>
	        <br>
	        <h2 class="animated bounceIn" style="color:#3888cc"><i class="glyphicon glyphicon-alert"></i>&nbsp; 404</h2>
	        <h4 class="h3" style="color: #82898a;">Elemento no encontrado.</h4>
	        <a href="{{ url('') }}">Volver</a>
	    </div>

	</div>
</div>
   
{% endblock %}