<?php

namespace App\Controllers;

use Core\Components\Alert;
use Core\Components\AlertType;
use \Core\View;

/**
 * Task controller
 *
 * PHP version 7.0
 */
class Task extends Base
{

    /**
     * Add task action
     *
     * @return void
     */
    public function addAction()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            // Validating values
            if (empty($_POST['username']) || empty($_POST['email']) || empty($_POST['content'])){
                $this->alerts['invalid_values'] = new Alert(
                    AlertType::DANGER,
                    'Введите правильность введенных данных',
                    true
                );
            } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                $this->alerts['invalid_email'] = new Alert(
                    AlertType::DANGER,
                    'Введите валидный email',
                    true
                );
            } else {
                $username = $_POST['username'];
                $email = $_POST['email'];
                $content = $_POST['content'];

                $result = \App\Models\Task::addItem($username, $email, $content);

                // In case of successful adding task, redirect to the home page to show added task
                if ($result) {
                    $_SESSION['alert_success'] = 'Новая задача успешно добавлена';
                    header("Location: /");
                    exit();
                } else {
                    $this->alerts['undefied_error'] = new Alert(
                        AlertType::DANGER,
                        'Что-то пошло не так',
                        true
                    );

                    View::renderTemplate('Task/add.html', $this->view_data);
                }
            }
        } else {
            View::renderTemplate('Task/add.html', $this->view_data);
        }
    }

    /**
     * Edit task action
     *
     * @return void
     */
    public function editAction()
    {
        // Redirect to the login page if user is not logged in yet
        if (!$this->is_logged_in){
            header("Location: /auth/login");
            exit();
        }

        $id = (isset($this->params['id']) ? intval($this->params['id']) : null);

        $task = \App\Models\Task::getItem($id);

        // Show 404 page if task is not found from database
        if ($task === false){
            View::renderTemplate('404.html', $this->view_data);
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST'){

            // Values validation
            if (empty($_POST['content'])){
                $this->alerts['empty_content'] = new Alert(
                    AlertType::DANGER,
                    'Введите текст задачи',
                    true
                );
            } else {
                $content = trim($_POST['content']);
                $status = (!empty($_POST['status']) ? intval($_POST['status']) : 0);

                $is_edited = $task->is_edited ? 1 : ($task->content !== $content);

                $result = \App\Models\Task::editItem($id, $content, $status, $is_edited);

                // In case of successful editing of the task, redirect to the home page
                if ($result) {
                    header("Location: /");
                    exit();
                } else {
                    $this->alerts['undefied_error'] = new Alert(
                        AlertType::DANGER,
                        'Что-то пошло не так',
                        true
                    );
                }
            }
        }

        $this->view_data = array_merge($this->view_data, array(
            'task' => $task
        ));
        View::renderTemplate('Task/edit.html', $this->view_data);
    }
}
