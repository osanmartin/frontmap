<?php

namespace App\Controllers;
use App\helpers\CallAPI;

class ServiceController extends ControllerBase {


	public function renderModalAddAction(){

		if(!$this->request->isAjax()) {

			$this->defaultRedirect();

		}

		$this->mifaces->newFaces();

		$view = "controllers/service/modal_add_service";
		$dataView = [];
		$toRend = $this->view->getPartial($view,$dataView);

		$this->mifaces->addToRend('modal-master',$toRend);
		$this->mifaces->addToDataView('open_modal',1);

		$this->mifaces->run();

	}

	public function renderModalSearchAction(){

		if(!$this->request->isAjax()) {

			$this->defaultRedirect();

		}

		$this->mifaces->newFaces();

		$view = "controllers/service/modal_search_service";
		$dataView = [];
		$toRend = $this->view->getPartial($view,$dataView);

		$this->mifaces->addToRend('modal-master',$toRend);
		$this->mifaces->addToDataView('open_modal',1);

		$this->mifaces->run();

	}


	public function renderSearchAction(){

	    $this->mifaces->newFaces();

	    $services = [];

	    $post = $this->request->getPost();

	    if(!$this->request->isAjax()){

	    	$this->defaultRedirect();

	    }


	    $callApi = new CallAPI();

	    $data['position_x'] = $post['location_lat'];
	    $data['position_y'] = $post['location_lng'];
	    $data['radius'] = '5000';

	    if(!empty($post['name'])){

	    	$data['name'] = $post['name'];

	    }

	    if(isset($post['category']) && $post['category']){


	    	$data['service_type'] = $post['category'];

	    }

	    $result = $callApi->call('GET',$this->config['urlApi'].'services/find',$data);

	    $services = [];


	    if(!isset($result['description'])){

		    foreach ($result as $key => $val) {

		        $services[$key] = $val;
		    
		        $services[$key]['lat'] =  $val['x_position'];
		        $services[$key]['lng'] =  $val['y_position'];
		        $services[$key]['icon'] =  'img/markers/bathroom_gray.png';



		    }

	        $dataView['services'] = $services;

	    } else {

	    	$dataView['services'] = $services;
	    	$dataView['msg'] = "No se encontraron resultados.";

	    }

	    $view = $this->view->getPartial('controllers/service/_search_table',$dataView);

	    $this->mifaces->addToRend('list-results',$view);
	    $this->mifaces->addToJsonView('services',$services);

	    $this->mifaces->run();




	}


	/**
	*  Realiza votos
	* 
	**/
	public function voteAction(){

		error_log("START VOTE");

		$this->mifaces->newFaces();

		$post = $this->request->getPost();

		if(!isset($post['type']) || !isset($post['vote']) || !isset($post['service'])){

			error_log("FIRST IF");
			$this->mifaces->addToMsg('warning','No fue posible realizar la votación, por favor inténtelo nuevamente.');
			$this->mifaces->addToJsonView('call_status',['error' => true]);
			$this->mifaces->run();
			exit;

		}


		$method_vote = "reports/add";

		if(	$post['type'] != 'active' && 
			$post['type'] != 'quality' && 
			$post['type'] != 'price'){

			error_log("SECOND IF");

			$this->mifaces->addToMsg('warning','No fue posible realizar la votación, por favor inténtelo nuevamente.');
			$this->mifaces->addToJsonView('call_status',['error' => true]);
			$this->mifaces->run();
			exit;

		}


		# Validación para confiabilidad
		if(	$post['type'] == 'active' && $post['vote'] != 0 && $post['vote'] != 1 ){

			$this->mifaces->addToMsg('warning','Hubo un error en la votación. Sólo es posible votar de forma positiva o negativa.');
			$this->mifaces->addToJsonView('call_status',['error' => true]);
			$this->mifaces->run();
			exit;

		}

		# Validación para calidad
		if(	$post['type'] == 'quality' && ($post['vote'] < 1 || $post['vote'] > 5 )){

			$this->mifaces->addToMsg('warning','Hubo un error en la votación. Sólo es posible calificar de 1 a 5 corazones.');
			$this->mifaces->addToJsonView('call_status',['error' => true]);
			$this->mifaces->run();
			exit;

		}

		$param[$post['type']] = $post['vote'];
		$param['services_id'] = $post['service'];

		error_log("PRE API");
		$callApi = new CallAPI();

		$result = $callApi->call('POST',$this->config['urlApi'].$method_vote,$param);

		error_log("POST API");

		if(isset($result['description'])){

			foreach ($result['description'] as $key => $val) {

				# Demasiados intentos
				if($val['code'] == '2004'){

					$this->mifaces->addToMsg('warning','Ya has votado recientemente por este servicio, inténtalo más tarde.');

				} else{

					$this->mifaces->addToMsg('warning','No fue posible realizar la votación, por favor inténtelo nuevamente.');

				}

			}

			
			$this->mifaces->addToJsonView('call_status',['error' => true]);
			$this->mifaces->run();
			exit;

		}

		$this->mifaces->addToJsonView('call_status',['error' => false]);
		$this->mifaces->addToMsg('success','¡Votación realizada correctamente!');

		error_log('END VOTE');
		$this->mifaces->run();




	}



	public function addAction(){

		if(!$this->request->isAjax()){

			$this->defaultRedirect();

		}

		$this->mifaces->newFaces();

		$post = $this->request->getPost();

		if(	!isset($post['lat']) || 
			!isset($post['lng'])){

			$this->mifaces->addToJsonView('call_status',['error' => true]);
			$this->mifaces->addToMsg('warning','Debe seleccionar una dirección válida.');
			$this->mifaces->run();
			exit;

		}

		$rules = [  'address_service' => 'required',
		            'title_service' => 'required',
		            'category' => 'required'];

		$this->valida->validate($post, $rules);

		$this->valida->getErrors();

		if ( $this->valida->failed() ) {

		    $arr = array();

		    foreach ($this->valida->getErrors() as $campo => $error) {
		        $arr[] = array($campo, $error);
		    }

		    $this->mifaces->addToJsonView('call_status',['error' => true]);
		    $this->mifaces->addErrorsForm( $arr ,true);
		    $this->mifaces->run();
		    return;
		}


		$param['service_type_id'] = $post['category'];
		$param['x_position'] = $post['lat'];
		$param['y_position'] = $post['lng'];
		$param['name'] = $post['title_service'];
		$param['price'] = 2;
		$param['quality'] = 5;

		$callApi = new CallAPI();

		$result = $callApi->call('POST',$this->config['urlApi'].'reports/addnew',$param);

		if(isset($result['description']['code'])){

			$this->mifaces->addToMsg('warning','No fue posible realizar la votación, por favor inténtelo nuevamente.');
			$this->mifaces->addToJsonView('call_status',['error' => true]);
			$this->mifaces->run();
			exit;

		}

		$this->mifaces->addToJsonView('call_status',['error' => false]);
		$this->mifaces->addToMsg('success','¡Agregado correctamente!');
		$this->mifaces->run();


	}

}
