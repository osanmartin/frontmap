<div id="modal-search-service" class="modal fade" role="dialog">
	<div class="modal-dialog">
		
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><i class="fa fa-search"></i> Buscar Servicio </h4>
				</div>
			<div class="modal-body">
		    
			    <form action="">

	    	    	<div class="form-group">
	    	    		<div class="row">
	    					<div class="col-md-12">
	    						
	    						<label for="">
	    							Servicio
	    						</label>

	    					</div>

	    		    		<div class="col-md-12">
	    		    			<input type="text" class="form-control custom" placeholder="Nombre del Servicio">	
	    		    		</div>
	    	    		</div>
	    	    	</div>

	    	    	<div class="form-group">
	    	    		<div class="row">
	    					<div class="col-md-12">
	    						
	    						<label for="">
	    							Categoría de Servicio
	    						</label>

	    					</div>

	    		    		<div class="col-md-12">
	    		    			<select class="form-control custom" name="" id="">
	    		    				<option selected value="">Todos</option>
	    		    				<option value="">Baños</option>
	    		    				<option value="">Puntos de recolección</option>
	    		    			</select>
	    		    		</div>
	    	    		</div>
	    	    	</div>

			    </form>

			    <a class="btn btn-modal-options btn-search-modal" data-url="{{ url('service/rendersearch') }}"> Buscar </a>

			</div>
			<div id="list-results" class="modal-footer">

				{{ partial('controllers/service/_search_table') }}

			</div>
		</div>

	</div>
</div>