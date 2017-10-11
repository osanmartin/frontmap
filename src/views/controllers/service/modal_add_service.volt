<div id="modal-new-service" class="modal fade" role="dialog">
	<div class="modal-dialog">
		
		<!-- Modal content-->
		<div class="modal-content">
		  <div class="modal-header">
		    <button type="button" class="close" data-dismiss="modal">&times;</button>
		    <h4 class="modal-title"><i class="fa fa-plus-circle"></i> Nuevo Servicio </h4>
		  </div>
		  <div class="modal-body">

		    <form id="form-add-service" action="{{ url('service/add') }}">

		    	<div class="form-group">
		    		<div class="row">
						<div class="col-md-12">
							
							<label for="">
								Dirección <span id="container-address" class="hidden font-success"> - <span id="address-selected"></span> <i id="check-address" class="fa fa-check-circle"></i> </span>
							</label>

						</div>

			    		<div class="col-md-12">
			    			<input id="address_service" name="address_service" type="text" class="form-control custom" placeholder="Ubicación de Servicio">	
			    		</div>
		    		</div>
		    	</div>
		    	<div class="box-error"><p id="address_service-error" class="error"></p></div>

    	    	<div class="form-group">
    	    		<div class="row">
    					<div class="col-md-12">
    						
    						<label for="">
    							Título
    						</label>

    					</div>

    		    		<div class="col-md-12">
    		    			<input type="text" class="form-control custom" placeholder="Nombre del Servicio" id="title_service" name="title_service">	
    		    		</div>
    	    		</div>
    	    	</div>
    	    	<div class="box-error"><p id="title_service-error" class="error"></p></div>

    	    	<div class="form-group">
    	    		<div class="row">
    					<div class="col-md-12">
    						
    						<label for="">
    							Categoría
    						</label>

    					</div>

    		    		<div class="col-md-12">
    		    			<select class="form-control custom" name="category" id="category">
    		    				<option value="1">Baños</option>
    		    				<option value="2">Otro</option>
    		    			</select>
    		    		</div>
    	    		</div>
    	    	</div>
    	    	<div class="box-error"><p id="category-error" class="error"></p></div>

		    </form>

		  </div>
		  <div class="modal-footer">
		  	<div class="row">
		  		<div class="col-xs-6 float-right">
		  			<a id="btn-add-service" class="btn btn-modal-save">Guardar</a>
		  		</div>
		  		<div class="col-xs-6 float-left">
		  			<a class="btn btn-modal-close" data-dismiss="modal">Cancelar</a>
		  		</div>
		    </div>
		  </div>
		</div>

	</div>
</div>