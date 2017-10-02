		    <!--

			-->

{% if services is defined and services %}


	<div class="row text-center table-header">
		<div class="col-xs-2 ">
			
			<span class="hidden-xs">Distancia</span>

		</div>
		<div class="col-xs-2">
			
			<span class="hidden-xs">Tiempo</span>

		</div>
		<div class="col-xs-6">
			Servicio

		</div>
		<div class="col-xs-2">
			
			

		</div>
	</div>

	{% for service in services %}

		<div class="row text-center table-row">
			<div class="col-xs-2 size-20">
				<span id="distance-{{ service['id'] }}"></span> <span class="size-12 bold-800">KM</span>
			</div>
			<div class="col-xs-2 size-20">
				<span id="duration-{{ service['id'] }}"></span> <span class="size-12 bold-800">min</span>
			</div>
			<div class="col-xs-6">
				<div>{{ service['name'] }}</div>
				<div class="size-20">
					<span class="icon-note background-{{ service['confiability'] }} " title="Confiabilidad" data-toggle="tooltip"><i class="fa fa-tachometer "></i></span>
					<span class="icon-note background-{{ service['quality'] }}" title="Calidad" data-toggle="tooltip"><i class="fa fa-heart-o "></i></span>
					<span class="icon-note background-{{ service['price'] }}" title="Precio" data-toggle="tooltip"><i class="fa fa-money "></i></span>
				</div>
			</div>
			<div class="col-xs-2 no-padding">
				<button type="button" class="btn btn-action-search" data-lat="{{ service['lat'] }}" data-lng="{{ service['lng'] }}" > <i class="fa fa-map-marker"></i> Ir</button>
			</div>
		</div>

	{% endfor %}

{% elseif msg is defined and msg %}

  	<div class="row">

		<div class="col-xs-12 text-center msg-search">
			<span>{{ msg|uppercase }}</span>
		</div>
	</div>


{% else %}

  	<div class="row">

		<div class="col-xs-12 text-center msg-search">
			<span>AÚN NO SE HA REALIZADO NINGUNA BÚSQUEDA.</span>
		</div>
	</div>

{% endif %}