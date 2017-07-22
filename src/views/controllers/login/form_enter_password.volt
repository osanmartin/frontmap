<div class="form-group" id='div_birthdate'>
    <label for="birthdate" class="date text-light">Fecha nacimiento</label>
    <input id="birthdate" name='birthdate' type="text" class="form-control datepicker-free validate">
    <p id='birthdate-error' class='error'></p>
</div>

<div class="form-group" id='div_password'>
    <label for="password" class="text-light">Nueva Contraseña</label>
    <div class="input-group">
    	<input id="password" name='password' type="password" class="form-control validate">
    	<span class="input-group-addon show-hide-password"><i class="fa fa-eye"></i></span>
    </div>
    <p id='password-error' class='error'></p>
</div>

<div class="form-group" id='div_password'>
    <label for="confirm_password" class="text-light">Confirme Nueva Contraseña</label>
    <div class="input-group">
		<input id="confirm_password" name='confirm_password' type="password" class="form-control validate">
		<span class="input-group-addon show-hide-password"><i class="fa fa-eye"></i></span>
	</div>
    <p id='confirm_password-error' class='error'></p>
</div>

<div class="buttons text-center">
    <button id="login-password-confirm" data-url='{{ url('updatepass') }}' type="button" class="btn btn-aqua btn-round btn-width text-uppercase">Entrar <i class="fa fa-sign-in"></i></button>
</div>

