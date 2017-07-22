<?php
    namespace App\helpers;

    use Phalcon\Mvc\User\Plugin;
    use Phalcon\Http\Request;
    use App\Models\Configurations;

    /**
     * Configuration
     *
     * Acceso directo a las confriguraciones
     *
     * @subpackage   helpers
     * @category     Configuration
     */
    class Config extends Plugin {

        private $config;

        function __constructs__($config){

            $this->config = $config;
        }

        /**
         * state
         *
         * retorna el estado de una confirguración
         *
         * @param string $config
         */
        public function state($config)
        {
            $this->config = $config;

            /*$configuration = Configurations::findFirstByName($this->config);
            
            if( ! count($configuration) )
                return false;

            return (bool)$configuration->value;*/

            return false;
        }

        /**
         * param
         *
         * retorna el valor de un parametro de una confirguración
         *
         * @author Jorge Silva
         * @param string $config
         */
        public function param($config)
        {
            $this->config = $config;

            $configuration = Configurations::findFirstByName($this->config);

            if( ! count($configuration) )
                return false;

            return $configuration->value;
        }
    }
