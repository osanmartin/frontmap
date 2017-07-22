<?php

namespace App\utilities;
use App\library\Constants\Constant;
use App\Business\UserBSN;
use App\Business\TurnBSN;

use App\helpers\Config;

/**
 * Clase para guardar metodos para la vista para usar con volt
 * eg: {{ utility._strtotime('parametros_aqui') }}
 * Class Utility
 * @package App\utilities
 */
class Utility
{
    #estado por defecto de un Medical History Common
    private $DEFAULT_EXAMN_STATE_ID = 1;

    function _strtotime($param) {
        return strtotime($param);
    }


    function _split($param) {
        return str_split($param);
    }

    function getItem($array, $indexx, $indexy) {

    	if(isset($array[$indexx][$indexy]))
    		return $array[$indexx][$indexy];
    	else
    		return false;
    }

    function _getDateFormatFull($fecha) {
        $constant = new Constant();
        return $constant->_getDateFormatFull($fecha);
    }

    function _getMonthFull($fecha) {
        $constant = new Constant();
        return $constant->_getMonthFull($fecha);
    }

    function _timeAgo($fecha) {
        $constant = new Constant();
        $fecha = date("Y-m-d H:i:s", strtotime($fecha));

        return $constant->_timeAgo($fecha);
    }

    function _diffAgo($fecha) {

        $constant = new Constant();
        $fecha = date("Y-m-d H:i:s", strtotime($fecha));

        return $constant->_diffAgo($fecha);

    }

    public function getEdad($fecha){

        $nacimiento = new \DateTime($fecha);
        $actual = new \DateTime('now');

        $interval = $nacimiento->diff($actual);

        $years    = $interval->format('%y');
        $months   = $interval->format('%m');
        $days     = $interval->format('%d');


        if( $years > 0 ) {
            if($years == 1){
                return $years.' Año';
            }
            return $years.' Años';
        }

        if($months > 0){
            if($months == 1){
                return $months.' Mes';
            }
            return $months . ' Meses';
        }

        if($days > 0){
            if($days == 1){
                return $days.' Día';
            }
            return $days . ' Días';
        }

        return "-";
    }

    public function getYears($fecha){

        $nacimiento = new \DateTime($fecha);
        $actual = new \DateTime('now');

        $interval = $nacimiento->diff($actual);

        $years    = $interval->format('%y');

        if( $years > 0 ) {

            return $years;
        }else{
            return false;
        }
    }

    function bisiesto($fecha_actual){
        $bisiesto=false;
        //probamos si el mes de febrero del año actual tiene 29 días
        if (checkdate(2,29,$fecha_actual))
        {
            $bisiesto=true;
        }
        return $bisiesto;
    }


    function _getDayFull($numberoofweek){
        $constant = new Constant();
        return $constant->_getDayFull($numberoofweek);
    }

    function _getDayMini($numberoofweek){
        $constant = new Constant();
        return $constant->_getDayMini($numberoofweek);
    }

    function _replace($coincidencia, $reemplazo, $string){
        return str_replace($coincidencia, $reemplazo, $string);
    }

    function _number_format($number) {
        if(is_numeric($number))
            return number_format($number , 0, ',', '.');
        else
            return "-";
    }

    function tienepermiso($metodo, $controlador) {

        $configuration = new Config();

        if (! $configuration->state('acl') ){
            return true;
        }
        else
            return \App\AccesoAcl\AccesoAcl::tienePermiso($metodo, $controlador);

    }

    /**
     * validateHoursConfig
     *
     * retorna un boleano si la fecha aún es valida
     * compara la fecha de hoy y las horas que han pasado,
     * y la compara con las horas de la configuracion
     * si las horas que han pasado son menor a la de configracion retorna True
     * si no, retorna false
     *
     * @param datetime $date
     * @return boolean
     */
    public function validateHoursConfig($date) {

        # validamos la fecha
        if( !isset($date) || is_null($date) || !$this->validateDate($date, 'Y-m-d H:i:s' ))
            return false;

        $config = \App\Models\Configurations::findFirstByName('turn_edition_time');

        # no existe configuración
        if(!$config)
            return false;

        # sin restricción
        if($config->value == '-1')
            return true;

        # no se puede editar
        if($config->value == '0')
            return false;

        # Calculamos la diferencia
        $hora_actual        = new \DateTime('NOW');
        $datetime_finish    = new \DateTime($date);
        $interval           = $datetime_finish->diff($hora_actual);
        $horas_diff         = (int)$interval->format('%h');

        # retornamos true si aún hay tiempo y false si no lo hay
        if($horas_diff <= (int)$config->value)
            return true;
        else
            return false;
    }

    /**
     * validateDate
     *
     * valida una fecha
     *
     * @param datetime $date
     * @param string $format
     *
     * @return boolean
     */
    public function validateDate($date, $format = 'Y-m-d') {

        $d = \DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    function confirmEditTurn($idturn){

        $turn = new TurnBSN();
        return $turn->confirmEditTurn($idturn);
    }

    public function str_contains($str, $search)
    {
        return stripos($str, $search);
    }
    public function camel_case($str){
        return ucwords($str);
    }

    public function _explode($limiter, $str){
        return explode($limiter, $str);
    }

    //retorna primer string encontrado
    public function _explode_first($limiter, $str){

        $arr = explode($limiter, $str);

        return $arr[0];
    }


     public function _special_chart($str){
       $no_permitidas = array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
       $permitidas = array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
       return str_replace($no_permitidas, $permitidas ,$str);
    }

    public function ucfirst($str){
        return ucfirst($str);
    }

    public function _rut_format($rut){

        $rut = str_replace("-","",$rut);

        if(is_numeric($rut)){
            return number_format( substr ( $rut, 0 , -1 ) , 0, "", ".") . '-' . substr ( $rut, strlen($rut) -1 , 1 );
        }
        else{
            return $rut;
        }

    }

    /**
     *
     * Enlaza datos de una historia medica por el numero del fieldtype
     *
     * @param object $mh->MedicalHistoryCommonExtra
     *
     * @return array
     */
    public function _parseArray($mh){

        $arr = [];

        foreach ($mh->MedicalHistoryCommonExtra as $key => $val) {
             $arr[$val->field_name] = $val->field_value;
        }

        if(empty($arr))
            return $arr;

        $arrList = $arr;
        $arrResult = [];
        foreach ($arrList as $key => $val) {

            $arrFieldName = explode("-", $key);
            $actualName = reset($arrFieldName);
            $actualNum = end($arrFieldName);

            $arrResult[$actualNum][$actualName] = $val;
        }

        return $arrResult;


    }

    public function _parseArrayTimeline($mh){

        $arr = [];

        foreach ($mh->MedicalHistoryCommonExtra as $key => $val) {
             $arr[$val->field_name] = $val->field_value;
        }

        $arrList = $arr;

        if(empty($arr))
            return $arr;

        $arrResult = [];
        foreach ($arrList as $key => $val) {

            $arrFieldName = explode("-", $key);
            $actualName = reset($arrFieldName);
            $actualNum = end($arrFieldName);

            $arrResult[$actualNum][$actualName] = $val;
        }



        $arrTo = array();

        foreach($arrResult as $key => $item)
        {
            if(isset($item['user']))
                $arrTo[$item['user']][$key] = $item;
            else
                $arrTo[$item['usuario']][$key] = $item;
        }

        return $arrTo;

    }

    public function getKey($arr){

        return key($arr);

    }

    public function _parseArraySimple($mh){
        $arr = array();

        foreach ($mh->MedicalHistoryCommonExtra as $key => $val) {
            $arr[$val->field_name] = $val->field_value;
        }
        return $arr;
    }

    public function _getSubStr( $str, $init, $finish ){

        return substr($str, $init, $finish);

    }

    /**
     * Agrupa un array según la palabra clave solicitada
     * retorna array agrupado o array vacío si no encuentra coincidencias
     */

    public function _toArrayGroup($word, $arr){

        $groupArr = [];
        foreach ($arr as $key => $value) {
            $pos = strpos( $key, $word);
            if( $pos !== false ){
                $groupArr[$key] = $value;
            }
        }
        return $groupArr;
    }

    /**
     * _prepareExtraToDatabase
     *
     * Método formatear un formulario enviado a la base de datos.
     *
     * @param array $post con datos de formulario
     * @param object $turn con objeto de turno
     *
     */
    public function _prepareExtraToDatabase($post, $turn, $session, $common_id = null){

        #ARRAY PARA PERSISTIR EL FORMULARIO, CONTIENE datos common y extras
        $dataFormPersist["extras"] = [];
        $dataFormPersist["common"] = [];

        #OBTENEMOS LOS DATOS NECESARIOS
        $description    = isset($post["description"])? $post["description"] : ""; // Si no viene dejamos un string vacio
        $state          = isset($post["status"])? $post["status"] : $this->DEFAULT_EXAMN_STATE_ID; // si no viene dejamos el estado por defecto
        $state          = trim($state) != ""? $state : $this->DEFAULT_EXAMN_STATE_ID; // si viene vacio dejamos el estado por defecto
        $historyType    = $post["form_type"];

        $turnId         = $turn->id;

        #DEFINIMOS LOS DATOS COMMONS
        if ($common_id != null) {
            $dataFormPersist['common']['id'] = $common_id;
        }
        $dataFormPersist['common']['medical_history_type_id'] =   $historyType;
        $dataFormPersist['common']['created_by_user_id']      =   $session->get('auth-identity')["id"];
        $dataFormPersist['common']['description']             =   $description;
        $dataFormPersist['common']['user_patient_id']         =   $turn->Users->id;
        $dataFormPersist['common']['usb_id']                  =   $turn->UsersSpecialtiesBranchoffices->id;
        $dataFormPersist['common']['state_id']                =   $state;
        $dataFormPersist['common']['turn_id']                 =   $turnId;
        if ($common_id == null) {
            foreach ($post as $nameinput => $valueinput) {

                #verificamos si el campo viene con prefijo
                $result = preg_match("#^extra-(.*)$#i", $nameinput);

                if ($result) {

                    $nombrecampo = explode("extra-",$nameinput)[1]; // guardamos el nombre real del campo sin prefijo.

                    if (isset($valueinput) && (!empty($valueinput) || intval($valueinput) === 0 ) && $valueinput != "") {

                        #si el campo es un array (checkbox)
                        if (is_array($valueinput)) {

                            foreach ($valueinput as $inputitemkey => $inputitem) {
                                /**
                                 * Se agregan los campos que son array de la forma
                                 *
                                 * nombrecampo-1 = valorx
                                 * nombrecambo-2 = valory
                                 *
                                 */

                                $dataFormPersist["extras"][$nombrecampo."-".($inputitemkey+1)]["value"] = $inputitem; // se agregan dinamicamente
                                $dataFormPersist["extras"][$nombrecampo."-".($inputitemkey+1)]["type"] = "text";
                                $dataFormPersist["extras"][$nombrecampo."-".($inputitemkey+1)]["turn_id"] = $turnId;

                            }

                        } else {

                            $dataFormPersist["extras"][$nombrecampo]["value"]   = $valueinput; // se agregan dinamicamente
                            $dataFormPersist["extras"][$nombrecampo]["type"]    = "text"; // se agregan dinamicamente

                        }

                    }

                }

            }
        } else {

            foreach ($post as $nameinput => $valueinput) {

                #verificamos si el campo viene con prefijo
                $result = preg_match("#^extra-(.*)$#i", $nameinput);

                if ($result) {

                    $nombrecampo = explode("extra-",$nameinput)[1]; // guardamos el nombre real del campo sin prefijo.

                    if (isset($valueinput) && (!empty($valueinput) || intval($valueinput) === 0 ) && $valueinput != "") {

                        #si el campo es un array (checkbox)
                        if (is_array($valueinput)) {

                            foreach ($valueinput as $inputitemkey => $inputitem) {
                                /**
                                 * Se agregan los campos que son array de la forma
                                 *
                                 * nombrecampo-1 = valorx
                                 * nombrecambo-2 = valory
                                 *
                                 */
                                $dataFormPersist["extras"][$nombrecampo."-".($inputitemkey+1)] = $inputitem; // se agregan dinamicamente

                            }

                        } else {

                            $dataFormPersist["extras"][$nombrecampo] = $valueinput; // se agregan dinamicamente

                        }

                    }

                }

            }
        }

        return $dataFormPersist;
    }

    public function formatMultipleFields($param) {

        $result = [];
        foreach ($param as $key => $val) {
            $cols = explode('-', $key);
            $name = '';
            for($i = 1; $i < sizeof($cols)-1; $i++) {
                $name = $name . $cols[$i];
            }
            $result[(int)end($cols)][$name] = $val;

        }
        return $result;

    }

    public function getStandardDate($date){

        $dateObj = date_create($date);

        return date_format($dateObj,'d-m-Y');
    }

    public function getDateYYMMDD($date){

        $dateObj = date_create($date);

        return date_format($dateObj,'Y-m-d');
    }

    

    public function getStandardTime($date){

        $dateObj = date_create($date);

        return date_format($dateObj,'H:i');
    }

    public function template_exists($param) {

        $base_dir = __DIR__ . '/../views/';

        return file_exists($base_dir . $param . '.volt');

    }

    //Verifica si un turno está disponible comparando su hora con la hora actual
    //si la hora del turno es mayo a la actual, el turno estará disponible
    public function avaibleTurn($dateTurn) {

        try {

            $now = new \DateTime('now');
            $turn = new \DateTime($dateTurn);

            if ( $turn >= $now) {

                return true;
            }else{
                return false;
            }

        } catch (Exception $e) {

            return false;

        }

    }


    //Verifica si un turno está disponible comparando su hora con la hora actual
    //si la hora del turno es mayo a la actual, el turno estará disponible
    public function minDateToday($date) {

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

    //Verifica si un turno está disponible comparando su hora con la hora actual
    //si la hora del turno es mayo a la actual, el turno estará disponible
    public function minMaxDateToday($date) {

        $now  = new \DateTime('now');
        $now  = $now->format('Y-m-d');

        $turn = new \DateTime($date);
        $turn = $turn->format('Y-m-d');

        if ( $turn == $now) {
            return true;
        }else{
            return false;
        }

    }


    //calcula el total de medicamento a ingerir desde un registro en medical_history_drugs_related
    public function getTotalDrug($drug){

      $constantObj = new Constant();

      $drugRelated = $drug->MedicalHistoryDrugsRelated;

      #Calculamos cuantas ordenes debemos generar
      $frequency = (integer) $drugRelated->frequency_time;
      $duration  = (integer) $drugRelated->duration_time;


      $frequency_unit = $constantObj->_getMinutesConvertion($drugRelated->frequency_unit);
      $duration_unit  = $constantObj->_getMinutesConvertion($drugRelated->duration_unit);

      #si las unidades viene mal seteadas
      if( !$duration_unit OR !$frequency_unit ){
          return '-';
      }

      #si hay division por cero
      if( $frequency * $frequency_unit == 0 ){
          return '-';
      }

      #obtenemos cantidad de ordenes a multiplicar por dosis
      $orders_quantity = ceil(( $duration * $duration_unit ) / ( $frequency * $frequency_unit ));

      $total = $drug->dose * $orders_quantity;

      echo ceil($total);




    }


    //Retorna rut / pasaporte / o numero de identificación asociado a paciente
    public function _getNumDoc($patient_id){

        if(isset($patient_id)){

            $userObj = new UserBSN();
            $param['patient_id'] = $patient_id;
            $numDocument = $userObj->getDocumentNumber($param);

            return $numDocument;

        }else{

            return false;

        }
    }

    // Obtiene semanas de embarazo en base a días y meses

    public function _getWeekPregnancy($weeks,$days){

        $totalDays = $weeks*7 + $days;
        return floor($totalDays/7);

    }

    //obtiene una id de usuario y retorna nombre completo
    //este metodo es para ser utilizado donde no se tenga acceso al objeto usuario
    //y hacer la la modificación signifique un alto costo
    public function getFullName($patient_id){

        $userBsn = new UserBSN();
        $user = $userBsn->getUserById($patient_id);

        if($user != false){

            $fullName = $user->UserDetails->getFullName();

            return $fullName;
        }else{
            return '';
        }


    }


    public function isFreeConfiguration($turn_configuration_id){

            $ESTADO_SIN_AGENDAR     = 1;

            $turnBsn = new TurnBSN();
            $param['turn_configuration_id'] = $turn_configuration_id;

            $turns = $turnBsn->getTurnsByConfiguration($param);

            if($turns != false){
                foreach ($turns as $turn) {

                    if($turn->turn_state_id != $ESTADO_SIN_AGENDAR){

                        return false;
                    }
                }
            }

            return true;

    }

    public function _parseArraySimpleGroup($mh){

        $list = $this->_parseArraySimple($mh);
        $arr = [];

        foreach ($list as $key => $val) {
            
            $exploded = str_replace('_', '-', $key);
            $exploded = explode('-', $exploded);

            if(is_numeric(end($exploded))) {

                $exploded = array_slice($exploded, 0, -1);
                $newKey = implode('_', $exploded);
                $arr[$newKey][] = $val;

            } else {

                $arr[$key] = $val;
    
            }


        }

        return $arr;

    }

    /**
    * calculateQtyDrugsFromRelated
    *
    * Método para realizar el calculo de medicamentos a mostrar en vista
    * es capaz de entregar un resultado numerico o concatenado a la unidad de medida
    * @param array $drug 
    * @return string con resultado de calculo
    */
    public function calculateQtyDrugs($drug){

        
        $time_minute_convertion    = [
                "minutos"  => 1,
                "horas"    => 60,
                "dias"     => 1440,
                "semanas"  => 10080,
                "meses"    => 43200
            ];

        $frequency = (integer) $drug['frequency_time'];
        $duration  = (integer) $drug['duration_time'];


        $frequency_unit = $this->_getMinutesConvertion($drug["frequency_unit"]);
        $duration_unit  = $this->_getMinutesConvertion($drug["duration_unit"]);

        #si las unidades viene mal seteadas
        if(!$duration_unit OR !$frequency_unit){
            
            return false;
        }

        #si hay division por cero
        if($frequency * $frequency_unit == 0){
            
            return false;
        }


        #obtenemos cantidad de ordenes a persistir
        $orders_quantity = ceil(( $duration * $duration_unit ) / ( $frequency * $frequency_unit ));

        $total = $drug['dose'] * $orders_quantity;

        return ceil($total);
    }

    /**
    * calculateQtyDrugsFromRelated
    *
    * Método para realizar el calculo de medicamentos a mostrar en vista
    * es capaz de entregar un resultado numerico o concatenado a la unidad de medida
    * @param object $drug de model medical_history_drug_related
    * boolean $noLabel
    * @return string con resultado de calculo
    */
    public function calculateQtyDrugsFromRelated($drug, $noLabel = false){

        if(is_object($drug)){
                
            if( $drug->MedicalHistoryDrugs->count() == 1 ){

                foreach ($drug->MedicalHistoryDrugs as $item) {
                    
                    $dose = $item->dose;
                    $unit = $item->unit;
                }

                $drug = $drug->toArray();
                $drug['dose'] = $dose;
                $drug['unit'] = $unit;
            }
                
        }

        $time_minute_convertion    = [
                "minutos"  => 1,
                "horas"    => 60,
                "dias"     => 1440,
                "semanas"  => 10080,
                "meses"    => 43200
            ];

        $frequency = (integer) $drug['frequency_time'];
        $duration  = (integer) $drug['duration_time'];


        $frequency_unit = $this->_getMinutesConvertion($drug["frequency_unit"]);
        $duration_unit  = $this->_getMinutesConvertion($drug["duration_unit"]);

        #si las unidades viene mal seteadas
        if(!$duration_unit OR !$frequency_unit){
            
            return false;
        }

        #si hay division por cero
        if($frequency * $frequency_unit == 0)
        {
            
            return false;
        }


        #obtenemos cantidad de ordenes a persistir
        $orders_quantity = ceil(( $duration * $duration_unit ) / ( $frequency * $frequency_unit ));

        $total = $drug['dose'] * $orders_quantity;

        if( $noLabel ){

            return ceil($total);

        }else{ return ceil($total).' '.$drug['unit'];

        }
    }


    /**
     *
     * _getMinutesConvertion
     *
     *      retorna la conversion en minutos de una unidad ejemplo
     *
     *      HórÁs retorna 60  (por 60 minutos en una hora)
     *
     *      retorna en false en caso de no exito
     *
     * @title  _getMinutesConvertion
     *
     * @param $unidad
     *
     * @return bool|mixed
     */
    function _getMinutesConvertion($unidad){

        $time_minute_convertion    = [
                "minutos"  => 1,
                "horas"    => 60,
                "dias"     => 1440,
                "semanas"  => 10080,
                "meses"    => 43200
            ];

        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';

        #quitamos los acentos y lo dejamos en lowercase
        $unidad = utf8_decode($unidad);
        $unidad = strtr($unidad, utf8_decode($originales), $modificadas);
        $unidad = strtolower($unidad);

        if( array_key_exists($unidad, $time_minute_convertion) ){

            return $time_minute_convertion[$unidad];

        }
        else{
            return false;
        }

    }

}
