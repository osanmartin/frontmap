<div class="iw-content">

	<div class="row no-margin">
		<div class="col-xs-12">
			<strong>{{service['name']}}</strong>
		</div>
	</div>
	<div class="row no-margin">
		<div class="col-xs-2">
			Horario:
		</div>
		<div class="col-xs-10">
			Lunes a Viernes, 12:00 a 21:00 hrs
		</div>
	</div>

	{% if (service['confiability'] >= 66.6) %}
		{% set border_confiability = 'border-green' %}
	{% elseif (service['confiability'] >= 33.3 and service['confiability'] < 66.6) %}
		{% set border_confiability = 'border-yellow' %}
	{% elseif (service['confiability'] < 33.3 ) %}
		{% set border_confiability = 'border-red' %}
	{% endif %}

	{% if (service['quality'] >= 6.66) %}
		{% set border_quality = 'border-green' %}
	{% elseif (service['quality'] >= 3.33 and service['quality'] < 6.66) %}
		{% set border_quality = 'border-yellow' %}
	{% elseif (service['quality'] < 3.33 ) %}
		{% set border_quality = 'border-red' %}
	{% endif %}

	{% if (service['quality'] >= 6.66) %}
		{% set border_quality = 'border-green' %}
	{% elseif (service['quality'] >= 3.33 and service['quality'] < 6.66) %}
		{% set border_quality = 'border-yellow' %}
	{% elseif (service['quality'] < 3.33 ) %}
		{% set border_quality = 'border-red' %}
	{% endif %}

	<div class="row no-margin">
		<div class="col-xs-4 text-center info-validez">
			<a href="javascript:void(0)">
				<img class="{{border_confiability}}" src="img/markers/meter.png" alt="">
				<span>Confiabilidad</span>
				<div class="menu btn14 validez" data-menu="14">
				  <div class="icon-circle margin-top-8"></div>
				  <div class="icon"></div>
				</div>
				<div class="btn-validez hidden">
					<div class="btn-validez-negativa">
						<i class="fa fa-thumbs-o-down"></i>
					</div>
					<div class="btn-validez-positiva">
						<i class="fa fa-thumbs-o-up"></i>
					</div>
				</div>
			</a> 

		</div>
		<div class="col-xs-4 text-center info-calidad">
			<a href="javascript:void(0)">
				<img class="{{border_quality}}" src="img/markers/badge.png" alt="">
				<span>Calidad</span>
				<div class="menu btn14 calidad" data-menu="14">
				  <div class="icon-circle margin-top-8"></div>
				  <div class="icon"></div>
				</div>
				<div class="btn-calidad hidden">

					<input type="hidden"/>
			
				</div>


			</a> 
			<div class="cssload-jar">
				<div class="cssload-base">
					<div class="cssload-liquid"> </div>
					<div class="cssload-wave"></div>
					<div class="cssload-wave"></div>
					<div class="cssload-bubble"></div>
					<div class="cssload-bubble"></div>
				</div>
			</div>
		</div>

		<div class="col-xs-4 text-center info-precio">
			<a href="javascript:void(0)">
				<img class="transparent" src="img/markers/pig_money_sad.png" data-target="1">
				<img src="img/markers/pig_money_normal.png" alt="" data-target="2">
				<img class="transparent" src="img/markers/pig_money_happy.png" data-target="3">
				<span>Precio</span>
				<div class="menu btn14 precio" data-menu="14">
				  <div class="icon-circle margin-top-8"></div>
				  <div class="icon"></div>
				</div>
			</a> 
			<div class="btn-precio">

				<div class="btn-precio-low hidden" data-target="3">
					<span> {{ prices_range[service['service_types_id']][0]['price'] }} </span>
				</div>
				<div class="btn-precio-mid hidden" data-target="2">
					<span> {{ prices_range[service['service_types_id']][1]['price'] }} </span>
				</div>
				<div class="btn-precio-high hidden" data-target="1">
					<span> {{ prices_range[service['service_types_id']][2]['price'] }} </span>
				</div>
			</div>
		</div>
	</div>



</div>

