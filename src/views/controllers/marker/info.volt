<div class="iw-content">

	<div class="row no-margin mgn-bottom-10">
		<div class="col-xs-12">
			<strong>{{service['name']}}</strong>
		</div>
	</div>

	{% if (service['confiability'] >= 66.6) %}
		{% set border_confiability = 'bgnd-green' %}
		{% set msg_confiability = "Este servicio con seguridad está en funcionamiento" %}
	{% elseif (service['confiability'] >= 33.3 and service['confiability'] < 66.6) %}
		{% set border_confiability = 'bgnd-yellow' %}
		{% set msg_confiability = "Este servicio es probablemente está en funcionamiento" %}
	{% elseif (service['confiability'] < 33.3 ) %}
		{% set border_confiability = 'bgnd-red' %}
		{% set msg_confiability = "Este servicio no existe o no está en funcionamiento" %}
	{% endif %}

	{% if (service['quality'] >= 6.66) %}
		{% set border_quality = 'bgnd-green' %}
		{% set msg_quality = "Este servicio es de alta calidad" %}
	{% elseif (service['quality'] >= 3.33 and service['quality'] < 6.66) %}
		{% set border_quality = 'bgnd-yellow' %}
		{% set msg_quality = "Este servicio es de calidad media" %}
	{% elseif (service['quality'] < 3.33 ) %}
		{% set border_quality = 'bgnd-red' %}
		{% set msg_quality = "Este servicio es de baja calidad" %}
	{% endif %}


	{% if service['price_level'] is defined %}

		{% if service['price_level'] == 1 %}
			{% set border_price = 'bgnd-green' %}
			{% set msg_price = "Este servicio es considerado de bajo costo" %}
		{% elseif service['price_level'] == 2 %}
			{% set border_price = 'bgnd-yellow' %}
			{% set msg_price = "Este servicio es considerado de costo regular" %}
		{% elseif service['price_level'] == 3 %}
			{% set border_price = 'bgnd-red' %}
			{% set msg_price = "Este servicio es considerado costoso" %}
		{% endif %}

	{% else %}


		{% set msg_price = 'Se desconoce precio' %}
		{% set border_price = 'bgnd-white' %}

	{% endif %}

	<div class="row no-margin">
		<div class="col-xs-4 text-center info-validez">
			<a href="javascript:void(0)" title="{{ msg_confiability }}" data-toggle="tooltip">
				<img id="indicator-confiability" class="{{border_confiability}}" src="img/markers/meter.png" alt="" >
				<span>Confiabilidad</span>
			</a> 
				<div id="menu-vote-confiability" class="menu btn14 validez" data-menu="14">
				  <div id="indicator-confiability" class="icon-circle margin-top-8"></div>
				  <div id="indicator-confiability" class="icon"></div>
				</div>
				<div class="btn-validez hidden">
					<div class="btn-validez-negativa">
						<i id="btn-confiability-negative" class="fa fa-thumbs-o-down"></i>
					</div>
					<div  class="btn-validez-positiva">
						<i id="btn-confiability-positive" class="fa fa-thumbs-o-up"></i>
					</div>
				</div>


		</div>
		<div class="col-xs-4 text-center info-calidad" >
			<a href="javascript:void(0)" title="{{ msg_quality }}" data-toggle="tooltip">
				<img id="indicator-quality" class="{{border_quality}}" src="img/markers/badge.png" alt="" >
				<span>Calidad</span>

			</a> 
				<div id="menu-vote-quality" class="menu btn14 calidad" data-menu="14">
				  <div id="menu-vote-quality" class="icon-circle margin-top-8"></div>
				  <div id="menu-vote-quality" class="icon"></div>
				</div>
				<div id="btn-quality" class="btn-calidad hidden">

					<input type="hidden"/>
			
				</div>


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
			<a href="javascript:void(0)" title="{{ msg_price }}" data-toggle="tooltip">
				<img id="indicator-price" class="transparent {{ border_price }}" src="img/markers/pig_money_sad.png" data-target="1" >
				<img id="indicator-price" class="{{ border_price }}" src="img/markers/pig_money_normal.png" alt="" data-target="2" >
				<img id="indicator-price" class="transparent {{ border_price }}" src="img/markers/pig_money_happy.png" data-target="3" >
				<span>Precio</span>
			</a> 
				<div id="menu-vote-price" class="menu btn14 precio" data-menu="14">
				  <div id="menu-vote-price" class="icon-circle margin-top-8"></div>
				  <div id="menu-vote-price" class="icon"></div>
				</div>

			<div class="btn-precio">

				<div class="btn-precio-low hidden" data-target="3" data-id="{{ prices_range[service['service_types_id']][0]['id'] }}" >
					<span id="btn-price-cheap"> {{ prices_range[service['service_types_id']][0]['price'] }} </span>
				</div>
				<div class="btn-precio-mid hidden" data-target="2" data-id="{{ prices_range[service['service_types_id']][1]['id'] }}">
					<span id="btn-price-regular"> {{ prices_range[service['service_types_id']][1]['price'] }} </span>
				</div>
				<div class="btn-precio-high hidden" data-target="1" data-id="{{ prices_range[service['service_types_id']][2]['id'] }}">
					<span id="btn-price-expensive"> {{ prices_range[service['service_types_id']][2]['price'] }} </span>
				</div>
			</div>
		</div>
	</div>


	<div id="vote-action" data-url="{{ url('service/vote') }}"></div>
	<input id="service" name="service" type="hidden" value="{{service['id']}}">
</div>

