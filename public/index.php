<?php

/**
 * Front controller
 *
 * PHP version 7.0
 */

/**
 * Composer
 */
require dirname(__DIR__) . '/vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');


/**
 * Routing
 */
$router = new Core\Router();

// Routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('page/{pagenumber:\d+}', ['controller' => 'Home', 'action' => 'index']);
$router->add('auth/login', ['controller' => 'Auth', 'action' => 'login']);
$router->add('auth/logout', ['controller' => 'Auth', 'action' => 'logout']);

$router->add('task/add', ['controller' => 'Task', 'action' => 'add']);
$router->add('task/{id:\d+}/edit', ['controller' => 'Task', 'action' => 'edit']);
    
$router->dispatch($_SERVER['QUERY_STRING']);
