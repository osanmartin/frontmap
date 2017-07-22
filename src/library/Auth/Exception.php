<?php
namespace App\library\Auth;

/**
 * Excepciones
 *
 * En esta clase podemos encontrar una sobreescritura de la clase Exception
 *
 * @subpackage   Library
 * @category     Auth
 */	
class Exception extends \Exception
{

    protected $field;


    /**
    *Contructor
    *
    * @param $message
    * @param $field
    * @param $code
    * @param $previous
    */
    public function __construct($message, $field = null, $code = 0, Exception $previous = null) {

        $this->field = $field;

        parent::__construct($message, $code, $previous);

    }

    /**
    * Obtiene campo
    *
    *@return $field
    */
    public function getField(){
        return $this->field;
    }


}
