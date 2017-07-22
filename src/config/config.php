<?php
return new \Phalcon\Config([

    'application' => [
        'controllersDir'            => APP_DIR.'/Controllers/',
        'utilitiesDir'              => APP_DIR.'/utilities/',
        'modelsDir'                 => APP_DIR.'/models/',
        'viewsDir'                  => APP_DIR.'/views/',
        'formsDir'                  => APP_DIR.'/forms/',
        'libraryDir'                => APP_DIR.'/library/',
        'pluginsDir'                => APP_DIR.'/plugins/',
        'cacheDir'                  => APP_DIR.'/cache/',
        'baseUri'                   => '/frontmap/',
        'publicUrl'                 => '/frontmap/',
        'cryptSalt'                 => 'eEAfR|_&G&f,+vU]:jFr!!A&+71w1Ms9'
    ],
    'switchUtils'   => [
        'production'  => true
    ],
    'noAuth'        => [
        'home'      => array('*'    =>  true),
        'scheduling'      => array('*'    =>  true),
        'user'      => array('*'    =>  true),
        'schedule'      => array('*'    =>  true),
        'session'   => array('*'    =>  true),
        'prueba'    => array('*'    =>  true),
        'test'      => array('*'    =>  true)
    ],
    'urlApi'        => "http://localhost/frontmap/api/",
    'appTitle'      =>'Memoria Maps',
    'appName'       =>"Memoria Maps",
    'appAutor'      =>'OSC',
    'appAutorLink'  =>'OSC',

  

]);
