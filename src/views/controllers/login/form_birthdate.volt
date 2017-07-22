<input type="hidden" name="type" value="{{ type }}">

<div class="form-group" id='div_birthdate'>
    <label for="birthdate" class="date text-light">Fecha nacimiento</label>
    <input id="birthdate" name='birthdate' type="text" class="form-control datepicker-free validate">
    <p id='birthdate-error' class='error'></p>
</div>

<div class="buttons text-center">
    <button id="login-birthday" data-url='{{ url('verifybirthday') }}' type="button" class="btn btn-aqua btn-round btn-width text-uppercase">Entrar <i class="fa fa-sign-in"></i></button>
</div>
