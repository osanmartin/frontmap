{% extends "layouts/login.volt" %}

{% block content %}

<div class="container">
	<div class="row margin-top-50 margin-top-20-xs">
		<div class="col-xs-12 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4">
			<div class="card">
				<div class="card-header">
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							{{ image("img/logo-cdmaule-white.png", "height":"35", "style":"margin:15px") }}
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="header-title text-right">
								LOGIN
							</div>
						</div>
					</div>
				</div>
				<div class="card-body">
					<form action="{{ url('loginpost') }}" method="post" id='form_login'>
						<div class="form-group">
							<label for="rut" class="text-light">RUT <span class="label-help">(Ej: XXXXXXXX-X)</span></label>
							<input id="rut" name='rut' type="text" class="form-control rut-mask validate">

							<p id='rut-error' class='error'></p>
						</div>

						<div id="input-login">

							<div class="buttons text-center">
								<button id="confirm_user" data-url='{{ url('verifyUser') }}' type="button" class="btn btn-aqua btn-round btn-width text-uppercase">Verificar <i class="fa fa-check-square-o"></i></button>
							</div>
						</div>

						<div class="margin-top-30 text-center">
							<a href="{{"javascript:0"}}" data-toggle="tooltip" title="Favor contactar a la Clínica" data-placement="bottom" class="text-cloud">¿Olvidaste tu Contraseña?</a>
						</div>


						<div class="before-footer text-center">
							<a href="{{ url('registroUsuarios') }}" class="text-cloud">Registrarse</a>
						</div>
					</form>
				</div>
				<div class="card-footer text-center">
					{{ image("img/z-med-white.png", "height":"20", "style":"margin:15px") }}
				</div>
			</div>
		</div>
	</div>
</div>




{% endblock %}
