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
                #$this->assets->addJs("js/pages/login.js");
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

            $rules = [  'username' => 'required',
                        'password' => 'required'];

            $this->valida->validate($post, $rules);

            $messages = array(
                'username' => "Por favor, ingrese su nombre de usuario.",
                'password' => "Por favor, ingrese la contraseña"
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

            if( (!isset($datos['token']) || (isset($datos['token']) && empty($datos['token'])))) {

                $this->mifaces->addToMsg("warning", 'No se pudo iniciar sesión, revise que su usuario y contraseña esté correcto.');
                $this->mifaces->run();
                return;
            }


            if( isset($datos['description']['code']) && $datos['description']['code'] == 401){

                $this->logoutAction();
                $this->mifaces->addToMsg("Error", "Sesión expirada o inexistente. Favor iniciar sesión." );
                $this->mifaces->addRedir('login');
                $this->mifaces->addToDataView('status', false);
                $this->mifaces->run();
                return;
            }

            /**
             * seteamos las variables de sesión
             * con las cuales comprobamos que el usuario se encuentra logeado
             * también guardamos en una variable de sesión el tipo de usuario
             */

            $this->mifaces->addLog("Se ha iniciado sesión con exito");

            $this->session->set("auth-identity", ['id' => $datos['id']] );
            $this->session->set("accesstoken", $datos['token']);


            # CSRF-TOKEN 
            $key = $this->security->getTokenKey();
            $token = $this->security->getToken();
            $arrToken['key'] = $key;
            $arrToken['token'] = $token;

            $this->session->set('csrf-token',$arrToken);

            # END CSRF-TOKEN 

            $this->mifaces->addToMsg("success", "Se ha iniciado sesión con exito.");
            $this->mifaces->addToDataView('status', true);
            $this->mifaces->addToDataView('redirectTo', $this->config->get('application')['baseUri']);
            $this->mifaces->run();
        }

        public function initSesion($post)
        {
            $callApi = new CallAPI();

            $data['username'] = $post['username'];
            $data['password'] = $post['password'];


            $url = $this->config->get("urlApi");

            $result = $callApi->call('POST', $url.'users/login', $data, true);



            return $result;
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


        public function registerAction(){

            if(!$this->request->isAjax()){

                $this->defaultRedirect();

            }

            $post = $this->request->getPost();

            $rules = [  'username_registro' => 'required|email',
                        'password_registro' => 'required',
                        'password_repeat' => 'required'];

            $this->valida->validate($post, $rules);

            $this->valida->getErrors();

            $password_not_equal = false;

            if(isset($post['password_registro']) && isset($post['password_repeat']) && $post['password_repeat'] != $post['password_registro']){

                $password_not_equal = true;

            }

            if ( $this->valida->failed() || $password_not_equal) {

                $arr = array();

                foreach ($this->valida->getErrors() as $campo => $error) {
                    $arr[] = array($campo, $error);
                }

                if($password_not_equal){

                    $arr[] = ['password_repeat','Contraseñas no coinciden'];

                }

                
                $this->mifaces->addErrorsForm( $arr );
                $this->mifaces->addToJsonView('call_status',['error' => true]);
                $this->mifaces->run();
                exit;
            }


            $callApi = new CallAPI();

            $username = explode('@', $post['username_registro']);
            $username = reset($username);


            $data['username'] = $username;
            $data['email'] = $post['username_registro'];
            $data['public_name'] = $username;
            $data['password'] = $post['password_registro'];

            $result = $callApi->call('POST',$this->config['urlApi'].'users/',$data);

            if(isset($result['description'])){

                $this->mifaces->addToMsg('warning','No fue posible realizar el registro, por favor recargue la página.');

                $this->mifaces->addToJsonView('call_status',['error' => true]);
                $this->mifaces->run();
                exit;

            }

            $this->mifaces->addToJsonView('call_status',['error' => false]);
            $this->mifaces->addToMsg('success','¡Registro exitoso!');
            $this->mifaces->run();

        }

    }
