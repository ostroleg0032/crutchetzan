<?php

namespace core\classes;

//CZAN ROUTE SHORTCUTS
define('CRS', [
    ':int:[a-zA-Z][a-zA-Z0-9]*:' => '[1-9][0-9]*',
    ':str:[a-zA-Z][a-zA-Z0-9]*:' => '[a-zA-Z0-9]+'
]);

class CzanRoute {

    private $czan_url;
    private $action_name;

    public function __construct($czan_url, $action) {
        $this->czan_url = $czan_url;
        $this->action_name = $action_name;
    }

    public function to_regex_pattern() {
        $pattern = $this->czan_url;
        $pattern = preg_replace('/\//', '\/', $pattern);
        $pattern = preg_replace('/:int:[a-zA-Z][a-zA-Z0-9]*:/', '[1-9][0-9]*', $pattern);
        $pattern = "/^{$pattern}$/";
        return $pattern;
    }

    public function get_var_pos() {
        return explode('/', $this->czan_url);
    }
}


class CzanRouter {

    protected $czan_routes = [];

    public function match($url) {
        foreach ($this->czan_routes as $czan_route) {
            preg_match($czan_route->to_regex_pattern(), $url, $matches);
            if ($matches) {
                return $czan_route;
            }
        }
        return null;
    }

    public function route($czan_url, $action_name) {
        $czan_route = new CzanRoute($czan_url, $action_name);
        $this->czan_routes[] = $czan_route; 
    }


    public function run() {
        $url = trim($_SERVER['REQUEST_URI'], '/');
        if ($czan_route = $this->match($url)) {
            echo "This route was founded!";
        } else {
            echo "No route!";
        }
        var_dump($czan_route->get_var_pos());
    }
}
