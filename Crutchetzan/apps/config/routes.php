<?php

$czan_router->route('users/:int:id:jopa', 'login');
$czan_router->route('users/:int:id:/qwerty/:int:age:', '');
$czan_router->route('users', 'login');
$czan_router->route('', 'index');
