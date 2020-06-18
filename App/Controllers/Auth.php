<?php

namespace App\Controllers;

use Core\Components\Alert;
use Core\Components\AlertType;
use \Core\View;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

/**
 * Auth controller
 *
 * PHP version 7.0
 */
class Auth extends Base
{

    /**
     * Show the login page
     *
     * @return void
     * @throws LoaderError
     * @throws RuntimeError
     * @throws SyntaxError
     */
    public function loginAction()
    {
        // If the user is already logged in, redirect to referer
        if ($this->view_data['isLoggedIn']){
            $this->redirectToReferer();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            if (empty($_POST['userName']) || empty($_POST['password'])){
                $this->alerts['empty_credentials'] = new Alert(
                    AlertType::DANGER,
                    'Введите имя пользователя или пароль',
                    true
                );
            } else {
                $user_name = $_POST['userName'];
                $password = $_POST['password'];

                if (trim($user_name) == 'admin' && trim($password) == '123'){
                    $_SESSION['isLoggedIn'] = true;

                    $_SESSION['alert_success'] = 'Вы успешно вошли как администратор';
                    $this->redirectToReferer();
                } else {
                    $this->alerts['invalid_credentials'] = new Alert(
                        AlertType::DANGER,
                        'Ведите правильные данные',
                        true
                    );
                }
            }
        }

        // Show the login template
        View::renderTemplate('Auth/login.html', $this->view_data);
    }

    /**
     * Action for logging out. Redirects to referer after successful cleaning session
     *
     * @return void
     */
    public function logoutAction()
    {
        if(isset($_SESSION['isLoggedIn']))
            unset($_SESSION['isLoggedIn']);

        $this->redirectToReferer();
    }
}
