<?php
	/*
	 * Define custom routes. File gets included in the router service definition.
	 */
	$router = new Phalcon\Mvc\Router();

	# LOGIN
	$router->add('/', 				  array('controller' => 'home', 	'action' => 'index'));
	/*
	
	$router->add('/login', 			  array('controller' => 'session',  'action' => 'index'));
	$router->add('/test', 			  array('controller' => 'test',  	'action' => 'index'));
	$router->add('/logout', 		  array('controller' => 'session',  'action' => 'logout'));
	$router->add('/verifyUser', 	  array('controller' => 'session', 	'action' => 'verifyUser'));
	$router->add('/verifybirthday',   array('controller' => 'session', 	'action' => 'verifyBirthday'));
	$router->add('/loginpost',  	  array('controller' => 'session',  'action' => 'loginpost'));



	$router->add('/updatepass',  	  array('controller' => 'user',  'action' => 'updatePassword'));
	$router->add('/registroUsuarios', array('controller' => 'user', 	'action' => 'registerUserData'));

	# Actualización de datos
	$router->add('/actualizarDatos', array('controller' => 'user', 			'action' => 'updateUserData' ));

	# AGENDA
    $router->add('/agendamiento', 	 array('controller' => 'scheduling', 	'action' => 'index'));
    $router->add('/gestionReserva',  array('controller' => 'schedule', 		'action' => 'index'));
    $router->add('/consultaReserva/:params', array('controller' => 'schedule', 'action' => 'getscheduledata', 'params' => 1));
    
    $router->add('/documentoReserva/:params', array('controller' => 'schedule', 'action' => 'printreserve', 'params' => 1));
	*/	
	return $router;
