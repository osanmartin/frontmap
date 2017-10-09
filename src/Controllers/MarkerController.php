<?php

    namespace App\Controllers;


    use App\helpers\CallAPI;

    class MarkerController extends ControllerBase {


    	public function renderAllAction(){

    		//API CALL

    		$this->mifaces->newFaces();


            $dataView = [];

            $callApi = new CallAPI();

            $data['position_x'] = '-33.0539430';
            $data['position_y'] = '-71.6245970';
            $data['radius'] = '5000';

            # Obtiene servicios
            $result = $callApi->call('GET',$this->config['urlApi'].'services/find',$data);


            $markers = [];

            foreach ($result as $key => $val) {

                $markers[$key] = $val;
            
                $markers[$key]['lat'] =  $val['x_position'];
                $markers[$key]['lng'] =  $val['y_position'];
                $markers[$key]['icon'] =  'img/markers/bathroom_gray.png';



            }

            #obtiene rangos de precios
            $callApi = new CallAPI();

            $prices_range = $callApi->call('GET',$this->config['urlApi'].'price_ranges', false);

            $auxPrice = [];
            foreach ($prices_range as $val) {
                $auxPrice[$val['service_type_id']][] = $val;
            }



            $prices_range = $auxPrice;

            $dataView['prices_range'] = $prices_range;


            # se setea html en cada servicio

            foreach ($markers as $key => $val) {

                $dataView['service'] = $val;

                $html = $this->view->getPartial('controllers/marker/info',$dataView);

                $markers[$key]['infoWindow']['content'] = $html;

            }


    		$this->mifaces->addToJsonView('markers',$markers);

    		$this->mifaces->run();
    		

    	}



    }
