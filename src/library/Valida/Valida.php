<?php
	namespace App\library\Valida;

	use Phalcon\Mvc\User\Component;
	use Phalcon\Mvc\Dispatcher;


	/**
	 * Validacion
	 *
	 * En esta clase podemos encontrar funciones que permiten realizar distintos tipos
	 * de validaciones
	 *
	 *
	 * @subpackage   Library
	 * @category     Valida
	 */
	class Valida extends Component{

		public 	$post;
		private $failed = false;
		private $list_errors;
		private $generic = true;

		private $rules = array(	'required',
                                'requiredorzero',
								'int',
								'string',
								'max',
								'min',
								'email',
								'date',
								'date2',
								'rut',
                                'time',
                                'numeric',
                                'numericorvoid',
                                'intorvoid',
                                'depend',
                                'choose',
                                'password',
                                'mindatetoday',
                                'phone'
								);


		/**
		* Metodo principal que recibe datos a validar y reglas de validación
		* 
		*
		* @param array $post Conjunto de datos a Validar
		* @param array $reglas Conjunto de validaciones a aplicar
		*
		* @return bool false si falla alguna validación
		*/
		public function validate($post, $reglas, $generic = true) {

			// reset variables
			$this->failed 		= false;
			$this->generic = $generic;
			$this->list_errors 	= null;


			# verificamos que la variable $arg sea un array
			if( is_array($post) ) {

				$this->setPost($post);

				# recorremos el array
				if($reglas){

					foreach ($reglas as $campo => $regla) {
						
						$this->isValid($campo, $regla);

					}
				}

			} else {
				# en caso de no ser un array retornamos un false
                return false;
				$this->list_errors[] = "El primer argumento debe ser un array";
			}
		}

		/**
		* Setea var post
		* @param $post 
		*/
		private function setPost($post){
			$this->post = $post;
		}

		/**
		* Verifica si una validacion para la regla ingresada
		* @param array $campo
		* @param array $reglas
		*
		* @return bool 
		*/
		private function isValid($campo, $reglas)
		{
			$reglas = explode('|', $reglas);

			// dejamos en una variable si es entero o no
			if(in_array('int', $reglas)){
				$entero = true;
			} else {
				$entero = false;
			}

			foreach ($reglas as $regla) {
				# buscamos el : para saber si es max o min
				$pos = strpos($regla, ':');


				if($pos === false){# si no se encuentra el :

					if(in_array($regla, $this->rules)) {

						$regla = "is_".$regla;
						if(!$this->$regla($campo)){
							$this->failed = true;
						}

					} else {
						$this->list_errors[] = "El tipo de regla no existe.";
					}
				
				} else {
					# separamos la regla del valor
					$regla = explode(':', $regla);

                    if ($regla[0] == 'depend') {
                        $val = null;
                        if ( sizeof($regla) > 2) {
                            $val = $regla[2];
                        }
                        if (!$this->is_depending($regla[1], $val)) {
                            return;
                        }

                    } else {

                        if (in_array($regla[0], $this->rules)) {

                            $r = "is_" . $regla[0];
                            if (!$this->$r($campo, $regla[1], $entero)) {
                                $this->failed = true;
                            }

                        } else {
                            $this->list_errors[] = "El tipo de regla no existe.";
                        }
                    }
				}
			}

		}

		/**
		* Verifica si un campo es requerido
		*
		* Si no, tambien ingresa el dato a la lista de errores
		*
		* @param array $campo
		*
		* @return bool 
		*/
		private function is_required($campo) {

			if($this->generic == true){

				if(isset($this->post[$campo])){
					if( !is_array($this->post[$campo]) && !is_numeric($this->post[$campo]) ) {
						$campoTrim = trim($this->post[$campo]);
						if( empty($campoTrim) ){
							$this->list_errors[$campo] = "Campo requerido.";
							return false;
						}
					}

				} else {
					$this->list_errors[$campo] = "Campo requerido.";
					return false;
				}
			}else{

				if(isset($this->post[$campo])){
					if( !is_array($this->post[$campo]) && !is_numeric($this->post[$campo])) {
						 $campoTrim = trim($this->post[$campo]);
						if( empty($campoTrim) ){
							$this->list_errors[$campo] = "El campo %".$campo."% es requerido.";
							return false;
						}
					}

				} else {
					$this->list_errors[$campo] = "El campo %".$campo."% es requerido.";
					return false;
				}

			}

			return true;
		}


        /**
         * Verifica si un campo es requerido
         *
         * Si no, tambien ingresa el dato a la lista de errores
         *
         * @param array $campo
         *
         * @return bool
         */
        private function is_requiredorzero($campo) {

            if($this->generic == true){

                if(isset($this->post[$campo])){

                    if(empty($this->post[$campo])){
                        if($this->post[$campo]!=='0') {
                            $this->list_errors[$campo] = "Campo requerido.";
                            return false;
                        }
                    }

                } else {
                    $this->list_errors[$campo] = "Campo requerido.";
                    return false;
                }

            }else{

                if(isset($this->post[$campo])){

                    if(empty($this->post[$campo])){
                        if($this->post[$campo]!=='0') {
                            $this->list_errors[$campo] = "El campo %" . $campo . "% es requerido.";
                            return false;
                        }
                    }

                } else {
                    $this->list_errors[$campo] = "El campo %".$campo."% es requerido.";
                    return false;
                }

            }

            return true;
        }

		/**
		* Verifica si un campo es string
		*
		* Si no, tambien ingresa el dato a la lista de errores
		*
		* @param array $campo
		*
		* @return bool 
		*/
		private function is_string($campo) {

			if($this->generic == true){

				if(!is_string($this->post[$campo])) {

					if( !isset($this->list_errors[$campo]) ) {

						$this->list_errors[$campo] = "Este campo debe ser un texto.";
					}
				
					return false;
			    }

			}else{

				if(!is_string($this->post[$campo])) {

					if( !isset($this->list_errors[$campo]) ) {

						$this->list_errors[$campo] = "El campo %".$campo."% debe ser un texto.";
					}
					

					return false;
				}

			}

			return true;
		}

		/**
		* Verifica si un campo es un numero entero
		*
		* Si no, tambien ingresa el dato a la lista de errores
		*
		* @param array $campo
		*
		* @return bool 
		*/
		private function is_int($campo) {

			if($this->generic == true){

				if(isset($this->post[$campo])){

					if(!is_int( (int)$this->post[$campo] )) {

						if( !isset($this->list_errors[$campo]) ) {

							$this->list_errors[$campo] = "Este campo debe ser un entero.";
						}

						return false;
					}

				}

			}else{

				if(isset($this->post[$campo])){

					if(!is_int( (int)$this->post[$campo] )) {

						if( !isset($this->list_errors[$campo]) ) {

							$this->list_errors[$campo] = "El campo %".$campo."% debe ser un entero.";
						}

						return false;
					}

				}

			}
		    
			return true;
		}

		/**
		* Verifica si un campo es numero
		*
		* Si no, tambien ingresa el dato a la lista de errores
		*
		* @param array $campo
		*
		* @return bool 
		*/
		private function is_numeric($campo) {

			if($this->generic == true){

				if(isset($this->post[$campo])){

					if(!is_numeric( $this->post[$campo] )) {

						if( !isset($this->list_errors[$campo]) ) {

							$this->list_errors[$campo] = "Este campo debe ser un número.";
						}

						return false;
					}

				}

			}else{

				if(isset($this->post[$campo])){

					if(!is_numeric( $this->post[$campo] )) {

						if( !isset($this->list_errors[$campo]) ) {

							$this->list_errors[$campo] = "El campo %".$campo."% debe ser un número.";
						}

						return false;
					}

				}

			}

			
			
			return true;
		}

        /**
         * verifica si un campo es un número o viene vacio (numeric not required)
         *
         * @param $campo
         * @return bool
         */
		private function is_numericorvoid($campo) {

            if (!isset($this->post[$campo]) or $this->post[$campo] == '') {

                return true;

            } else {

                return $this->is_numeric($campo);

            }

		}

        /**
         * verifica si un campo es un entero o viene vacio (integer not required)
         *
         * @param $campo
         * @return bool
         */
        private function  is_intorvoid($campo) {

            if (!isset($this->post[$campo]) or $this->post[$campo] == '') {

                return true;

            } else {

                if($this->generic == true){

                    if(isset($this->post[$campo])){

                        if(strval($this->post[$campo]) != strval(intval($this->post[$campo]))) {

                            if( !isset($this->list_errors[$campo]) ) {

                                $this->list_errors[$campo] = "Este campo debe ser un entero.";
                            }

                            return false;
                        }

                    }

                }else{

                    if(isset($this->post[$campo])){

                        if(strval($this->post[$campo]) != strval(intval($this->post[$campo]))) {

                            if( !isset($this->list_errors[$campo]) ) {

                                $this->list_errors[$campo] = "El campo %".$campo."% debe ser un entero.";
                            }

                            return false;
                        }

                    }

                }

                return $this->is_int($campo);

            }

        }

		/**
		* Verifica si un campo es email
		*
		* Si no, tambien ingresa el dato a la lista de errores
		*
		* @param array $campo
		*
		* @return bool 
		*/
		private function is_email($campo) {

			if($this->generic == true){

				if( isset( $this->post[$campo]) && !empty($this->post[$campo]) ){

						if (!filter_var($this->post[$campo], FILTER_VALIDATE_EMAIL)) {

							if( !isset($this->list_errors[$campo]) ) {
						    	
						    	$this->list_errors[$campo] = "Debe ingresar un email válido.";
						    }

							return false;
						}

				}

			}else{

				if( isset( $this->post[$campo]) && !empty($this->post[$campo]) ){

					if (!filter_var($this->post[$campo], FILTER_VALIDATE_EMAIL)) {

						if( !isset($this->list_errors[$campo]) ) {
					    	
					    	$this->list_errors[$campo] = "El campo %".$campo."% debe contener un email válido.";
					    }

						return false;
					}

				}

			}
			
			return true;
		}

		/**
		* Verifica si un campo tiene un máximo de caracteres
		*
		* Si no, tambien ingresa el dato a la lista de errores
		*
		* @param array $campo
		*
		* @return bool 
		*/
		private function is_max($campo, $num, $tipo) {

			if($this->generic == true){

				if(isset($this->post[$campo])) {

					if($tipo) {

						if($this->post[$campo] > $num) {

							if( !isset($this->list_errors[$campo]) ) {

								$this->list_errors[$campo] = "Este valor debe ser menor o igual a {$num}.";
							}

							return false;
						}

					} else {

						if(mb_strlen($this->post[$campo]) > $num) {

							if( !isset($this->list_errors[$campo]) ) {
								
								$this->list_errors[$campo] = "El número de caracteres de este campo debe ser menor o igual a {$num}.";
							}

							return false;
						}
					}
				}

			}else{

				if(isset($this->post[$campo])) {

					if($tipo) {

						if($this->post[$campo] > $num) {

							if( !isset($this->list_errors[$campo]) ) {

								$this->list_errors[$campo] = "El campo %".$campo."% debe tener un valor menor o igual a {$num}.";
							}

							return false;
						}

					} else {

						if(mb_strlen($this->post[$campo]) > $num) {

							if( !isset($this->list_errors[$campo]) ) {
								
								$this->list_errors[$campo] = "El campo %".$campo."% debe poseer un número de carácteres menor o igual a {$num}.";
							}

							return false;
						}
					}
				}

			}

			return true;

		}

		/**
		* Verifica si un campo tiene un mínimo de caracteres
		*
		* Si no, tambien ingresa el dato a la lista de errores
		*
		* @param array $campo
		*
		* @return bool 
		*/
		private function is_min($campo, $num, $tipo) {

			if($this->generic == true){

				if(isset($this->post[$campo])){

					if($tipo){


						if($this->post[$campo] < $num) {

							if( !isset($this->list_errors[$campo]) ) {
								
								$this->list_errors[$campo] = "Este valor debe ser mayor o igual a {$num}.";
							}

							return false;
						}
						
					} else {

						if(mb_strlen($this->post[$campo]) < $num) {

							if( !isset($this->list_errors[$campo]) ) {
								
								$this->list_errors[$campo] = "El número de caracteres de este campo debe ser mayor o igual a {$num}.";
							}

							return false;
						}
					}
				}

			}else{

				if(isset($this->post[$campo])){

					if($tipo){


						if($this->post[$campo] < $num) {

							if( !isset($this->list_errors[$campo]) ) {
								
								$this->list_errors[$campo] = "El campo %".$campo."% debe tener un valor mayor o igual a {$num}.";
							}

							return false;
						}
						
					} else {

						if(mb_strlen($this->post[$campo]) < $num) {

							if( !isset($this->list_errors[$campo]) ) {
								
								$this->list_errors[$campo] = "El campo %".$campo."% debe poseer un número de carácteres mayor o igual a {$num}.";
							}

							return false;
						}
					}
				}

			}
			
			return true;
		}

		/**
		* Verifica si un campo es una fecha
		*
		* Si no, tambien ingresa el dato a la lista de errores
		*
		* @param array $campo
		*
		* @return bool 
		*/
		private function is_date($campo) {

			if($this->generic == true){

				if(isset($this->post[$campo]) && !empty($this->post[$campo])){

					if(!$this->validateDate($this->post[$campo])) {

						if( !isset($this->list_errors[$campo]) ) {
								
							$this->list_errors[$campo] = "Debe ingresar una fecha válida.";
						}

						return false;
					}
				}

			}else{

				if(isset($this->post[$campo]) && !empty($this->post[$campo])){

					if(!$this->validateDate($this->post[$campo])) {

						if( !isset($this->list_errors[$campo]) ) {
								
							$this->list_errors[$campo] = "El campo %".$campo."% debe poseer una fecha válida.";
						}

						return false;
					}
				}

			}

			return true;
		}

		/**
		* Verifica si un campo es una fecha
		*
		* Si no, tambien ingresa el dato a la lista de errores
		*
		* @param array $campo
		*
		* @return bool 
		*/
		private function is_date2($campo) {

			if($this->generic == true){

				if(isset($this->post[$campo]) && !empty($this->post[$campo])){

					if(!$this->validateDate2($this->post[$campo])) {

						if( !isset($this->list_errors[$campo]) ) {
								
							$this->list_errors[$campo] = "Debe ingresar una fecha válida.";
						}

						return false;
					}
				}

			}else{

				if(isset($this->post[$campo]) && !empty($this->post[$campo])){

					if(!$this->validateDate2($this->post[$campo])) {

						if( !isset($this->list_errors[$campo]) ) {
								
							$this->list_errors[$campo] = "El campo %".$campo."% debe poseer una fecha válida.";
						}

						return false;
					}
				}

			}

			return true;
		}

		private function is_mindatetoday($campo) {

			if($this->generic == true){

				if(isset($this->post[$campo]) && !empty($this->post[$campo])){

					if(!$this->mindate($this->post[$campo])) {

						if( !isset($this->list_errors[$campo]) ) {
								
							$this->list_errors[$campo] = "Ingrese una fecha igual o mayor a la de hoy.";
						}

						return false;
					}
				}

			}else{

				if(isset($this->post[$campo]) && !empty($this->post[$campo])){

					if(!$this->mindate($this->post[$campo])) {

						if( !isset($this->list_errors[$campo]) ) {
								
							$this->list_errors[$campo] = "El campo %".$campo."% debe poseer una fecha igual o mayor a la de hoy.";
						}

						return false;
					}
				}

			}

			return true;
		}

		
		/**
		* Valida el formato de una fecha
		*
		* Formato por defecto Y-m-d
		*
		* @param date $date
		* @param string $format
		*
		* @return bool 
		*/
		private function validateDate($date, $format = 'Y-m-d') {

		    $d = \DateTime::createFromFormat($format, $date);
		    return $d && $d->format($format) == $date;
		}

		/**
		* Valida el formato de una fecha
		*
		* Formato por defecto d-m-Y
		*
		* @param date2 $date
		* @param string $format
		*
		* @return bool 
		*/
		private function validateDate2($date, $format = 'd-m-Y') {

		    $d = \DateTime::createFromFormat($format, $date);
		    return $d && $d->format($format) == $date;
		}

		/**
		* Establece la fecha de hoy como fecha minima
		*
		* Formato por defecto d-m-Y
		*
		* @param date $date
		*
		* @return bool 
		*/
		private function mindate($date) {

		    $now  = new \DateTime('now');
		    $now  = $now->format('Y-m-d');

		    $turn = new \DateTime($date);
		    $turn = $turn->format('Y-m-d');

		    if ( $turn >= $now) {
		        return true;
		    }else{
		        return false;
		    }

		}


		/**
		* Verifica si un campo es una hora
		*
		* Si no, tambien ingresa el dato a la lista de errores
		*
		* @param array $campo
		*
		* @return bool 
		*/
		private function is_time($campo){

			if($this->generic == true){

				if(isset($this->post[$campo]) && !empty($this->post[$campo])){

				    if(!$this->validateTime($this->post[$campo])) {

				        if( !isset($this->list_errors[$campo]) ) {

				            $this->list_errors[$campo] = "Debe ingresar una hora válida.";
				        }

				        return false;
				    }
				}

			}else{

				if(isset($this->post[$campo]) && !empty($this->post[$campo])){

				    if(!$this->validateTime($this->post[$campo])) {

				        if( !isset($this->list_errors[$campo]) ) {

				            $this->list_errors[$campo] = "El campo %".$campo."% debe poseer una hora válida.";
				        }

				        return false;
				    }
				}

			}

            return true;
        }

        /**
		* Valida el formato de una hora
		*
		*
		* @param time $mytime
		* 
		*
		* @return bool 
		*/
        private function validateTime($myTime) {
            $time = preg_match('#^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$#', $myTime);

            if ( $time == 1 )
            {
                return true;
            }
            else
            {
                return false;
            }
        }


        /**
		* Utiliza la funcion ppara validar Rut
		*
		*
		* @param string $campo
		*
		* @return bool 
		*/
		private function is_rut($campo) {

			if($this->generic == true){

				if(isset($this->post[$campo]) && !empty($this->post[$campo])){

					if(!$this->valida_rut($this->post[$campo])) {

						if( !isset($this->list_errors[$campo]) ) {
								
							$this->list_errors[$campo] = "Debe ingresar un rut válido.";
						}

						return false;
					}
				}

			}else{

				if(isset($this->post[$campo]) && !empty($this->post[$campo])){

					if(!$this->valida_rut($this->post[$campo])) {

						if( !isset($this->list_errors[$campo]) ) {
								
							$this->list_errors[$campo] = "El campo %".$campo."% debe poseer un rut válido.";
						}

						return false;
					}
				}

			}

			return true;
		}

		/**
		* Valida el formato de una Rut (Chile)
		*
		*
		* @param string $rut
		*
		* @return bool 
		*/
		private function valida_rut($rut)
		{
		    if (!preg_match("/^[0-9.]+[-]?+[0-9kK]{1}/", $rut)) {
		        return false;
		    }

		    $rut 	= preg_replace('/[\.\-]/i', '', $rut);
		    $dv 	= substr($rut, -1);
		    $numero = substr($rut, 0, strlen($rut) - 1);
		    $i 		= 2;
		    $suma 	= 0;
		    
		    foreach (array_reverse(str_split($numero)) as $v) {
		        if ($i == 8)
		            $i = 2;
		        $suma += $v * $i;
		        ++$i;
		    }
		    
		    $dvr = 11 - ($suma % 11);
		    
		    if ($dvr == 11)
		        $dvr = 0;
		    if ($dvr == 10)
		        $dvr = 'K';
		    if ($dvr == strtoupper($dv))
		        return true;
		    else
		        return false;
		}

		private function validaPassword($password, $campo){

			$uppercase = preg_match('@[A-Z]@', $password);
			$lowercase = preg_match('@[a-z]@', $password);
			$number    = preg_match('@[0-9]@', $password);


			if($uppercase && $lowercase && $number && strlen($password) >= 6) {
			  return true;
			}else{

				if ( $uppercase == 0 ) {

					$this->list_errors[$campo][] = "Debe contener al menos un caracter en mayúscula.";
				}
				if ( $lowercase == 0 ) {

					$this->list_errors[$campo][] = "Debe contener al menos un caracter en minúscula.";
				}
				if ( $number == 0 ) {

					$this->list_errors[$campo][] = "Debe contener al menos un caracter numérico.";
				}
				if ( strlen(trim($password)) < 6 ) {

					$this->list_errors[$campo][] = "Debe contener 6 o más caracteres.";
				}

				return false;
			}

		}
	

		public function failed(){
			return $this->failed;
		}

		public function getErrors(){
			return $this->list_errors;
		}


        private function is_depending($needed, $value = null) {

            if (!isset($this->post[$needed]) or (empty($this->post[$needed]) and $this->post[$needed] != 0)) {
            	
                return false;
                
            }

            if ($value !== null) {

                if (is_array($this->post[$needed]))
                {

                    return in_array($value, $this->post[$needed]);

                } else {

                    return ($this->post[$needed] == $value);
                }

            }

            return true;

        }

        private function is_choose($campo, $choose) {

            $choose = explode('&', $choose);
            $error = true;
            foreach ($choose as $val) {

                if (isset($this->post[$val]) and !empty($this->post[$val])) {

                    return true;
                }

            }

            $this->list_errors[$campo] = "Debe ingresar al menos un campo";
            return false;

        }

        private function is_password($campo) {
			
			if(isset($this->post[$campo]) && !empty(trim($this->post[$campo]))){

				if( !$this->validaPassword($this->post[$campo],$campo) ) {

					return false;
				}else{

					return true;
				}
			}else{

				$this->list_errors[$campo] = "Debe ingresar un password";
				return false;
			}

		}


		/**
		* Verifica si un campo es un numero entero
		*
		* Si no, tambien ingresa el dato a la lista de errores
		*
		* @param array $campo
		*
		* @return bool 
		*/
		private function is_phone($campo) {

			if($this->generic == true){

				if(isset($this->post[$campo])){
                    
                    $is_int = filter_var($this->post[$campo], FILTER_VALIDATE_INT, array('options' => array('min_range' => 0)));
                    
					if(!$is_int) {

						if( !isset($this->list_errors[$campo]) ) {

							$this->list_errors[$campo] = "Este campo debe poseer sólo números. Evite anteponer un cero.";
						}

						return false;
					}

				}

			}else{

				if(isset($this->post[$campo])){

                    $is_int = filter_var($this->post[$campo], FILTER_VALIDATE_INT, array('options' => array('min_range' => 0)));

					if(!$is_int) {	

						if( !isset($this->list_errors[$campo]) ) {

							$this->list_errors[$campo] = "El campo %".$campo."% debe poseer sólo números. Evite antemoner un cero.";
						}

						return false;
					}

				}

			}
		    
			return true;
		}



	}