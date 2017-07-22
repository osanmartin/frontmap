<?php
namespace App\library\Mifaces;

use Phalcon\Mvc\User\Component;

class Mifaces extends Component{

	private $_toRend;
	private $_toErrorForm;
    private $_toErrorFormGeneric;
    private $_toRedir;
	private $_toNewWin;
	private $_toMsg;
	private $_toDataSelect;
	private $_socket;
    private $_toDataView;
    private $_toJsonView;
    private $_toSwalRend;
    private $_toDataForm;
    private $_toRendAppend;
	private $_toLog;

    public function __construct() {
		$this->newFaces();
	}

    public function newFaces() {
		$this->_toRend			= array();
		$this->_toErrorForm		= array();
        $this->_toErrorFormGeneric = array();
		$this->_toNewWin		= null;
        $this->_toRedir			= null;
		$this->_toMsg			= array();
		$this->_toDataSelect	= array();
		$this->_socket			= array();
        $this->_toDataView      = array();
        $this->_toJsonView      = array();
        $this->_toSwalRend      = array();
        $this->_toDataForm      = array();
        $this->_toRendAppend    = array();
		$this->_toLog 			= array();
    }


	public function addToMsg($type, $msg, $run = false){
		$this->_toMsg[$type]=$msg;
		if($run){
			$outputs[] = array('type' => 'msg',		'msgs'		=> $this->_toMsg );
			$this->response($outputs);
			exit();
		}

	}

	public function addNewWin($htmlString, $name, $run = false){
		if($name!=null && $htmlString!=null){
			$this->_toNewWin['name']=$name;
			$this->_toNewWin['htmlString']=$htmlString;

			if($run){
				$outputs[] = array('type' => 'newWin', 'win' =>$this->_toNewWin['htmlString'], 'name'=>$this->_toNewWin['name']);
				$this->response($outputs);
			}
		}
	}

    public function addRedir($url, $run = false){
        if($url!=null){
            $this->_toRedir=$url;
            if($run){
                $outputs[] = array('type' => 'redir', 'redir'=>$url);
                $this->response($outputs);
            }
        }
    }

	public function addSocket($jquery){
		$this->_socket[]=$jquery;
	}

	public function addToRend($div,$htmlString, $run = false){
		$this->_toRend[$div]=$htmlString;
		if($run){
			$outputs[] = array('type' => 'render',	'renders'	=> $this->_toRend );
			$this->response($outputs);
			exit();
		}
	}

    public function addToRendAppend($div,$htmlString, $run = false){
        $this->_toRendAppend[$div]=$htmlString;
        if($run){
            $outputs[] = array('type' => 'renderappend',	'renders'	=> $this->_toRendAppend );
            $this->response($outputs);
            exit();
        }
    }

	public function addErrorsForm($arreglo, $run = false){

        $arr['grouped']    = false;
        $arr['msg']        = $arreglo;

		if($arreglo != null && $arreglo != ''){
			$this->_toErrorForm = $arr;
		}
		if($run){
			$outputs[] = array('type' => 'errorForm', 'data' => $this->_toErrorForm);
			$this->response($outputs);
			exit();
		}
	}


	public function addErrorsFormGeneric($arreglo , $div, $run = false) {

        $arr['grouped']     = true;
        $arr['msg']         = $arreglo;
        $arr['div']         = $div;

        if($arreglo != null && $arreglo != ''){
            $this->_toErrorFormGeneric[$div] = $arr;
        }
        if($run){
            $outputs[] = array('type' => 'errorFormGeneric', 'data' => $this->_toErrorForm);
            $this->response($outputs);
            exit();
        }
    }

	public function addToDataView($key, $data, $run = false){
	    if($key != null && $data != null && $key != '' && $data != '') {
	           $this->_toDataView[$key] = $data;
        }
        if($run) {
            $outputs[] = array('type' => 'data', 'dataview' => $this->_toDataView);
            $this->response($outputs);
            exit();
        }
    }

	public function addLog($msg){

		array_push($this->_toLog, $msg);
	}

    public function addToJsonView($key, $array, $run = false) {


        if($key != null && $array != null && $key != '' && $array != '') {
            $this->_toJsonView[$key] = $array;
        }
        if($run) {
            $outputs[] = array('type' => 'json', 'datajson' => $this->_toJsonView);
            $this->response($outputs);
            exit();
        }

    }

    public function addToDataSelect($to, $data, $selected = false, $reverse_selected = false, $run = false){
        $this->_toDataSelect[$to]["data"]    =   $data;

        if(isset($selected) && $selected != false){
            $this->_toDataSelect[$to]["selected"]    =   $selected;
        }

        //si es true el selected se hace el match con el value
        // en caso contrario se hace con el label
        if(isset($reverse_selected) && $reverse_selected == true)
            $this->_toDataSelect[$to]["reverse_selected"]    =   true;
        else
            $this->_toDataSelect[$to]["reverse_selected"]    =   false;

        if($run){
            $outputs[] = array('type' => 'dataSelect', 'renders' => $this->_toDataSelect);
            $this->response($outputs);
            exit();
        }
    }

    public function addToSwalRend($config, $htmlString, $run = false) {

        $this->_toSwalRend[$config["type"]]["html"] = $htmlString;
        $this->_toSwalRend[$config["type"]]["config"] = $config;

        if($run){
            $outputs[] = array('type' => 'swal',	'renders'	=> $this->_toSwalRend );
            $this->response($outputs);
            exit();
        }
    }

    public function addToFormData($arreglo, $run = false) {
        if($arreglo != null && $arreglo != ''){
            $this->_toDataForm = $arreglo;
        }
        if($run){
            $outputs[] = array('type' => 'dataForm', 'data' => $this->_toDataForm);
            $this->response($outputs);
            exit();
        }
    }

	public function run(){

		$outputs = array();


		if(count($this->_toRend)>0)
			$outputs[] = array('type' => 'render',	'renders'	=> $this->_toRend );

        if(count($this->_toRendAppend)>0)
            $outputs[] = array('type' => 'renderappend',	'renders'	=> $this->_toRendAppend );

        if($this->_toRedir!=null)
            $outputs[] = array('type' => 'redir',	'redir'		=> $this->_toRedir );

		if(count($this->_socket)>0)
			$outputs[] = array('type' => 'socket',	'sockets'		=> $this->_socket );

		if($this->_toNewWin!=null)
			$outputs[] = array('type' => 'newWin', 	'win'		=> $this->_toNewWin['htmlString'], 'name' => $this->_toNewWin['name']);

		if(count($this->_toMsg)>0)
			$outputs[] = array('type' => 'msg',		'msgs'		=> $this->_toMsg );

		if(count($this->_toDataSelect)>0){
            $outputs[] = array('type' => 'dataSelect', 'renders' => $this->_toDataSelect);
		}

		if(count($this->_toDataView)>0) {
		    $outputs[] = array('type' => 'data',    'dataview'  => $this->_toDataView);
        }

        if(count($this->_toJsonView) > 0){
            $outputs[] = array('type' => 'json', 'datajson' => $this->_toJsonView);
        }

        if(count($this->_toSwalRend)>0) {
            $outputs[] = array('type' => 'swal',	'renders'	=> $this->_toSwalRend );
        }

        if(count($this->_toErrorForm)>0){
        	$outputs[] = array('type' => 'errorForm', 'data' => $this->_toErrorForm);
        }

        if(count($this->_toErrorFormGeneric)>0){
            $outputs[] = array('type' => 'errorFormGeneric', 'data' => $this->_toErrorFormGeneric);
        }

		if(count($this->_toDataForm) > 0) {
            $outputs[] = array('type' => 'dataForm', 'data' => $this->_toDataForm);
        }

		if(count($this->_toLog) > 0) {
            $outputs[] = array('type' => 'log', 'data' => $this->_toLog);
        }


		$this->response($outputs);
	}


    private function response($outputs){


        if($this->session->get('auth-identity')){
            $outputs[] = array('type' => 'csrf', 'csrfdata' => $this->session->get('csrf-token'));    
        }
        echo json_encode($outputs);

    }
}
