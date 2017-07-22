<?php

    namespace App\Controllers;

    class TestController extends ControllerBase {


        /**
         * index
         *
         * Carga información necesaria para empezar a tomar horas
         *
         */
        public function indexAction(){

            $flashSessionMsg = "";


            if($this->flash->output() !== null){
                $flashSessionMsg = $this->flash->output();
            }

            echo $flashSessionMsg;

            //$this->view->pick('controllers/test');

        }

    }
