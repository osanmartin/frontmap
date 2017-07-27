<?php

    namespace App\Controllers;

    class MarkerController extends ControllerBase {


    	public function renderAllAction(){

    		//API CALL

    		$this->mifaces->newFaces();

    		$html = "<p>TODO OK</p>";


    		$markers[] = 	[	
	    						'id' => 1,
	    						'lat' => -33.06, 
	    						'lng' => -71.63, 
	    						'icon' => "img/markers/bathroom_30x30.png",
	    						'infoWindow' => ['content' => $html]
    						];

    		$markers[] = 	[	
	    						'id' => 2,
	    						'lat' => -33.065, 
	    						'lng' => -71.63, 
	    						'icon' => "img/markers/bathroom_30x30.png",
	    						'infoWindow' => ['content' => $html]
    						];


    		$markers[] = 	[	
	    						'id' => 3,
	    						'lat' => -33.068, 
	    						'lng' => -71.63, 
	    						'icon' => "img/markers/bathroom_30x30.png",
	    						'infoWindow' => ['content' => $html]
    						];

    		$this->mifaces->addToJsonView('markers',$markers);

    		$this->mifaces->run();
    		

    	}

    }
