<?php

namespace Core;

use Exception;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * View
 *
 * PHP version 7.0
 */
class View
{

    /**
     * Render a view file
     *
     * @param string $view The view file
     * @param array $args Associative array of data to display in the view (optional)
     *
     * @return void
     * @throws Exception
     */
    public static function render($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = dirname(__DIR__) . "/App/Views/$view";  // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            throw new Exception("$file not found");
        }
    }

    /**
     * Render a view template using Twig
     *
     * @param string $template The template file
     * @param array $args Associative array of data to display in the view (optional)
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public static function renderTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig\Loader\Filesystemloader(dirname(__DIR__) . '/App/Views');
            $twig = new \Twig\Environment($loader);
        }

        echo $twig->render($template, $args);
    }
}
