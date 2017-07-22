<div class="content-step">
	<div class="main-title text-center">
		ESPECIFICACIÓN RESERVA N° {{ number_reservation }}
	</div>
	<br>
	<form action="">
		<div id="scheduling_details" class="scheduling-details">
			<div class="row">

                <div class="col-xs-12 no-padding-xs">
					<div class="title text-light">
						<i class="fa fa-file-text-o"></i> Datos Paciente
					</div>
					<div class="schedule-resume text-left">
						<div class="resume">
							<b><i class="fa fa-calendar"></i> Nombre:</b> {{ data_user.name }}<br>
							<b><i class="fa fa-clock-o"></i> Rut:</b>  {{ data_user.rut }}<br>
                            <b><i class="fa fa-calendar-times-o"></i> Previsión: </b> {{ data_user.medical_plan }} <br>
						</div>
					</div>
				</div>

                <div class="col-xs-12 no-padding-xs">
					<div class="title text-light">
						<i class="fa fa-file-text-o"></i> Datos Especialista
					</div>
					<div class="schedule-resume text-left">
						<div class="resume">
							<b><i class="fa fa-calendar"></i> Especialista:</b> {{ data_turn.specialist }}<br>
							<b><i class="fa fa-clock-o"></i> Especialidad:</b>  {{ data_turn.specialty }}<br>
							<b><i class="fa fa-calendar-times-o"></i> Centro Médico: </b> {{ data_turn.branch_office }} <br>
						</div>
					</div>
				</div>

                <div class="col-xs-12 no-padding-xs">
					<div class="title text-light">
						<i class="fa fa-file-text-o"></i> Datos Contacto Paciente
					</div>
					<div class="schedule-resume text-left">
						<div class="resume">
							<b><i class="fa fa-calendar"></i> Teléfono Móvil:</b> {{ data_user.phone_mobile }}<br>
							<b><i class="fa fa-clock-o"></i> Teléfono Fijo:</b>  {{ data_user.phone_fixed }}<br>
							<b><i class="fa fa-calendar-times-o"></i> Email: </b> {{ data_user.email }} <br>
						</div>
					</div>
				</div>

				<div class="col-xs-12 no-padding-xs">
					<div class="title text-light">
						<i class="fa fa-file-text-o"></i> Datos Reserva
					</div>
					<div class="schedule-resume text-left">
						<div class="resume">
							<b><i class="fa fa-calendar"></i> Fecha:</b> {{ data_turn.date }}<br>
							<b><i class="fa fa-clock-o"></i> Hora:</b>  {{ data_turn.time }} Hrs.<br>
							<b><i class="fa fa-calendar-times-o"></i> Estado: <span class="status">{{ data_turn.turn_state }}</span></b>
						</div>
					</div>
				</div>

			</div>
		</div>
	</form>
</div>
