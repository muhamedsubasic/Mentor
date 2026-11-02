<?php
require_once __DIR__ . '/BaseDao.php';

class CategoryDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('categories', ['name']);
    }

    /** All categories with the number of courses in each one. */
    public function get_all()
    {
        return $this->query(
            "SELECT c.*, (SELECT COUNT(*) FROM courses co WHERE co.category_id = c.id) AS course_count
             FROM categories c
             ORDER BY c.name"
        );
    }

    /** Find a category by its name. */
    public function get_by_name($name)
    {
        return $this->query_unique("SELECT * FROM categories WHERE name = :name", ['name' => $name]);
    }
}