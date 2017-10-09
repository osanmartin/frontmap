<?php

    namespace App\helpers;

    use Phalcon\Mvc\User\Plugin;

    /**
     * Retorna datos necesarios para cargar las vista e impresión
     *
     * consulta a la base de datos la información necesaria para generar las vistas de impresión
     *
     * @subpackage   helpers
     * @category     Print
     */
    class CallAPI extends Plugin {

        public $error;
        private $header = array();
        protected $curl;
        protected $pass;

        private $errorMessage = array(
            'secret_token' => 'Falta Token de autenticación'
        );

        function __construct() {

            $this->curl = curl_init();
        }

        // Method: POST, PUT, GET etc
        // Data: array("param" => "value") ==> index.php?param=value

        public function call($method, $url, $data = false, $urlencoded = false)
        {
            if( $data != false ) {
                $this->data = $data;
            } else{
                $this->data = array();
            }

            $this->setAuth();

            switch ($method)
            {
                case "POST":
                    curl_setopt($this->curl, CURLOPT_POST, 1);

                    $this->setHeader('accesstoken: '.$this->session->get('accesstoken'));


                    if($urlencoded) {
                        $this->data = http_build_query($this->data);
                        $this->setHeader('Content-Type: application/x-www-form-urlencoded');
                    }

                    curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->data);

                    break;

                case "PUT":

                    curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "PUT");

                    if($urlencoded) {
                        $this->data = http_build_query($this->data);
                        $this->setHeader('Content-Type: application/x-www-form-urlencoded');
                    }

                    if ($this->data)
                        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->data);

                    break;

                case "DELETE":
                    curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                    break;

                default:
                    if ($this->data)
                        $url = sprintf("%s?%s", $url, http_build_query($this->data));
            }



            if( count($this->header) > 0 ) {
                curl_setopt($this->curl, CURLOPT_HTTPHEADER, $this->header );
            }

            curl_setopt($this->curl, CURLOPT_URL, $url);
            curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($this->curl);

            curl_close($this->curl);



            $this->verifyResponse($result);


            $json = json_decode($result, true);

            return $json;
        }

        /**
         * setHeader
         *
         * se genera un array con las cabeceras
         *
         * @param string $header
         */
        public function setHeader($header) {

            array_push($this->header, (string)$header);
        }

        public function setAuth() {


            if( $this->session->has('accesstoken') ){

                $tokens = $this->session->get('accesstoken');
                $this->setHeader("accesstoken: {$tokens}");
            }

        }

        private function verifyResponse($result)
        {



            if($this->config->get("switchUtils")['production'] == true AND $result == false){


                if($this->request->isAjax()){
                    $this->mifaces->addToMsg("info", "El servidor ha tardado demasiado en responder, inténtelo nuevamente." );
                    #$this->mifaces->addToDataView('status', false);
                    $this->mifaces->run();
                    exit;
                }
                else {

                    $toRend = $this->view->getPartial('controllers/error_pages/timeout', []);
                    print($toRend);
                    exit();

                }

            }

            $datos = json_decode($result, true);

      
            /*

            if( $datos['error'] ) {

                if($this->request->isAjax()){

                    $this->mifaces->newFaces();

                    if( isset($datos['error_description']) AND trim($datos['error_description']) != '' ) {

                            $this->mifaces->addToMsg("info", $datos['error_description']);
                    }

                    $this->mifaces->addToDataView('status', false);
                    $this->mifaces->run();
                    exit();

                } else {

                    $toRend = $this->view->getPartial('controllers/error_pages/database', []);
                    print($toRend);
                    exit();

                }
            }*/

            


            if(isset($datos['description'])){

                foreach ($datos['description'] as $val) {

                    if( isset($val['code']) && $val['code'] == 401) {

                        $this->renderLogout();

                    }

                    
                }

            }

        }

        /**
         * logout
    	 *
         * cierra la sesión
         *
         * @return boolean
         */
        public function logoutAction() {

        	$this->auth->remove();
            $this->session->destroy();
            return $this->response->redirect('login');
        }

        private function renderLogout(){


            $msg = "Sesión expirada o inexistente. Favor iniciar sesión.";

            $this->auth->remove();
            //el session destroy debe manejarse en cada controllador o metodo que lo requiera.

            $flashSession = new \Phalcon\Flash\Session();

            if($this->request->isAjax()){     

                $flashSession->notice($msg);
                $this->mifaces->addRedir('login');
                $this->mifaces->run();

            } else {

                $this->flashSession->notice($msg);
                $response = new \Phalcon\Http\Response();
                $response->redirect("login");
                $response->send();

            }

            exit;

            

        }





    }


