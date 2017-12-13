<?php
	namespace App\Controllers;

	use Phalcon\Mvc\Controller;
	use Phalcon\Mvc\Dispatcher;
    use Phalcon\Mvc\Model\Criteria;

	use App\utilities\Utility;
	use App\AccesoAcl\AccesoAcl;



    /**
     * Class ControllerBase
     *
     * @package App\Controllers
     *          Clase que maneja la metodos a utilizar en todas las clases hijas (sistema)
     */
	class ControllerBase extends Controller
	{

        /**
         * @var  $urlapi URL API
         */
        public $urlapi;


        /**
         *
         * initialize
         *
         *         Metodo que inicializa y setea variables necesarias
         */
	    public function initialize() {

            $this->utility = new Utility();
            $this->view->utility = $this->utility;

            $this->urlapi = $this->config->get("urlApi");

			$this->view->setVar('configuration', $this->configuration );
        }


        /**
         *
         * beforeExecuteRoute
         *
         *
         * @param Dispatcher $dispatcher
         *
         * @return bool
         *
         *
         * Maneja login validacion
         * Carga sucursales a usar en to_do el sistema
         * ACL
         */
		public function beforeExecuteRoute(Dispatcher $dispatcher)
        {
            /*
            #Usuarios logueados

            if ($this->request->isPost() AND !is_null($this->session->get('auth-identity'))) {

                if ($this->security->checkToken()) {
                    
                    $key = $this->security->getTokenKey();
                    $token = $this->security->getToken();
                    $arrToken['key'] = $key;
                    $arrToken['token'] = $token;

                    $this->session->set('csrf-token',$arrToken);

                } else{



                    if($this->request->isAjax()){

                        $this->mifaces->newFaces();
                        $this->mifaces->addToMsg("warning","Por favor, vuelva a intentarlo.");
                        $this->mifaces->run();

                    } else{


                        $this->flash->warning("Por favor, vuelva a intentarlo.");

                        $controller = $this->dispatcher->getControllerName();
                        $action = $this->dispatcher->getActionName();

                        $this->dispatcher->forward([ 'controller' => $controller ,'action' => $action ]);

                    }

                    exit();


                }

            }*/


            // Sin permiso de acceso

            //noAuth -> configuracion de controller y acciones que no tienen que pasar por la autentificacion

            $noAuth = $this->config->noAuth;
            $controller = strtolower($dispatcher->getControllerName());
            $action = strtolower($dispatcher->getActionName());




            /**
             * Control de usuarios logeados
             */

            
            if (!(isset($noAuth[$controller]['*']) || isset($noAuth[$controller][$action]))) {

                $identity = $this->auth->getIdentity();


                if ( ! $this->session->has('auth-identity') ) {

                    $this->auth->remove();
                    $this->session->destroy();

					$this->redirIsAjax();

                    $response = new \Phalcon\Http\Response();
                    $response->redirect("login");
                    $response->send();
                    exit();
                }

                if (!is_array($identity) ) {

					$this->redirIsAjax();

                    $response = new \Phalcon\Http\Response();
                    $response->redirect("login");
                    $response->send();
                    exit();

                } 

            }
	    }

		private function redirIsAjax()
		{
			if( $this->request->isAjax() ) {
				$this->mifaces->newFaces();
				$this->mifaces->addRedir('login');
				$this->mifaces->run();
				exit;
			}
		}


        /**
         *
         * defaultRedirect
         *
         *         Redirige al home de la pagina
         */
		public function defaultRedirect() {
			#redirigimos
			$this->response->redirect("");
			#deshabilitamos la vista para ahorrar procesamiento
			$this->view->disable();
		}


        /**
         *
         * notFoundRedirect
         *
         *         Redigire a una vista de not foun
         */
		public function notFoundRedirect() {
		    $this->view->pick("error_pages/not_found");
        }


        /**
         *
         * contextRedirect
         *
         *
         * @param null $url
         *
         * redirige a una vista dada una url
         */
        public function contextRedirect($url = null) {

            if($url == null)
                $this->defaultRedirect();

            #redirigimos
            $this->response->redirect($url);
            #deshabilitamos la vista para ahorrar procesamiento
            $this->view->disable();
        }

		/**
		 * imp
		 *
		 * imprime array u objetos formateados
		 *
		 */
		public function imp($o){

			echo "<pre>";
			print_r($o);
			exit;
		}
	}
