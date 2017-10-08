<?php

    namespace App\Controllers;

    use App\Business\ListBsn;
    use App\Business\UserBsn;
    use App\helpers\Errors;



    /**
     * Controlador Usuario Web
     *
     * Acá se encuentran los procesos relacionados manejo de usuarios web
     *
     * @package      ZMed
     * @subpackage   Controllers
     * @category     User Controller
     * @author       Zenta Group
     * @title        UserController
     */
    class UserController extends ControllerBase {


        /**
         * index
         *
         * Carga información necesaria para empezar a tomar horas
         *
         * @author Sebastián Silva
         * @title Página principal agenda web
         */
        public function indexAction(){


        }

        /**
         * registerUserData
         *
         * Método para registrar usuarios
         * @author Sebastián Silva
         * @title Acceso Registrar Usuario
         */
        public function registerUserDataAction()
        {
            $list = new ListBSN();
            $list_medicalplan = $list->getListMedicalplans();

            $this->view->setVar('medicalplan', $list_medicalplan);

            $this->assets->addJs("js/pages/register.js");
            $this->view->pick("controllers/register/_index");

        }

        /**
         * persistUserData
         *
         * Método para registrar usuarios
         * @author
         * @title Acceso Registrar Usuario
         */
        public function persistUserDataAction()
        {
            $post = $this->request->getPost();


            if($post['password'] != $post['password_reitera']) {

                $this->mifaces->addErrorsForm( [['password_reitera' , 'Las contraseñas deben coincidir']] ,true);
            }

            $rules = array(
                'rut' => 'required|rut',
                'firstname' => 'required|string',
                'lastname' => 'required|string',
                'lastname_mother' => 'required|string',
                'birthdate' => 'required|date2',
                'phone_mobile' => 'required|phone',
                'phone_fixed' => 'required|phone',
                'email' => 'required|email',
                'sexo' => 'required|string',
                'medical_plan_id' => 'required',
                'password' => 'required|string',
                'password_reitera' => 'required|string',
                );

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
                    //echo "Error en el campo '{$campo}': {$error} <br>";
                    $arr[] = array($campo, $error);
                }

                $this->mifaces->addErrorsForm( $arr ,true);

                $this->mifaces->run();
                return;
            }

            $userBsn = new UserBSN();


            $dateTime = new \Datetime($post['birthdate']);
            $post['birthdate'] = $dateTime->format('Y-m-d');

            $result = $userBsn->registerUser($post);

            if( ! $result->status ) {

                $error = new Errors();
                foreach ($result->messages as $key => $value) {
                    
                    $this->mifaces->addToMsg("warning", $error->getMsgError(key($value)));

                }

                $this->mifaces->addToDataView('status', false);
                $this->mifaces->run();
                exit;

            }

            $param = array(
                'rut' => $post['rut'],
                'password' => $post['password']
            );

            $this->setSesion($param);


            $this->mifaces->addToMsg("Success", "Se ha registrado con éxito.");
            $this->mifaces->addToDataView('status', true);

            $session = new SessionController();
            $ruta = $session->getRedirectByType(3);

            $this->mifaces->addToDataView('redirectTo', $ruta );
            $this->mifaces->run();
        }

        /**
         *
         */
        public function updatePasswordAction()
        {
            if( !$this->request->isAjax() )
                $this->view->disable();

            $post = $this->request->getPost();

            $this->mifaces->newFaces();

            if($post['password'] != $post['confirm_password']) {

                $this->mifaces->addErrorsForm( [['confirm_password' , 'Las contraseñas deben coincidir']] ,true);
            }

            $rules = array(
                'rut' => 'required|string',
                'password' => 'required|string',
                'birthdate' => 'required|date2'
            );

            $this->valida->validate($post, $rules);

            $messages = array(
                'rut' =>     "Por favor, ingrese su RUT.",
                'password' => "Por favor, ingrese la contraseña",
                'birthdate' => "Por favor, ingrese la fecha de nacimiento"
            );

            $this->valida->getErrors();

            if ( $this->valida->failed() ) {

                $arr = array();

                foreach ($this->valida->getErrors() as $campo => $error) {
                    //echo "Error en el campo '{$campo}': {$error} <br>";
                    $arr[] = array($campo, $error);
                }

                $this->mifaces->addErrorsForm( $arr ,true);
                $this->mifaces->run();
                return;
            }


            $userBsn = new UserBSN();

            $result = $userBsn->updatePass($post);

            if( ! $result->status ){

                $url_base = $this->config->get("application")["publicUrl"];
                $url_method= "login/";
                $url_redir = $url_base.$url_method;

                $messages = reset($result->messages);

                $code_error = key($messages);

                if($code_error == "missing_parameters"){

                    $msg = "Debes completar todos los campos";
                    $render = false;

                } elseif($code_error == "password_not_match"){

                    $msg = "Las contraseñas ingresadas no coinciden";
                    $render = false;

                } elseif($code_error == "password_exists"){

                    $msg = "Contraseña ya existe, favor iniciar sesión";
                    $render = true;

                } elseif($code_error == "validation_incorrect"){

                    $msg = "La fecha de nacimiento no coincide con la del rut asociado.";
                    $render = false;

                }else {

                    $msg = reset($messages);
                    $render = false;

                }


                if($render){

                    $view = "controllers/login/form_password";
                    $param['type'] = 3;
                    $toRend = $this->view->getPartial($view,$param);
                    $this->mifaces->addToRend('input-login',$toRend);

                }

                $this->mifaces->addToMsg('warning',$msg);
                $this->mifaces->run();

                exit;
            }

            $this->setSesion($post);

            $this->mifaces->addToMsg("Success", "Se ha actualizado su contraseña.");
            $this->mifaces->addToDataView('status', true);

            $session = new SessionController();
            $ruta = $session->getRedirectByType(3);

            $this->mifaces->addToDataView('redirectTo', $ruta );
            $this->mifaces->run();
        }

        public function setSesion($post)
        {
            $session = new SessionController();

            $param = array(
                'rut' => $post['rut'],
                'password' => $post['password'],
                'type' => 3
            );

            $datos = $session->initSesion($param);

            $this->mifaces->addLog("se ha iniciado sesión con exito");

            $this->session->set("auth-identity", $datos['data']['patient'] );
            $this->session->set("type-user", 3 );
            $this->session->set("accesstoken", $datos['accesstoken']);

            # CSRF-TOKEN 
            $key = $this->security->getTokenKey();
            $token = $this->security->getToken();
            $arrToken['key'] = $key;
            $arrToken['token'] = $token;

            $this->session->set('csrf-token',$arrToken);

            # END CSRF-TOKEN 

            return true;
        }

        /**
         * updateUserData
         *
         * Método para actualizar datos usuarios
         * @author Hernán Feliú
         * @title Acceso Actualizar Usuario
         */
        public function updateUserDataAction(){

            $userDetails = $this->session->get('auth-identity');
           
            $param['rut'] = $this->session->get('auth-identity')['rut'];

            $list = new ListBSN();
            $list_medical_plans = $list->getListMedicalplans();

            $userObj = new UserBSN();
            $vinculatedUsers = $userObj->getVinculatedUsers($param);

            $username = $this->session->get('auth-identity')['firstname'];
            $this->view->setVar('username',$username);

            $this->view->setVar('medical_plans', $list_medical_plans);
            $this->view->setVar('userDetails', $userDetails);
            $this->view->setVar('vinculatedUsers', $vinculatedUsers);

            $this->assets->addJs("js/pages/update-data-user.js");
            $this->view->pick("controllers/update_user_data/main");

        }

        /**
         * persistUpdateUserData
         *
         * Método para persistir datos actualizados de usuario
         * @author Hernán Feliú
         * @title Acceso Persistir Datos Actualizados de Usuario
         */
        public function persistUpdateUserDataAction(){

            if ($this->request->isAjax()) {

                $post = $this->request->getPost();
                  
                if(isset($post['password']) && isset($post['confirm_password']) && $post['password'] != $post['confirm_password']) {
                     
                    $this->mifaces->addErrorsForm( [['confirm_password' , 'Las contraseñas deben coincidir']] ,true);
                    $this->mifaces->run();
                    exit();

                }

                $rules = array(
                    'rut' => 'required|rut',
                    'birthdate' => 'required|date2',
                    'phone_mobile' => 'required|phone',
                    'phone_fixed' => 'required|phone',
                    'email' => 'required|email',
                    'medical_plan' => 'required|string'
                    );

                $this->valida->validate($post, $rules);

                $errors = $this->valida->getErrors();
                $arr = array();

                if ($this->valida->failed()) {

                    foreach ($errors as $key => $value) {
                        $arr[] = [$key, $value];
                    }

                    $this->mifaces->addErrorsForm($arr);

                }else{

                    $post['medical_plan_id'] = $post['medical_plan'];

                    $dateTime = new \Datetime($post['birthdate']);
                    $post['birthdate'] = $dateTime->format('Y-m-d');

                    $userObj = new UserBSN();
                    $response =  $userObj->updateUser($post);
                   
                    if($response->error){

                        foreach ($response->messages as $key => $value) {

                            $this->mifaces->addToMsg("danger", reset($value));

                        }

                    }

                    if($response->status){

                        $view = "controllers/update_user_data/form_update";

                        $userDetails = array();

                        foreach ($response->data->patient as $key => $value) {

                            $userDetails[$key] = $value;

                        }

                        if(isset($userDetails['medical_plan'])){

                            $medicalPlan = $userDetails['medical_plan'];

                            $userDetails['medical_plan'] = array('id' => $medicalPlan->id,
                                                                 'name'=> $medicalPlan->name);

                        }

                        if(isset($userDetails['pending_changes'])){

                            $pendings = $userDetails['pending_changes'];

                            $temp = array();

                            foreach ($pendings as $key => $value) {

                               $temp[$key] = $value;

                            }
                            
                            $userDetails['pending_changes'] = $temp;
                            $this->session->set('auth-identity', $userDetails);

                        }

                        $list = new ListBSN();
                        $response_medical_plans = $list->getListMedicalplans();

                        if(count($response_medical_plans) == 0){

                            $this->mifaces->addToMsg("danger", "No se han podido cargar las previsiones. Refresque la página.");

                        }else{

                             $dataView['medical_plans'] = $response_medical_plans;

                        }

                        $dataView['userDetails'] = $userDetails;

                        $toRend = $this->view->getPartial($view, $dataView);

                        $this->mifaces->addToRend("update_user_data_render", $toRend);

                        $this->mifaces->addToMsg("success","Sus datos han sido actualizados correctamente.");

                    }else{

                        foreach ($response->messages as $key => $value) {

                            $this->mifaces->addToMsg("danger", reset($value));

                        }

                    }

                }

                $this->mifaces->run();

            }else{
                    #deshabilitamos la vista para ahorrar procesamiento
                $this->view->disable();
            }
        }

        /**
         * linkUser
         *
         * Método para vincular usuarios
         * @author Hernán Feliú
         * @title Acceso Vinculación de Usuarios
         */
        public function linkUserAction(){

            if($this->request->isAjax()){

                $post = $this->request->getPost();
                
                if(isset($post['link_password']) && isset($post['link_password_reitera']) && $post['link_password'] != $post['link_password_reitera']) {
                     
                    $this->mifaces->addErrorsForm( [['link_password_reitera' , 'Las contraseñas deben coincidir']] ,true);
                    $this->mifaces->run();
                    exit();

                }

                $rules = array(
                    'link_rut' => 'required|string',
                    'link_firstname' => 'required|string',
                    'link_lastname' => 'required|string',
                    'link_lastname_mother' => 'required|string',
                    'link_birthdate' => 'required|date2',
                    'link_phone_mobile' => 'required|phone',
                    'link_phone_fixed' => 'required|phone',
                    'link_email' => 'required|email',
                    'link_sexo' => 'required',
                    'link_medical_plan_id' => 'required',
                    'link_rutchild' => 'required'
                    );

                $this->valida->validate($post, $rules);

                $errors = $this->valida->getErrors();

                if ( $this->valida->failed() ) {

                    $arr = array();

                    foreach($errors as $key => $value){

                        $arr[] = [$key, $value];

                    }

                    $this->mifaces->addErrorsForm($arr);
                   
                }else{

                    $userBsn = new UserBSN();
                    
                    $link_data = array();

                    foreach($post as $key => $value){

                        $index = explode('link_', $key);
                        $link_data[$index[1]] = $value;

                    }

                    $dateTime = new \Datetime($link_data['birthdate']);

                    $link_data['birthdate'] = $dateTime->format('Y-m-d');
                    
                    $response = $userBsn->createLinkUser($link_data);
                    
                    if($response->error){

                        foreach ($response->messages as $key => $value) {

                            $this->mifaces->addToMsg("danger", reset($value));

                        }

                    }

                    if($response->status){

                        $param['rut'] = $link_data['rut'];
                        $this->renderLinkUserTable($param);

                        $this->mifaces->addToMsg("success","Se ha vinculado paciente exitosamente.");
                        $this->mifaces->addToJsonView("link_success",true);

                    }else{

                        foreach ($response->messages as $key => $value) {

                            $this->mifaces->addToJsonView("link_success",false);

                            if(key($value) == "rut_exists"){

                                $this->mifaces->addToJsonView('rut_match',true);

                            }else{

                                $this->mifaces->addToMsg("danger", reset($value));

                            }

                        }
                    }
                    
                }

                $this->mifaces->run();

            }else{
                #deshabilitamos la vista para ahorrar procesamiento
                $this->view->disable();
            }

        }

        /**
         * unlinkUser
         *
         * Método para desvincular usuarios
         * @author osanmartin
         * @title Acceso Desvinculación de Usuarios
         */
        public function unlinkUserAction(){

            if($this->request->isAjax()){

                $post = $this->request->getPost();
                $this->mifaces->newFaces();

                if(isset($post['rutchild'])){

                        $param['rut'] = $this->session->get('auth-identity')['rut'];
                        $param['rutchild'] = $post['rutchild'];

                        $userBsn = new UserBSN();
                        $response = $userBsn->unlink($param);

                        if(!$response->error){

                            if($response->status){

                                $this->mifaces->addToMsg("sucess",reset($response->messages));
                                $this->renderLinkUserTable($param);

                            } else {

                                foreach ($response->messages as $key => $val) {
                                    $this->mifaces->addToMsg("warning",reset($val));
                                }

                            }


                        } else {

                            $this->mifaces->addToMsg("warning","Error inesperado, vuelva a intentarlo");

                        }


                } else {

                    $this->mifaces->addToMsg("warning","Error inesperado, vuelva a intentarlo");

                }

                $this->mifaces->run();


            } else {


                $this->view->disable();

            }

        }

        /**
        * renderLinkUserTable
        *
        * Método para renderizar tabla de pacientes vinculados
        * @author Hernán Feliú
        * @title Acceso Renderizar Tabla Pacientes Vinculados
        * @param $param['rut'] 
        */
        private function renderLinkUserTable($param){

            if(!isset($param['rut'])){

                return false;

            }else{

                $view = "controllers/update_user_data/table_vinculated_user";

                $userObj = new UserBSN();
                $vinculatedUsers = $userObj->getVinculatedUsers($param);

                $dataView['vinculatedUsers'] = $vinculatedUsers;
                $toRend = $this->view->getPartial($view, $dataView);
                $this->mifaces->addToRend("table_vinculated_user_render", $toRend);

            }

        }

        /**
         * updateLinkUserData
         *
         * Método para actualizar datos usuario vinculado
         * @author Hernán Feliú
         * @title Acceso Actualizar Usuario Vinculado
         */
        public function updateUserLinkDataAction(){

            if($this->request->isAjax()){

                $post = $this->request->getPost();

                if(isset($post['rut'])){

                    $param['rut'] = $post['rut'];

                    $userObj = new UserBSN();
                    $response = $userObj->getUser($param);

                    if($response->error){

                        foreach ($response->messages as $key => $value) {

                            $this->mifaces->addToMsg("danger", reset($value));

                        }

                    }

                    if($response->status){

                        $view = "controllers/update_user_data/form_link_edit";

                        $userData = $response->data->patient;
                        $list = new ListBSN();
                        
                        $response_medical_plans = $list->getListMedicalplans();

                        if(count($response_medical_plans) == 0){

                            $this->mifaces->addToMsg("danger", "No se han podido cargar las previsiones. Refresque la página.");

                        }else{

                             $dataView['medical_plans'] = $response_medical_plans;

                        }

                        $dataView['userData'] = $userData;
                        $dataView['edit'] = true;
                        $toRend = $this->view->getPartial($view, $dataView);

                        $this->mifaces->addToRend("vinculated_patient_form_render", $toRend);
                        $this->mifaces->addToJsonView("user_success", true);

                    }else{

                        $this->mifaces->addToJsonView("user_success", false);

                        foreach ($response->messages as $key => $value) {

                            $this->mifaces->addToMsg("danger", reset($value));

                        }

                    }
        
                }else{

                    $this->mifaces->addToMsg("danger", "Error inesperado. Inténtelo nuevamente.");

                }

                $this->mifaces->run();

            }else{
                #deshabilitamos la vista para ahorrar procesamiento
                $this->view->disable();
            }

        }

        /**
         * persistUpdateLinkUserData
         *
         * Método para persistir datos actualizados de usuario Vinculado
         * @author Hernán Feliú
         * @title Acceso Persistir Datos Actualizados de Usuario Vinculado
         */
        public function persistUpdateLinkUserDataAction(){

            if ($this->request->isAjax()) {

                $post = $this->request->getPost();
                
                if(isset($post['link_edit_password']) && isset($post['link_edit_confirm_password']) && $post['link_edit_password'] != $post['link_edit_confirm_password']) {
                     
                    $this->mifaces->addErrorsForm( [['link_edit_confirm_password' , 'Las contraseñas deben coincidir']] ,true);
                    $this->mifaces->run();
                    exit();

                }

                $rules = array(
                    'link_edit_birthdate' => 'required|date2',
                    'link_edit_phone_mobile' => 'required|string',
                    'link_edit_email' => 'required|email',
                    'link_edit_medical_plan_id' => 'required',
                    );

                $this->valida->validate($post, $rules);

                $errors = $this->valida->getErrors();

                if ( $this->valida->failed() ) {

                    $arr = array();

                    foreach($errors as $key => $value){

                        $arr[] = [$key, $value];

                    }

                    $this->mifaces->addErrorsForm($arr);
                   
                }else{

                    $userBsn = new UserBSN();
                    
                    $link_data = array();

                    foreach($post as $key => $value){

                        $index = explode('link_edit_', $key);
                        $link_data[$index[1]] = $value;

                    }

                    $dateTime = new \Datetime($link_data['birthdate']);

                    $link_data['birthdate'] = $dateTime->format('Y-m-d');
                     
                    $response =  $userBsn->updateUser($link_data);
                    
                    if($response->error){

                        foreach ($response->messages as $key => $value) {

                            $this->mifaces->addToMsg("danger", reset($value));

                        }

                    }

                    if($response->status){
                        
                        $param['rut'] = $link_data['user_rut'];
                        $this->renderLinkUserTable($param);

                        $this->mifaces->addToMsg("success","Datos de paciente actualizados exitosamente.");
                        $this->mifaces->addToJsonView("update_success",true);

                    }else{

                        $this->mifaces->addToJsonView("update_success",false);

                        foreach ($response->messages as $key => $value) {

                            $this->mifaces->addToMsg("danger", reset($value));

                        }

                    }

                }

                $this->mifaces->run();

            }else{
                    #deshabilitamos la vista para ahorrar procesamiento
                $this->view->disable();
            }
        }

    }
