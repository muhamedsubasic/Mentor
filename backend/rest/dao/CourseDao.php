<?php
require_once __DIR__ . '/BaseDao.php';

class CourseDao extends BaseDao
{
    private const SELECT = "
        SELECT c.*, 
               cat.name AS category_name,
               CONCAT(t.first_name, ' ', t.last_name) AS trainer_name
        FROM courses c
        JOIN categories cat ON cat.id = c.category_id
        JOIN trainers t ON t.id = c.trainer_id";

    public function __construct()
    {
        parent::__construct('courses', ['category_id', 'trainer_id', 'name', 'price']);
    }

    public function get_all()
    {
        return $this->query(self::SELECT . " ORDER BY c.name");
    }

    public function get_by_id($id)
    {
        return $this->query_unique(self::SELECT . " WHERE c.id = :id", ['id' => $id]);
    }

    public function get_by_category($category_id)
    {
        return $this->query(self::SELECT . " WHERE c.category_id = :category_id ORDER BY c.name", ['category_id' => $category_id]);
    }

    public function get_by_trainer($trainer_id)
    {
        return $this->query(self::SELECT . " WHERE c.trainer_id = :trainer_id ORDER BY c.name", ['trainer_id' => $trainer_id]);
    }

    public function search_by_name($keyword)
    {
        return $this->query(self::SELECT . " WHERE c.name LIKE :keyword ORDER BY c.name", ['keyword' => "%$keyword%"]);
    }
}