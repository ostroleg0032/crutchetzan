<?php

require_once realpath('./core/classes/CzanRouter.php');
use core\classes\CzanRouter;
use core\classes\CzanRoute; 

$czan_router = new CzanRouter;

require_once realpath('./apps/config/routes.php');

$czan_router->run();