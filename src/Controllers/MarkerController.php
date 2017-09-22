<?php

    namespace App\Controllers;

    class MarkerController extends ControllerBase {


    	public function renderAllAction(){

    		//API CALL

    		$this->mifaces->newFaces();


            $dataView = [];

    		$html = $this->view->getPartial('controllers/marker/info',$dataView);

            $markers[] =    [   
                                'id' => 1,
                                'lat' => -33.046273, 
                                'lng' => -71.620073,
                                'name' => 'Baños Plaza Victoria',
                                'confiability' => 'green',
                                'quality' => 'yellow',
                                'price' => 'green',
                                'icon' => "img/markers/bathroom_30x30.png",
                                'infoWindow' => ['content' => $html]
                            ];

            $markers[] =    [   
                                'id' => 2,
                                'lat' => -33.047325,  
                                'lng' => -71.613502,
                                'name' => 'Baños Parque Italia',
                                'confiability' => 'red',
                                'quality' => 'red',
                                'price' => 'red',
                                'icon' => "img/markers/bathroom_30x30.png",
                                'infoWindow' => ['content' => $html]
                            ];


            $markers[] =    [   
                                'id' => 3,
                                'lat' => -33.041228, 
                                'lng' => -71.626788,
                                'name' => 'Baños Cerro Concepción',
                                'confiability' => 'yellow',
                                'quality' => 'yellow',
                                'price' => 'green',
                                'icon' => "img/markers/bathroom_30x30.png",
                                'infoWindow' => ['content' => $html]
                            ];


  
    		$this->mifaces->addToJsonView('markers',$markers);

    		$this->mifaces->run();
    		

    	}



    }
