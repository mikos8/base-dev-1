<?php


namespace models\todo\tasks;

use models\Database;
use PDO;
use PDOException;

class TagsModel {

    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();

        try {
            $result = $this->db->query("SELECT 1 FROM `tags` LIMIT 1");
        } catch (PDOException $error) {
            $this->createTable();
        }
    }

    public function createTable(): bool
    {
        $tagsTableQuery = "CREATE TABLE IF NOT EXISTS `tags` (
            `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `user_id` INT,
            `name` VARCHAR(255) NOT NULL,
             FOREIGN KEY (user_id) REFERENCES users(id));
            
            CREATE TABLE IF NOT EXISTS `task_tags`(
            `id` INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
            `task_id` INT,
            `tag_id` INT,
            FOREIGN KEY (task_id) REFERENCES tasks(id) ON DELETE SET NULL, 
            FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE SET NULL )";

        $this->db->beginTransaction();
        try {

            $this->db->exec($tagsTableQuery);
            $this->db->commit();
            return true;
        } catch (PDOException $err) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getAllTags()
    {
        $query = "SELECT * FROM `tags`";

        try {
            $stmnt = $this->db->query("SELECT * FROM `tags`");
            $tasks = [];
            while ($row = $stmnt->fetch(PDO::FETCH_ASSOC)) {
                $tasks[] = $row;
            }

            return $tasks;
        } catch (PDOException $e) {

        }
    }

    public function getTagsById($id): bool|array
    {
        $query = "SELECT tags.* FROM tags 
                JOIN task_tags ON tags.id = task_tags.tag_id
                WHERE task_tags.task_id = :id
        ";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            $tags = $stmt->fetch(PDO::FETCH_ASSOC);

            return $tags;

        } catch (PDOException $err) {
            return false;
        }
    }
    public function getTagsByTaskId($id): bool|array
    {
        $query = "SELECT * FROM tags WHERE id=?";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);
            $tags = $stmt->fetch(PDO::FETCH_ASSOC);

            return $tags;

        } catch (PDOException $err) {
            return false;
        }
    }

    public function createTag($title, $description, $user_id)
    {
        $category_id = 1;
        var_dump('model create task', $user_id);
        echo '<br />';
        var_dump('model create title:', $title);
        echo '<br />';
        var_dump('model create description', $description);
        echo '<br />';
        echo '<br />';
        echo '<br />';
        $query = "INSERT INTO tags (title,description, user_id, category_id,status,priority) VALUES (?,?,?,?,?,?)";
        try {
            $stmt = $this->db->prepare($query);
            $res = $stmt->execute([$title, $description, $user_id,$category_id,'new','low']);
            var_dump('res', $res);
            echo '<br />';
            return true;
        } catch (PDOException $e) {

            var_dump('error->', $e);
            exit();
        }
    }

    public function updateTag($id, $title, $description, $status, $priority,$category_id,$finish_date)
    {
        $query = "UPDATE tags SET title=?,description=?,status=?, priority=?, category_id=?,finish_date=? WHERE id=?";


        try {
            $stmnt = $this->db->prepare($query);
            $stmnt->execute([$title, $description, $status,$priority,$category_id,$finish_date, $id]);
            return true;
        } catch (PDOException $e) {
            var_dump($e);
            exit();
        }
    }

    public function deleteTag($id): bool
    {
        $query = "DELETE FROM tags WHERE id= ?";

        try {
            $stmt = $this->db->prepare($query);
            $stmt->execute([$id]);

            return true;
        } catch (PDOException $error) {
            return false;
        }
    }
}