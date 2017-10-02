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

}
