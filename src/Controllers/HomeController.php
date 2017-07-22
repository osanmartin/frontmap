<?php

    namespace App\Controllers;

    class HomeController extends ControllerBase {

        public function indexAction(){

            $this->view->pick("layouts/main");

        }


    }
