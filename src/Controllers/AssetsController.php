<?php

	namespace App\Controllers;
	use App\Business\UserBSN;

	class AssetsController extends ControllerBase {

		/**
		 * @var array $error listado de errores
		 */
		public 	$error;

		public function indexAction() {

			$this->view->pick("controllers/users/upload");
		}

		public function uploadAvatarAction() {

			//comprueba si hay archivos por subir
	        if ($this->request->hasFiles() != true) {
	        	#retornar a formulario
	        	return false;
	        }

			$idUser = $this->session->get('auth-identity')['id'];		// Id usuario logeado

			$idUser = 1;

	        $file 	= $this->request->getUploadedFiles()[0];			// objecto file de phalcon
	        $ruta 	= 'img/avatar_'.$idUser.".".$file->getExtension();	// ruta donde se guardara el archivo img/avatar_iduser.jpg

	        # Guardamos el archivo en el servidor
        	if( $this->storeFile($file, $ruta) == false ) {

        		return false;
        	}

        	# Guardamos la ruta en la BD
        	$user = new UserBSN();

        	if( $user->storeAvatar($idUser, $ruta) == false ) {
        		#print_r($user->error);
        		return false;
        	}

        	#echo "Se ha guardado correctamente";
        	return true;
		}

        /**
         * storeFile
		 *
		 * guardar archivos en el servidor
         *
         * @param $file
         * @param $ruta
         * @return boolean
         */
		private function storeFile($file, $ruta){

			try {
				if( $file->moveTo($ruta) ) {

					return true;
				} else{

					return false;
				}
			} catch (Exception $e) {

				$this->error[] = $e->getMessage();
				return false;
			}
		}
	}
