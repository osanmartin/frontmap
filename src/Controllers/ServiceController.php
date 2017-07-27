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

}
