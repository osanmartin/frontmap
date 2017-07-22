<?php
	namespace App\AccesoAcl;

	use Phalcon\Mvc\User\Component;
	use Phalcon\Mvc\Dispatcher;

	/**
	 * Libreria Control de Accesos
	 *
	 * En esta libreria podemos encontrar los metodos que permiten al sistema
	 * identificar las funcionalidades permitidas a un usuario u otro.
	 *
	 * @subpackage   Library
	 * @category     AccesoAcl
	 */
	class AccesoAcl extends Component{

		private static $disp;

        /**
         * Consutla si tiene permiso para ingresar
         *
         * Todos los datos necesarios para realizar la consulta los obtiene
         * de la sesión. Este método es para ser utilizado en Backend
         *
         * @return bool
         */
        public static function tieneAcceso()
		{
			# instanciamos para poder obtener los datos
			$acceso 		= new AccesoAcl();
			$rol 			= $acceso->getRol();
			$action 		= $acceso->getAction();
			$controlador 	= $acceso->getControlador();

			if (!isset($rol) or empty(trim($rol))) {

				//$this->flash->error("Error con sesión, por favor reingrese");
				$acceso->killSession();
				
			}

			if($controlador == 'acceso' AND $action == 'denegado'){
				return true;
			}

			if($rol ==1 ){ #SuperUser
				return true;
			}

			$permiso = \App\Models\Permissions::find(" role_id = {$rol} AND name = '{$controlador}/{$action}' ")->toArray();

			if(count($permiso) > 0){
				return true;
			}

			# si no existe la variable, por defecto no tendrá acceso
			return false;
		}


		/**
         * Tiene Permiso
         *
         * Es capaz de indicar si el usuario tiene o no acceso a la 
         * funcionalidad consultada. 
         * Los datos para este metodo se ingresan por parametros
         *
         *
         * @param string $action
         * @param string $controlador
         *
         * @return bool True si puede acceder, False si no
         */
		public static function tienePermiso($action, $controlador = null)
		{

			# instanciamos para poder obtener los datos
			$acceso 	= new AccesoAcl();
			$rol 		= $acceso->getRol();

			if (!isset($rol) or empty(trim($rol))) {

				//$this->flash->error("Error con sesión, por favor reingrese");
				$acceso->killSession();
			}

			if($rol ==1 ){ #SuperUser
				return true;
			}

			#obtenemos el action o metodo al que se requiere acceder
			$action = strtolower($action);

			# seteamos el controlador enviado como parametro
			if(isset($controlador)){
				$controlador = strtolower($controlador);
			}else{
				$controlador 	= $acceso->getControlador();
			}

			$permiso = \App\Models\Permissions::find(" role_id = {$rol} AND name = '{$controlador}/{$action}' ")->toArray();

			if(count($permiso) > 0){
				return true;
			}

			# si no existe la variable, por defecto no tendrá acceso
			return false;
		}

	   /**
		* Metodo Utilitario
		*
		* Obtiene Rol de usuario logeado
		* @return string
		*/
		private function getRol()
		{
			return $this->auth->getIdentity()['rol'];
		}

	   /**
		* Metodo Utilitario
		*
		* Obtiene un Controlador
		* @return string
		*/
		private function getControlador()

		{
			return strtolower($this->dispatcher->getControllerName());
		}

	   /**
		* Metodo Utilitario
		*
		* Obtiene un Action
		* @return string
		*/
		private function getAction()
		{
			return strtolower($this->dispatcher->getActionName());
		}


		/**
		 * [killSession description]
		 * @return [type] [description]
		 */
		private function killSession() {
			$this->auth->remove();

			if($this->session)
        		$this->session->destroy();

        	if($this->request->isAjax()){

        		return;

        	}
        	else{

	        	$this->flash->error("Usuario con perfil incompleto, favor indique problema y reintente más tarde.");

	        	$this->dispatcher->forward(array(
	                "controller" => "session",
	                "action" => "login"
	            ));
	            
            }

            die();
		}

	}
