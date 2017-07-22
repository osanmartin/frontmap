{% extends "layouts/main.volt" %}

{% block content %}

<div class="container">
	<div class="row">
		<div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-10 col-md-offset-1">
			<div class="card margin-bottom-50">
				<div class="card-header">
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							{{ image("img/logo-cdmaule-white.png", "height":"35", "style":"margin:15px") }}
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="header-title text-right">
								REGISTRO
							</div>
						</div>
					</div>
				</div>
				<div class="card-body padding-bottom-20">
					<div class="content-step">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-12">

								<form id='form-register-user' action="">
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-4 no-padding-xs">
											<div class="form-group force-margin-bottom-10">
												<label for="rut" class="text-light">RUT <span class="label-help">(Ej: XXXXXXXX-X)</span></label>
												<input id="rut" name='rut' type="text" class="form-control rut-mask validate">
												<p class='error' id='rut-error'></p>
											</div>
										</div>
										<div class="col-xs-12 col-sm-4 col-md-4 no-padding-xs">
											<div class="form-group force-margin-bottom-10">
												<label for="firstname" class="text-light">Nombres</label>
												<input id="firstname" name='firstname' type="text" class="form-control validate">
												<p class='error' id='firstname-error'></p>
											</div>
										</div>
										<div class="col-xs-12 col-sm-4 col-md-4 no-padding-xs">
											<div class="form-group force-margin-bottom-10">
												<label for="lastname" class="text-light">Apellido Paterno</label>
												<input id="lastname" name='lastname' type="text" class="form-control validate">
												<p class='error' id='lastname-error'></p>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-4 no-padding-xs">
											<div class="form-group force-margin-bottom-10">
												<label for="lastname_mother" class="text-light">Apellido Materno</label>
												<input id="lastname_mother" name='lastname_mother' type="text" class="form-control validate">
												<p class='error' id='lastname_mother-error'></p>
											</div>
										</div>
										<div class="col-xs-12 col-sm-4 col-md-4 no-padding-xs">
											<div class="form-group force-margin-bottom-10">
												<label for="birthdate" class="text-light">Fecha de Nacimiento</label>
												<input id="birthdate" name='birthdate' type="text" class="form-control datepicker-free validate">
												<p class='error' id='birthdate-error'></p>
											</div>
										</div>
										<div class="col-xs-12 col-sm-4 col-md-4 no-padding-xs">
											<div class="form-group force-margin-bottom-10">
												<label for="phone_mobile" class="text-light">Teléfono Móvil</label>
												<input id="phone_mobile" name='phone_mobile' type="text" class="form-control validate">
												<p class='error' id='phone_mobile-error'></p>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-4 no-padding-xs">
											<div class="form-group force-margin-bottom-10">
												<label for="phone_fixed" class="text-light">Teléfono Fijo</label>
												<input id="phone_fixed" name='phone_fixed' type="text" class="form-control validate">
												<p class='error' id='phone_fixed-error'></p>
											</div>
										</div>
										<div class="col-xs-12 col-sm-4 col-md-4 no-padding-xs">
											<div class="form-group force-margin-bottom-10">
												<label for="email" class="text-light">Email</label>
												<input id="email" name='email' type="email" class="form-control validate">
												<p class='error' id='email-error'></p>
											</div>
										</div>
										<div class="col-xs-12 col-sm-4 col-md-4 no-padding-xs">
											<div class="form-group force-margin-bottom-10">
												<label for="Sexo" class="text-light">Sexo</label>
												<div id="check-container">
											        <div class="row">
											            <div class="col-xs-6 col-sm-6 type1">
											                <div class="col-xs-1 col-sm-2">
											                    <div class="checkSquare blue">
											                        <input id="sexo_masculino" name='sexo' value="M" type="radio" class="validate" name="sexo">
											                        <label for="sexo_masculino"></label>
											                    </div>
											                </div>
											                <div class="col-xs-8 col-sm-8">
											                    <label class="text-light checkSquare-label">Masculino</label>
											                </div>
											            </div>
											            <div class="col-xs-6 col-sm-6 type1">
											                <div class="col-xs-1 col-sm-2">
											                    <div class="checkSquare blue">
											                        <input id="sexo_femenino" name='sexo' value="F" type="radio" class="validate" name="sexo">
											                        <label for="sexo_femenino"></label>
											                    </div>
											                </div>
											                <div class="col-xs-8 col-sm-8">
											                    <label class="text-light checkSquare-label">Femenino</label>
											                </div>
											            </div>
											        </div>
											    </div>

												<p class='error' id='sexo-error'></p>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-4 col-md-4 no-padding-xs">
											<div class="form-group force-margin-bottom-10">
												<label for="medical_plan_id" class="text-light">Previsión</label>
												<select name="medical_plan_id" id="medical_plan_id" class="form-control chosen-select validate" data-placeholder="Seleccione..." data-noload="1">
													<option value="">Seleccione</option>

													{% for v in medicalplan %}
														<option value="{{ v.id }}">{{ v.name }}</option>
													{% endfor %}
												</select>

												<p class='error' id='medical_plan_id-error'></p>
											</div>
										</div>
										<div class="col-xs-12 col-sm-4 col-md-4 no-padding-xs">
											<div class="form-group force-margin-bottom-10">
												<label for="password" class="text-light">Contraseña</label>
												<div class="input-group">
													<input id="password" name='password' type="password" class="form-control validate">
													<span class="input-group-addon show-hide-password"><i class="fa fa-eye"></i></span>
												</div>
												<p class='error' id='password-error'></p>
											</div>
										</div>
										<div class="col-xs-12 col-sm-4 col-md-4 no-padding-xs">
											<div class="form-group">
												<label for="password_reitera" class="text-light">Repita Contraseña</label>
												<div class="input-group">
													<input id="password_reitera" name='password_reitera' type="password" class="form-control validate">
													<span class="input-group-addon show-hide-password"><i class="fa fa-eye"></i></span>
												</div>
												<p class='error' id='password_reitera-error'></p>
											</div>
										</div>
									</div>
									<div class="buttons text-center">
										<button id="btn-register-user" data-url="{{ url('user/persistUserData') }}" type="button" class="btn btn-round btn-aqua text-uppercase"><i class="fa fa-floppy-o"></i> Registrar</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="card-footer text-center">
					{{ image("img/z-med-white.png", "height":"20", "style":"margin:15px") }}
				</div>
			</div>
		</div>
	</div>
</div>


{% endblock %}
