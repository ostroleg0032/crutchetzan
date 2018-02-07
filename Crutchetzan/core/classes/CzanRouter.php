<?php

namespace core\classes;

//CZAN ROUTE SHORTCUTS
define('CRS', [
    ':int:([a-zA-Z][a-zA-Z0-9]*):' => '[1-9][0-9]*',
    ':str:([a-zA-Z][a-zA-Z0-9]*):' => '[a-zA-Z]+'
]);

class CzanRoute {

    private $czan_url;
    private $action_name;

    public function get_action_name() {
        return $this->action_name;
    }

    public function __construct($czan_url, $action_name) {
        $this->czan_url = $czan_url;
        $this->action_name = $action_name;
    }

    public function to_regex_pattern() {
        $pattern = $this->czan_url;
        $pattern = preg_replace('/\//', '\/', $pattern);
        foreach(CRS as $shortcut => $real) {
            $pattern = preg_replace("/{$shortcut}/", $real, $pattern);
        }
        $pattern = "/^{$pattern}$/";
        return $pattern;
    }

    public function get_var_pos() {
        $splitted_czan_url = explode('/', $this->czan_url);
        $res = [];
        $ctr = 0;
        foreach ($splitted_czan_url as $part) {
            foreach (CRS as $shortcut => $real) {
                preg_match("/^{$shortcut}$/", $part, $match);
                if ($match) {
                    $res[$ctr] = $match[1];
                    break;
                }
            }
            $ctr++;
        }
        return $res;
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
        $request_uri = trim($_SERVER['REQUEST_URI'], '/');
        $url = parse_url($request_uri, PHP_URL_PATH);
        if ($czan_route = $this->match($url)) {
            $var_pos = $czan_route->get_var_pos();
            $action = $czan_route->get_action_name() . "Action";
            $splitted_url = explode('/', $url);
            if ($url != "") {
                $controller = ucfirst($splitted_url[0]) . "Controller";
            } else {
                $controller = "IndexPageController";
            }
            $vars = [];
            foreach ($var_pos as $index => $var_name) {
                $vars[$var_name] = $splitted_url[$index];
            }
            $res = [];
            $res["controller"] = $controller;
            $res["action"] = $action;
            $res["vars"] = $vars;
            return $res;
        } else {
            return null;
        }
    }
}

/*
[
    controller_name => "", if url = "" then IndexPageController;
    action_name => "",
    vars => [
        id
        name
    ]
]
*/
