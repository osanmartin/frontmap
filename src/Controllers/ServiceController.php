<?php

namespace App\Controllers;

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

	    $services[] =    [   
	                        'id' => 1,
	                        'lat' => -33.046273, 
	                        'lng' => -71.620073,
	                        'name' => 'Baños Plaza Victoria',
	                        'confiability' => 'green',
	                        'quality' => 'yellow',
	                        'price' => 'green'
	                    ];

	    $services[] =    [   
	                        'id' => 2,
	                        'lat' => -33.047325,  
	                        'lng' => -71.613502,
	                        'name' => 'Baños Parque Italia',
	                        'confiability' => 'red',
	                        'quality' => 'red',
	                        'price' => 'red'
	                    ];


	    $services[] =    [   
	                        'id' => 3,
	                        'lat' => -33.041228, 
	                        'lng' => -71.626788,
	                        'name' => 'Baños Cerro Concepción',
	                        'confiability' => 'yellow',
	                        'quality' => 'yellow',
	                        'price' => 'green'
	                    ];

        $dataView['services'] = $services;

	    $view = $this->view->getPartial('controllers/service/_search_table',$dataView);

	    $this->mifaces->addToRend('list-results',$view);
	    $this->mifaces->addToJsonView('services',$services);

	    $this->mifaces->run();


	}

}
