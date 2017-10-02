<?php

	use Phalcon\Mvc\View;
	use Phalcon\DI\FactoryDefault;
	use Phalcon\Mvc\Dispatcher;
	use Phalcon\Mvc\Url as UrlProvider;
	use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
	use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
	use Phalcon\Mvc\Model\Metadata\Memory as MetaData;
	use Phalcon\Mvc\Model\Manager as modelsManager;
	use Phalcon\Session\Adapter\Files as SessionAdapter;
	use Phalcon\Flash\Session as FlashSession;
	use Phalcon\Events\Manager as EventsManager;
	use Phalcon\Crypt;
    use \Phalcon\Mvc\Dispatcher as PhDispatcher;
    use Phalcon\Logger;
    use Phalcon\Logger\Adapter\File as FileLogger;
    use Phalcon\Security;
    use Phalcon\UserPlugin\Plugin\Security as SecurityPlugin;
    use Phalcon\UserPlugin\Auth\Auth;
    use Phalcon\UserPlugin\Acl\Acl;
    use Phalcon\UserPlugin\Mail\Mail;

	#use App\library\Auth\Auth;
    use App\library\Mifaces\Mifaces;
    #use App\library\Mail\Mail;
    use App\library\AccesoAcl\AccesoAcl;
    use App\library\Valida\Valida;
    use App\library\Constants\Constant;
   	use App\library\Errors\Errors;
   	use App\library\PdfCreator\PdfCreator;
	use App\utilities\Utility;

	use App\helpers\Config;


	/**
	 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
	 */
	$di = new FactoryDefault();


	$di->set('config', $config);



	/**
	 * We register the events manager
	 */
	$di->set('dispatcher', function () use ($di, $config) {
        $dispatcher = new Dispatcher();
        $dispatcher->setDefaultNamespace('App\Controllers');

		$configuration = new Config();

        //in production
        if( $config->get("switchUtils")["production"] )
        {
            //set event for 404
            $evManager = $di->getShared('eventsManager');

            $evManager->attach(
                'dispatch:beforeException',
                function($event, $dispatcher, $exception)
                {

                    $action = "";
                    switch ($exception->getCode()) {
                        case PhDispatcher::EXCEPTION_HANDLER_NOT_FOUND:
                            $action = "notFound";
                            break;
                        case PhDispatcher::EXCEPTION_ACTION_NOT_FOUND:
                            $action = "notFound";
                            break;
                        default:
                            $action = "internal";
                            break;
                    }


                    $ctrl = $dispatcher->getActiveController();
                    if($ctrl instanceof \X\ApiControllerBase) {
                        $dispatcher->forward(array(
                            'namespace' => '\\',
                            'controller' => 'error',
                            'action' => 'api',
                            'params' => array('message' => $exception->getMessage())
                        ));
                        return false;
                    }


                    $dispatcher->forward(
                        array(
                            'namespace' => 'App\Controllers',
                            'controller' => 'error',
                            'action' => $action
                        )
                    );

                    return false;
                }
            );

            $dispatcher->setEventsManager($evManager);
        }

        $evManager = $di->getShared('eventsManager');

        $security = new SecurityPlugin($di);
        $evManager->attach('dispatch', $security);

        return $dispatcher;
    },
        true
    );

    /**
	 * Crypt service
	 */
	$di->set('crypt', function () use ($config) {
	    $crypt = new Crypt();

	    $crypt->setKey($config->application->cryptSalt);
	    return $crypt;
	});


	/**
	 * The URL component is used to generate all kind of urls in the application
	 */
	$di->set('url', function () use ($config) {
		$url = new UrlProvider();
		$url->setBaseUri($config->application->baseUri);
		return $url;
	});

    /*
     * config de vistas que use volt por defecto
     */
	$di->set('view', function () use ($config) {
		$view = new View();

		//$view->setViewsDir(APP_DIR . $config->application->viewsDir);

		$view->setViewsDir('../src/views/');

		$view->registerEngines(array(
			".volt" => 'volt'
		));


		$view->utility = new Utility();

		return $view;
	});

	/**
	 * Setting up volt
	 */

	$di->set('volt', function ($view, $di) {

		$volt = new VoltEngine($view, $di);

		$volt->setOptions(array(
			"compiledPath" => "../cache/volt/",
			'stat' => true,
            'compileAlways' => true
		));

		$compiler = $volt->getCompiler();
		$compiler->addFunction('capitalize','ucfirst');
		$compiler->addFunction('is_a', 'is_a');
        $compiler->addFunction("str_contains", "strpos");
        $compiler->addFunction("print_r", "print_r");
		
		return $volt;

	}, true);

	/**
	 * Database connection is created based in the parameters defined in the configuration file
	 */
	/*$di->set('db', function () use ($config) {
		$config = $config->get('database')->toArray();

		echo "<pre>";
		print_r($config);

		echo $dbClass = 'Phalcon\Db\Adapter\Pdo\\' . $config['adapter'];
		unset($config['adapter']);
		return new $dbClass($config);
	});*/

	$di->set('db', function () use ($config) {

        try {

            $db = new DbAdapter(array(
                'host' => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname' => $config->database->dbname,
                'options' => array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                )
            ));
        }
        catch (Exception $e) {

            return false;

        }

        //$db->query('SET QUERY_CACHE_TYPE = OFF;');
        $result = $db->query("SHOW VARIABLES LIKE 'wait_timeout'");
        $result = $result->fetchArray();
        $db->timeout = (int) $result['Value'];
        $db->start = time();
        $eventsManager = new \Phalcon\Events\Manager();


        //Listen all the database events
        $eventsManager->attach('db', function($event, $db) use ($config) {
            $sql = $db->getSQLStatement();
            $vars = $db->getSqlVariables();
            if ($event->getType() == 'beforeQuery' && $sql != 'SELECT 1+2+3') {
                $activeTimeout = time() - $db->start;
                if ($activeTimeout > $db->timeout) {
                    //echo "Reconnect to db - timeout";
                    $db->connect();
                    $db->start = time();
                }
                try {
                    $res = $db->query('SELECT 1+2+3');
                    $resArray = $res->fetch();
                    if ($resArray[0] != 6) {
                        //echo "Reconnect to db - distinct";
                        $db->connect();
                    }
                } catch (\PDOException $e) {
                    //echo "Reconnect to db - excepction";
                    $db->connect();
                }
                $initSql = strtolower(substr( $sql, 0, 6 ));

				$configuration = new Config();

                if ($initSql != "select" and $initSql != 'descri' and $configuration->state('log') ) {
                    $name = date('y-d-m');
                    $logger = new FileLogger("../logs/{$name}.log");
                    $session = new SessionAdapter();
                    $session->start();
                    $user = ':'.$session->get("auth-identity")['id'] . ':' .  $session->get("auth-identity")['nombre'];
                    if (count($vars)) {

                        $logger->log(
                             $user.':'. $sql . ' :params: ' . implode('& ', $vars),
                            Logger::INFO
                        );
                    } else {

                        $logger->log(
                             $user.':'. $sql . ' :params: NULL',
                            Logger::INFO
                        );
                    }

                }
                return true;
            }
        });

        //Assign the eventsManager to the db adapter instance
        $db->setEventsManager($eventsManager);

        return $db;
	});

	/**
	 * If the configuration specify the use of metadata adapter use it or use memory otherwise
	 */
	$di->set('modelsMetadata', function () {
		return new MetaData();
	});

	/**
	 * Start the session the first time some component request the session service
	 */
	$di->set('session', function () {

		$session = new SessionAdapter();
		$session->start();
		return $session;
	});

	/**
	 * Loading routes from the routes.php file
	 */
	$di->set('router', function () {
	    return require __DIR__ . '/routes.php';
	});

	/**
	 * Register the flash service with custom CSS classes
	 */
	$di->set('flash', function () {
		return new FlashSession(array(
			'error'   => 'errorMessage',
			'success' => 'successMessage',
			'notice'  => 'infoMessage',
			'warning' => 'warningMessage'
		));
	});

	/**
	 * Register a user component
	 */
	$di->set('elements', function () {
		return new Elements();
	});


	/**
     * Custom authentication component
     */

	//mifaces
    $di->set('mifaces', function () {
        return new Mifaces();
    });

    //auth autenticacion
     $di->set('auth', function () {
        return new Auth();
    });

    //Mail service uses AmazonSES
    $di->set('mail', function () {
        return new Mail();
    });

    //php excel
    $di->set('iof', function ()  use ($config) {
    	require_once $config->application->libraryDir.'PHPExcel/IOFactory.php';
        return new IOFactory();
    });

    //acl
    $di->set('AccesoAcl', function () {
        return new AccesoAcl();
    });

    //valida formularios data
    $di->set('valida', function () {
        return new Valida();
    });

    //errores de backend
    $di->set('errors', function () {
        return new Errors();
    });

    //Para consultas SQL con ORM
    $di->set('modelsManager', function() {
      return new modelsManager();
    });

    //constantes transversales a la app
    $di->set('Constant', function() {
      return new Constant();
    });

    //pdf
    $di->set('pdfcreator', function(){
    	return new PdfCreator();
    });


	$di->set('configuration', function(){
		return new Config();
	});

    $di->set(
        "security",
        function () {
            $security = new Security();

            // Set the password hashing factor to 12 rounds
            $security->setWorkFactor(12);

            return $security;
        },
        true
    );

    $di->setShared(
        'auth',
        function() {
            return new Auth();
        }
    );

    $di->setShared(
        'acl',
        function() {
            return new Acl();
        }
    );

    $di->setShared(
        'mail',
        function() {
            return new Mail();
        }
    );




?>
