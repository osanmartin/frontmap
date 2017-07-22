{% if userDetails is not defined or userDetails == false or userDetails|length == 0 %}

	{% set name = "*No indicado" %}
	{% set rut = "*No indicado" %}
	{% set prevision = "*No indicado" %}
	{% set avatar = "pic/avatars/default.png" %}
    {% set no_result = "no_result"%}

{% elseif userDetails['data_user'] is defined %}

	{% set data_user = userDetails['data_user'] %}

	{% set name = data_user.name %}
	{% set rut = data_user.rut %}
	{% set prevision = data_user.medical_plan %}
	{% set avatar = data_user.avatar %}
    {% set no_result = " " %}


{% else %}

	{% set name = userDetails['firstname'] ~ " " ~ userDetails['lastname'] ~ " " ~ userDetails['lastname_mother']%}
	{% set rut = userDetails['rut'] %}
	{% set prevision = userDetails['medical_plan']['name'] %}
	{% set avatar = userDetails['avatar'] %}
    {% set no_result = " "%}

{% endif %}

<div class="card-header">
	<div class="row">

		<div class="col-xs-12 no-padding container-avatar text-center">
			{{ image("img/logo-cdmaule-white.png", "height":"45", "style":"margin:20px") }}
		</div>

	</div>
</div>
