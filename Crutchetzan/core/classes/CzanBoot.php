<?php

require_once realpath('./core/classes/CzanRouter.php');
use core\classes\CzanRouter;
use core\classes\CzanRoute; 
use core\classes\CzanRenderer;

$czan_router = new CzanRouter;

require_once realpath('./apps/config/routes.php');

$params = $czan_router->run();
var_dump($params);

require_once realpath("./apps/controllers/{$params['controller']}.php");
require_once realpath('./core/classes/CzanRenderer.php');

global $czan_renderer;
$czan_renderer = new CzanRenderer;

$controller = new $params['controller'];
$controller->{$params['action']}($params['vars']);