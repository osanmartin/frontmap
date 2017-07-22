<?php

namespace App\library\Constants;

use Phalcon\Mvc\User\Component;
use App\utilities\Utility;



/**
 * Libreria de Constantes para Fechas
 *
 * @subpackage   Library
 * @category     Constant
 */
class Constant extends Component
{

    private $days_full	= array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");

    private $days_mini	= array("Dom","Lun","Mar","Mie","Jue","Vie","Sáb");

    private $months_full	= array( 1 => "Enero",
                                     2 => "Febrero",
                                     3 => "Marzo",
                                     4 => "Abril",
                                     5 => "Mayo",
                                     6 => "Junio",
                                     7 => "Julio",
                                     8 => "Agosto",
                                     9 => "Septiembre",
                                     10 => "Octubre",
                                     11 => "Noviembre",
                                     12 => "Diciembre" );

    private $months_mini	= array( 1 => "Ene",
                                     2 => "Feb",
                                     3 => "Mar",
                                     4 => "Abr",
                                     5 => "May",
                                     6 => "Jun",
                                     7 => "Jul",
                                     8 => "Ago",
                                     9 => "Sep",
                                     10 => "Oct",
                                     11 => "Nov",
                                     12 => "Dic" );


    private $time_minute_convertion    = [
        "minutos"  => 1,
        "horas"    => 60,
        "dias"     => 1440,
        "semanas"  => 10080,
        "meses"    => 43200
    ];

    /**
     * Obtiene dias Full
     *
     * Obtiene el nombre del dia completo indicado en el parametro
     * 
     * @param integer $param recibe un numero entero desde 0 a 6 representado éstos los días
     * desde Domingo a Sábado
     *
     * @return string Retorna el día solicitado
     */

	 public function _getDayFull($param) {
        return $this->days_full[$param];
    }

    /**
     * Obtiene dias Mini
     *
     * Obtiene el nombre del dia abreviado indicado en el parametro
     * 
     * @param integer $param recibe un numero entero desde 0 a 6 representado éstos los días
     * desde Domingo a Sábado
     *
     * @return string Retorna el día solicitado
     */
    function _getDayMini($param) {
        return $this->days_mini[$param];
    }

    /**
     * Obtiene Mes Full
     *
     * Obtiene el nombre del mes completo indicado en el parametro
     * 
     * @param integer $param recibe un numero entero desde 1 a 12 representado con esto el mes 
     * solicitado
     * desde Domingo a Sábado
     *
     * @return string Retorna el mes solicitado
     */
    function _getMonthFull($param) {


        if(!isset($param) || $param == 0){

            $this->error = $this->errors->WRONG_PARAMETERS;
            return false;

        }else{

            if($this->months_full[$param]){

                return $this->months_full[$param];

            }else{

                return false;

            }
        }
    }

    /**
     * Obtiene Mes Mini
     *
     * Obtiene el nombre del mes abreviado indicado en el parametro
     * 
     * @param integer $param recibe un numero entero desde 1 a 12 representado con esto el mes 
     * solicitado
     * desde Domingo a Sábado
     *
     * @return string Retorna el mes solicitado
     */
    function _getMonthMini($param) {

        if(!isset($param) || $param == 0  ){

            $this->error = $this->errors->WRONG_PARAMETERS;
            return false;

        }else{

            if($this->months_mini[$param]){

                return $this->months_mini[$param];

            }else{

                return false;

            }

        }
    }


    /**
     * Método encargado de entregar una fecha en formato escito
     *
     * 
     * @param datetime fecha
     *
     * @return string Fecha escrita
     */
    function _getDateFormatFull($param){
    	# formateamos la fecha
    	$fecha_format = "{day_full} {day}, {month_full} del {year}";

    	$day_full = $this->_getDayFull((int)date("w", strtotime($param)));
    	$fecha_format = str_replace("{day_full}", $day_full, $fecha_format);

    	$day = date("d", strtotime($param));
    	$fecha_format = str_replace("{day}", $day, $fecha_format);

    	$month = $this->_getMonthFull((int)date("m", strtotime($param)));
    	$fecha_format = str_replace("{month_full}", $month, $fecha_format);

    	$year = date("Y", strtotime($param));
    	$fecha_format = str_replace("{year}", $year, $fecha_format);

    	return $fecha_format;
    }

    /**
     * Hace cuanto
     *
     * Metodo encargado de notificar cuanto tiempo ha pasado desde el dato entregado
     * 
     * @param datetime fecha
     *
     * @return string Retorna un string que indica cuanto tiempo ha pasado desde la fecha
     */
    function _timeAgo($time_ago)
    {
        $time_ago = strtotime($time_ago);
        $cur_time   = time();
        $time_elapsed   = $cur_time - $time_ago;
        $seconds    = $time_elapsed ;
        $minutes    = round($time_elapsed / 60 );
        $hours      = round($time_elapsed / 3600);
        $days       = round($time_elapsed / 86400 );
        $weeks      = round($time_elapsed / 604800);
        $months     = round($time_elapsed / 2600640 );
        $years      = round($time_elapsed / 31207680 );
        // Seconds
        if($seconds <= 60){
            return "Ahora";
        }
        //Minutes
        else if($minutes <=60){
            if($minutes==1){
                return "Hace 1 minuto";
            }
            else{
                return "Hace $minutes minutos";
            }
        }
        //Hours
        else if($hours <=24){
            if($hours==1){
                return "Hace 1 hora";
            }else{
                return "Hace $hours hrs";
            }
        }
        //Days
        else if($days <= 7){
            if($days==1){
                return "Ayer";
            }else{
                return "Hace $days días";
            }
        }
        //Weeks
        else if($weeks <= 4.3){
            if($weeks==1){
                return "Hace 1 semana";
            }else{
                return "Hace $weeks semanas";
            }
        }
        //Months
        else if($months <=12){
            if($months==1){
                return "Hace 1 mes";
            }else{
                return "Hace $months meses";
            }
        }
        //Years
        else{
            if($years==1){
                return "Hace 1 año";
            }else{
                return "Hace $years Años";
            }
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
     *
     * @param $unidad
     *
     * @return bool|mixed
     */
    function _getMinutesConvertion($unidad){

        $originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
        $modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';

        #quitamos los acentos y lo dejamos en lowercase
        $unidad = utf8_decode($unidad);
        $unidad = strtr($unidad, utf8_decode($originales), $modificadas);
        $unidad = strtolower($unidad);

        if( array_key_exists($unidad, $this->time_minute_convertion) ){

            return $this->time_minute_convertion[$unidad];

        }
        else{
            return false;
        }

    }



    /**
     *
     * _diffAgo
     *
     *  Retorna edad de paciente
     *
     * @param $unidad
     *
     * @return Edad de paciente
     */

    public function _diffAgo($fecha){

        $utility = new Utility();

        return $utility->getEdad($fecha);

    }

  

}

?>
