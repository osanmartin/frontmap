<?php
    namespace App\helpers;

    use Phalcon\Mvc\User\Plugin;
    use Phalcon\Http\Request;

    /**
     * Up n' Down Files
     *
     * helpers para el manejo de archivos
     *
     * @subpackage   helpers
     * @category     FileStorage
     */
    class UDFiles extends Plugin {

        public $errors;
        private $route_storage;
        private $file_name;
        private $file;

        /**
         * upFile
         *
         * cordina el upload del archivo al servidor
         *
         * @author Sebastián Silva
         */
        public function upFile(Request $request, $rules)
        {
            $this->errors = array();

            if( empty($this->route_storage) ){

                $this->errors[] = 'Debe setear la ruta con el metodo setRoute($ruta)';
                return false;
            }

            if( empty($this->file_name) ){

                $this->errors[] = 'Debe setear el nombre del archivo con el metodo setName($nombre)';
                return false;
            }

            $this->file 	= $request->getUploadedFiles()[0]; // objecto file de phalcon

            $this->setSize();

            if( ! $this->isValid($rules) ){

                return false;
            }


            if ( ! $this->storeFile() ) {
                return false;
            }

            return $this->getFullNameFile();
        }

        /**
         * storeFile
		 *
		 * guardar archivos en el servidor
         *
         * @return boolean
         */
		private function storeFile(){

			try {
				if( $this->file->moveTo( $this->getFullNameFile() ) ) {

					return true;
				} else{

					return false;
				}
			} catch (Exception $e) {

				$this->errors[] = $e->getMessage();
				return false;
			}
		}

        /**
         * seteamos la ruta del archivo
         */
        public function setRoute($ruta)
        {
            // quitamos los espacios qie sobran
            $ruta = trim($ruta);

            // quitamos los / que están de sobra
            $ruta = explode('/', $ruta);
            $ruta = implode('/', $ruta);

            $this->route_storage = $ruta;
        }

        /**
         * seteamos el nombre del archivo
         */
        public function setName($name)
        {
            $name = trim($name);
            $name = str_replace(' ', '_', $name);

            $this->file_name = $name;
        }

        /**
         * seetamos la extención del archivo
         */
        public function setExtencion($ext)
        {
            $this->file_extencion = trim($ext);
        }

        /**
         * retornamos la ruta y nombre completo del archivo
         */
        public function getFullNameFile()
        {
            return $this->route_storage."/".$this->file_name.".".$this->file->getExtension();	// ruta donde se guardara el archivo img/avatar_iduser.jpg
        }

        /**
         * retornamos los errores encontrados en la carga del archivo
         */
        public function getErrors()
        {
            return $this->errors;
        }

        /**
         * retornamos el peso en mb
         */
        public function setSize()
        {
            $size = $this->file->getSize() / 1024 / 1024 ;
            $size = number_format($size, 1, '.', '');
            $this->size = $size;
        }

        /**
         * validamos si el archivo cumple con los requisitos
         */
        private function  isValid($rules) {

            foreach ($rules as $key => $value) {

                $rule = "valid".$key;

                if( ! $this->$rule($value) ){
                    return false;
                }
            }

            return true;
        }

        /**
         *
         * @param integer $sizemax tamaño en mb
         * @return boolean
         */
        private function validsize($sizemax) {

            if( $this->size > $sizemax ) {

                $this->errors[] = "Archivo es de mayor tamaño, el máximo permitido es de {$sizemax}Mb.";
                return false;
            }

            return true;
        }

        /**
         *
         * @param integer $sizemax tamaño en mb
         * @return boolean
         */
        private function validext( $extensions ) {

            $file_extencion = $this->file->getExtension();

            if( in_array($file_extencion, $extensions) ){

                return true;
            }

            $extensions = implode(', ', $extensions);

            $this->errors[] = "El archivo debe tener una extención valida ( {$extensions} )";
            return false;
        }
    }
