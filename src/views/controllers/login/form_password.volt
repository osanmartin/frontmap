<input type="hidden" name="type" value="{{ type }}">

<div class="form-group" id='div_password'>
    <label for="password" class="text-light">Contraseña</label>
    <div class="input-group">
    	<input id="password" name='password' type="password" class="form-control validate">
    	<span class="input-group-addon show-hide-password"><i class="fa fa-eye"></i></span>
    </div>
    <p id='password-error' class='error'></p>
</div>

<div class="buttons text-center">
    <button id="login" data-url='{{ url('loginpost') }}' type="button" class="btn btn-aqua btn-round btn-width text-uppercase">Entrar <i class="fa fa-sign-in"></i></button>
</div>
