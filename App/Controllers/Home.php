<?php

namespace App\Controllers;

use App\Models\Task;
use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends Base
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $page = (isset($this->params['pagenumber']) ? intval($this->params['pagenumber']) : 1);
        $sort_by = (isset($_GET['sort_by']) ? trim($_GET['sort_by']): null);
        $sort_type = (isset($_GET['sort_type']) ? trim($_GET['sort_type']): null);

        // Pagination object with task items
        $all_tasks_pagination = Task::getItemsPagination($page, 3, $sort_by, $sort_type);

        $this->view_data = array_merge($this->view_data, array(
            'tasks_pagination' => $all_tasks_pagination,
            'sort_by'          => $sort_by,
            'sort_type'        => $sort_type
        ));

        View::renderTemplate('Home/index.html', $this->view_data);
    }
}
