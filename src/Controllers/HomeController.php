<?php

    namespace App\Controllers;

    class HomeController extends ControllerBase {

        public function indexAction(){

            $this->view->pick("layouts/main");

        }


        public function loginAction(){

        	$this->view->pick("controllers/login/login");

        }


    }
