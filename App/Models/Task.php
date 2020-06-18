<?php

namespace App\Models;

use Core\Components\Pagination;
use Core\Model;
use PDO;
use PDOException;
use stdClass;

/**
 * Task model
 *
 * PHP version 7.0
 */
class Task extends Model
{

    /**
     * Get tasks with pagination object
     *
     * @param int $page_number Current page number
     * @param int $items_per_page Items per page
     * @param null $sort_by Sort by columns (username / email / status)
     * @param null $sort_type Sort type (ASC / DESC)
     * @return Pagination Returns Pagination object
     */
    public static function getItemsPagination($page_number = 1, $items_per_page = 3, $sort_by = null, $sort_type = null)
    {
        // Initialize Database PDO connection
        $db = static::getDB();

        // Get all number of tasks, needed for pagination
        $stmtAll = $db->query("SELECT COUNT(*) FROM tasks");
        $rows_count = intval($stmtAll->fetchColumn());

        // Validating fields in case of MYSQL injection
        $available_sorting_columns = ['username', 'email', 'status'];
        if (!is_null($sort_by)){
            if (!in_array($sort_by, $available_sorting_columns)){
                throw new PDOException('Sorting field is invalid', 12);
            }

            $available_sorting_types = ['ASC', 'DESC'];
            if (!is_null($sort_type) && !in_array($sort_type, $available_sorting_types)){
                throw new PDOException('Sorting type is invalid', 13);
            }
        }

        $start_at = $page_number * $items_per_page - $items_per_page;

        // Check is page number valid, if not reset page number
        if ($start_at > $rows_count){
            $page_number = 1;
            $start_at = 0;
        }

        $order_by = (!is_null($sort_by)) ? "`$sort_by` $sort_type" : '`id` DESC';
        $stmtRows = $db->query("SELECT * FROM `tasks` ORDER BY $order_by LIMIT $start_at, $items_per_page");
        $rows = $stmtRows->fetchAll(PDO::FETCH_OBJ);

        // Initialize and return Pagination object
        return new Pagination($rows, $rows_count, $page_number, $items_per_page);
    }

    /**
     * Add task row
     *
     * @param string $username Username of task
     * @param string $email Email
     * @param string $content Text content of Task
     * @return bool Returns true in case of successful inserting, false when there we have a trouble in executing of query.
     */
    public static function addItem($username, $email, $content)
    {
        // Initialize Database PDO connection
        $db = static::getDB();

        // Task row insertion to the Database
        $stmt = $db->prepare('INSERT INTO `tasks` (`username`, `email`, `content`) VALUES (:username, :email, :content)');
        return $stmt->execute(array(
            ':username' => $username,
            ':email' => $email,
            ':content' => $content
        ));
    }

    /**
     * Edit task row
     *
     * @param int $id Id of task
     * @param string $content Text content of Task
     * @param int $status Status of task
     * @param int $is_edited IsEdited mark of task
     * @return bool Returns true in case of successful inserting, false when there we have a trouble in executing of query.
     */
    public static function editItem($id, $content, $status, $is_edited)
    {
        // Initialize Database PDO connection
        $db = static::getDB();

        // Task updating of Database row
        $stmt = $db->prepare('UPDATE `tasks` SET `content` = :content, status = :status, `is_edited` = :is_edited WHERE `id` = :id');
        return $stmt->execute(array(
            ':content' => $content,
            ':status' => $status,
            ':is_edited' => $is_edited,
            ':id' => $id
        ));
    }

    /**
     * Edit task row
     *
     * @param int $id Id of task
     * @return stdClass|bool Returns stdObject of row, false when no task by id found or there we have a trouble in executing of query.
     */
    public static function getItem($id)
    {
        // Initialize Database PDO connection
        $db = static::getDB();

        // Get all number of tasks, needed for pagination
        $stmt = $db->prepare('SELECT * FROM `tasks` WHERE `id` = :id');
        $stmt->execute(array(
            ':id' => $id
        ));
        return $stmt->fetchObject();
    }
}
