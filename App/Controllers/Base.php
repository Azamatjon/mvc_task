<?php

namespace App\Controllers;

use Core\Controller;

/**
 * Base controller
 *
 * PHP version 7.0
 */
class Base extends Controller
{

    protected $is_logged_in, $view_data, $params, $alerts;

    /**
     * Base class constructor
     *
     * @param array $params Params generated from URI query string
     */
    function __construct($params) {
        parent::__construct($params);

        // Start session always
        session_start();

        // Initialize variables
        $this->params = $params;
        $this->alerts = [];
        $this->is_logged_in = isset($_SESSION['isLoggedIn']);

        $this->view_data['isLoggedIn'] = &$this->is_logged_in;
        $this->view_data['alerts'] = &$this->alerts;

        // Query string of GET method
        $url_components = parse_url($_SERVER['REQUEST_URI']);
        $query_params = [];
        if (isset($url_components['query'])){
            parse_str($url_components['query'], $query_params);
        }
        $this->view_data['query_string'] = strlen(http_build_query($query_params)) > 0 ? '?' . http_build_query($query_params) : '';
    }

    /**
     * Redirect to Referer (back)
     *
     */
    protected function redirectToReferer(){
        if (empty($_SERVER['HTTP_REFERER']) || fnmatch('*/auth/login', $_SERVER['HTTP_REFERER'])){
            header('Location: /');
        } else {
            $redirect_to = $_SERVER['HTTP_REFERER'];
            header("Location: $redirect_to");
        }

        // Be sure to stop script and not make changes after redirecting
        exit();
    }
}
