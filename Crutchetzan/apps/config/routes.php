<?php

$czan_router->route('users/:int:id:', 'detail');
$czan_router->route('users/:int:id:/qwerty/:str:name:', '');
$czan_router->route('users', 'all');
$czan_router->route('', 'index');
