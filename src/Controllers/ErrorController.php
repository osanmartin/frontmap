<?php

    namespace App\Controllers;

    use Phalcon\Mvc\View\Engine\Volt\Compiler as VoltCompiler;

    /**
     * Class ErrorController
     *
     * @subpackage   Errores
     * @category     Errores Controller
     * @title        Manejo de Errores
     *
     */
    class ErrorController extends ControllerBase
    {
        /**
         *
         * show500Action
         *
         * Cuando ocurre una execpción
         *
         */
        public function internalAction()
        {
            if($this->request->isAjax()){

                $this->mifaces->newFaces();
                $this->mifaces->addToMsg("danger","500 - Ha ocurrido un error inesperado, prontamente será arreglado.", true);
            }

            $this->response->setStatusCode(500, 'Internal Server Error');
            $this->view->pick('controllers/error_pages/500');
        }


        /**
         *
         * notFoundAction
         *
         * Cuando una ruta (controller y/o action) no existe en el sistem
         */
        public function notFoundAction()
        {
            if($this->request->isAjax()){

                $this->mifaces->newFaces();
                $this->mifaces->addToMsg("danger","404 - Elemento no encontrado.", true);
            }


            $this->response->setStatusCode(404, 'Not Found');
            $this->view->pick('controllers/error_pages/404');
        }


        /**
         *
         * apiAction
         *
         * @return \X\ApiResponse
         */
        public function apiAction() {
            $pars = $this->dispatcher->getParams();
            $this->response = new API\ApiResponse();
            $this->response->setResponseError($pars['message']);
            return $this->response;
        }


        /**
         *
         * apiAction
         *
         * @return \X\ApiResponse
         */
        public function apiGenericResponseAction(API\ApiResponse $response) {
            $this->response = new API\ApiResponse();
            $this->response = $response;
            return $this->response;
        }



    }