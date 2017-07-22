<?php

    namespace App\Controllers;

    use App\helpers\CallAPI;
    use App\helpers\Errors;
    use App\Business\UserBsn;

    class SessionController extends ControllerBase {




        public function indexAction()
        {
            $vars = $this->session->get('auth-identity');

            if (isset($vars['id'])) {

                return $this->response->redirect('');

            } else {

                $flashSessionMsg = "";


                $flashSessionMsg = $this->flash->getMessages();
                    
                // KILL SESSION
                $this->session->destroy();

                $flashStringArray = [];

                if (is_array($flashSessionMsg)) {
                    
                    foreach ($flashSessionMsg as $type => $msgs) {
                        
                        foreach ($msgs as $key => $message) {

                            switch ($type) {
                                case 'error':
                                    $class = "errorMessage";
                                    break;
                                case 'warning':
                                    $class = "warningMessage";
                                    break;
                                case 'success':
                                    $class = "successMessage";
                                    break;
                               default:
                                    $class = "noticeMessage";
                                     break;
                            }

                            $flashStringArray[$class] .= $message;
                        }
                    }

                }



                $this->view->setVar('flashSessionMsg',$flashStringArray);
                $this->assets->addJs("js/pages/login.js");
                $this->view->pick("controllers/login/login");
            }
        }

        public function loginpostAction() {

            $this->mifaces->newFaces();

            if ( ! $this->request->isAjax() ) {

                $this->mifaces->addToMsg("Error", "Error, Por favor actualice la página.");
                $this->mifaces->run();
                return;
            }

            $post = $this->request->getPost();

            if( ! isset($post) ) {
                $this->mifaces->addToMsg("Error", "Error, Por favor actualice la página.");
                $this->mifaces->run();
                return;
            }

            $rules = $this->getRulesByType($post['type']);


            $this->valida->validate($post, $rules);

            $messages = array(
                'rut' =>     "Por favor, ingrese su RUT.",
                'type'   => "Ha ocurrido un error, por favor, actualice la página",
                'password' => "Por favor, ingrese la contraseña",
                'birthdate' => "Por favor, ingrese su fecha de nacimiento"
            );

            $this->valida->getErrors();

            if ( $this->valida->failed() ) {

                $arr = array();

                foreach ($this->valida->getErrors() as $campo => $error) {
                    $arr[] = array($campo, $error);
                }

                $this->mifaces->addErrorsForm( $arr ,true);
                $this->mifaces->run();
                return;
            }

            $datos = $this->initSesion($post);

            if( $datos['error'] ) {

                if( isset($datos['messages']) && count($datos['messages']) > 0 ) {

                    $errors = new Errors();

                    foreach ($datos['messages'] as $key => $err) {

                        $this->mifaces->addToMsg("Error", $errors->getMsgError( key($err) ) );
                    }
                }

                $this->mifaces->addToDataView('status', false);
                $this->mifaces->run();
                return;
            }


            if( ! $datos['status'] ) {

                if( isset($datos['messages']) && count($datos['messages']) > 0 ) {

                    $errors = new Errors();

                    foreach ($datos['messages'] as $key => $err) {

                        switch (key($err)) {

                            case 'jwt_token_not_found':
                            case 'jwt_token_invalid':
                            case 'jwt_token_expired':
                            case 'jwt_user_not_found':
                                    $this->logoutAction();
                                    $this->mifaces->addToMsg("Error", "Sesión expirada o inexistente. Favor iniciar sesión." );
                                    $this->mifaces->addRedir('login');
                                break;

                            default:
                                $this->mifaces->addToMsg("warning", $errors->getMsgError( key($err) ) );

                            break;
                        }
                    }
                }
                //$this->mifaces->addToMsg("Error", "Error al iniciar sesión, Por favor verifique sus datos.");
                $this->mifaces->addToDataView('status', false);
                $this->mifaces->run();
                return;
            }

            /**
             * seteamos las variables de sesión
             * con las cuales comprobamos que el usuario se encuentra logeado
             * también guardamos en una variable de sesión el tipo de usuario
             */

            $this->mifaces->addLog("se ha iniciado sesión con exito");

            $this->session->set("auth-identity", $datos['data']['patient'] );
            $this->session->set("type-user", $post['type'] );
            $this->session->set("accesstoken", $datos['accesstoken']);


            # CSRF-TOKEN 
            $key = $this->security->getTokenKey();
            $token = $this->security->getToken();
            $arrToken['key'] = $key;
            $arrToken['token'] = $token;

            $this->session->set('csrf-token',$arrToken);

            # END CSRF-TOKEN 

            $this->mifaces->addToMsg("Success", "Se ha iniciado sesión con exito.");
            $this->mifaces->addToDataView('status', true);

            $ruta = $this->getRedirectByType($post['type']);
            $this->mifaces->addToDataView('redirectTo', $ruta );
            $this->mifaces->run();
        }

        public function initSesion($post)
        {
            $callApi = new CallAPI();
            $callApi->setHeader("user: {$post['rut']}");

            switch ($post['type']) {
                case 2:
                    $data = array(
                        'rut' => $post['rut'],
                        'birthdate' => $post['birthdate']
                    );

                    break;

                case 3:
                    $data = array(
                        'rut' => $post['rut'],
                        'password' => $post['password']
                    );

                    break;
            }


            $url = $this->config->get("urlApi");

            $result = $callApi->call('POST', $url.'login', $data, true);

            $datos = json_decode($result, true);

            return $datos;
        }

        public function getRedirectByType($type)
        {

            switch ($type) {
                case 2: $view = 'actualizarDatos'; break;
                case 3: $view = 'agendamiento'; break;
            }

            return $view;
        }

        private function getRulesByType($type)
        {

            switch ($type) {

                case 2:
                    $rules = array(
                        'rut'  => "required|string",
                        'type'   => "required|int|min:1",
                        'birthdate' => "required|date"
                    );
                    break;

                case 3:
                    $rules = array(
                        'rut'  => "required|string",
                        'type'   => "required|int|min:1",
                        'password' => "required|string",
                    );
                    break;
            }

            return $rules;
        }

        /**
         * verifyUser
         *
         * Método para verificar estado de usuario para acceso a sistema
         */
        public function verifyUserAction()
        {
            if( !$this->request->isAjax() )
                $this->view->disable();


            $this->valida->validate( $this->request->getPost(), [
                'rut'            => "required|rut"
            ]);

            $this->valida->getErrors();

            if ( $this->valida->failed() ) {

                $arr = array();
                foreach ($this->valida->getErrors() as $campo => $error) {
                    $arr[] = array($campo, $error);
                }
                $this->mifaces->addErrorsForm( $arr ,true);
            }

            $userBsn = new UserBsn();


            $status = $userBsn->getStateUser($this->request->getPost('rut'));

            switch ($status) {
                case 1: $view = 'controllers/login/warning_register'; break;
                #case 2: $view = 'controllers/login/form_birthdate'; break; // registro
                case 2: $view = 'controllers/login/form_enter_password'; break; // registro
                case 3: $view = 'controllers/login/form_password'; break;
                case 4: $view = false;
            }



            $this->mifaces->newFaces();

            if($view){

                $dataView = array(
                    'type' => $status
                );

                $toRend = $this->view->getPartial($view, $dataView);

                $this->mifaces->addToRend('input-login', $toRend);

            } else {

                $this->mifaces->addToMsg('warning','El rut ingresado no está asociado a un paciente.');

            }

            $this->mifaces->run();
        }

        /**
         * verifyBirthday
         */
        public function verifyBirthdayAction()
        {
            if( !$this->request->isAjax() )
                $this->view->disable();


            $this->valida->validate( $this->request->getPost(), [
                'rut'            => "required|rut",
                'birthdate'      => "required|date2"
            ]);

            $this->valida->getErrors();

            if ( $this->valida->failed() ) {

                $arr = array();
                foreach ($this->valida->getErrors() as $campo => $error) {
                    $arr[] = array($campo, $error);
                }
                $this->mifaces->addErrorsForm( $arr ,true);
            }

            $userBsn = new UserBsn();

            $param = array(
                'rut' => $this->request->getPost('rut'),
                'birthdate' => $this->request->getPost('birthdate')
            );

            $status = $userBsn->getStateBirthdayUser($param);

            if( $status != false ) {

                $view = 'controllers/login/form_enter_password';

                $toRend = $this->view->getPartial($view, []);

                $this->mifaces->newFaces();
                $this->mifaces->addToRend('input-login', $toRend);

            } else {

                $this->mifaces->addToMsg("danger","Hubo un error al verificar sus datos.");
            }


            $this->mifaces->run();

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

    }
