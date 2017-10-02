<?php

    namespace App\Controllers;


    use App\helpers\CallAPI;

    class MarkerController extends ControllerBase {


    	public function renderAllAction(){

    		//API CALL

    		$this->mifaces->newFaces();


            $dataView = [];


            /*
            $markers[] =    [   
                                'id' => 1,
                                'lat' => -33.046273, 
                                'lng' => -71.620073,
                                'name' => 'Baños Plaza Victoria',
                                'confiability' => 'green',
                                'quality' => 'yellow',
                                'price' => 'green',
                                'icon' => "img/markers/bathroom_gray.png",
                            ];

            $markers[] =    [   
                                'id' => 2,
                                'lat' => -33.047325,  
                                'lng' => -71.613502,
                                'name' => 'Baños Parque Italia',
                                'confiability' => 'red',
                                'quality' => 'red',
                                'price' => 'red',
                                'icon' => "img/markers/bathroom_gray.png",
                            ];


            $markers[] =    [   
                                'id' => 3,
                                'lat' => -33.041228, 
                                'lng' => -71.626788,
                                'name' => 'Baños Cerro Concepción',
                                'confiability' => 'yellow',
                                'quality' => 'yellow',
                                'price' => 'green',
                                'icon' => "img/markers/bathroom_gray.png",
                            ];

            */

            $callApi = new CallAPI();

            $data['position_x'] = '-33.0539430';
            $data['position_y'] = '-71.6245970';
            $data['radius'] = '5000';

            $result = $callApi->call('GET',$this->config['urlApi'].'services/find',$data);


            $markers = [];

            foreach ($result as $key => $val) {

                $markers[$key] = $val;
            
                $markers[$key]['lat'] =  $val['x_position'];
                $markers[$key]['lng'] =  $val['y_position'];
                $markers[$key]['icon'] =  'img/markers/bathroom_gray.png';



            }


            foreach ($markers as $key => $val) {

                $dataView['service'] = $val;
                
                $html = $this->view->getPartial('controllers/marker/info',$dataView);

                $markers[$key]['infoWindow']['content'] = $html;

            }

    		$this->mifaces->addToJsonView('markers',$markers);

    		$this->mifaces->run();
    		

    	}



    }
