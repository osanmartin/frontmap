<?php

    namespace App\helpers;

    use Phalcon\Mvc\User\Plugin;

    /**
     * Retorna datos necesarios para cargar las vista e impresión
     *
     * consulta a la base de datos la información necesaria para generar las vistas de impresión
     *
     * @subpackage   helpers
     * @category     Devuelve los errores
     */
    class Errors extends Plugin {

        private $array_errors = array(
            'missing_parameters' => 'Faltan parametros.',
            'no_records_found_id' => 'No se encontraron registros.',
            'turn_not_available' => 'El turno no está disponible para la acción solicitada.',
            'birth_not_match' => 'La fecha de nacimiento no coincide.',
            'reservation_not_available' => 'La reserva no está disponible para la acción solicitada',
            'reservation_restrictions' => 'No puede reservar puesto que sobrepasa las restricciones definidas.',
            'user_action_restrictions' => 'No tiene permitido realizar esta acción.',
            'bad_credentials' => 'Combinación rut/password Erronea.',
            'no_records_found' => 'No se encontraron registros.',
            'jwt_token_not_found' => 'Token no encontrado',
            'jwt_token_invalid' => 'Token invalido',
            'jwt_token_expired' => 'Token expirado',
            'jwt_user_not_found' => 'Usuario no encontrado',
            'rut_exists'        => 'El rut asociado a usuario ya existe.',
            'user_is_not_patient' => 'El rut asociado no pertenece a un paciente',
            'user_is_not_web' => 'El rut asociado no pertenece a un paciente web'
        );

        public function getMsgError($error_key)
        {

            if($this->array_errors[$error_key]){
                $result = $this->array_errors[$error_key];
            } else {
                $result = "Error no especificado";
            }

            return $result;
        }

    }
