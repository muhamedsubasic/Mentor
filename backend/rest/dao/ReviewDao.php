<?php
require_once __DIR__ . '/BaseDao.php';

class ReviewDao extends BaseDao
{
    private const SELECT = "
        SELECT r.*,
               CONCAT(u.first_name, ' ', u.last_name) AS user_name,
               c.name AS course_name
        FROM reviews r
        JOIN users u ON u.id = r.user_id
        JOIN courses c ON c.id = r.course_id";

    public function __construct()
    {
        parent::__construct('reviews', ['user_id', 'course_id', 'rating', 'comment']);
    }

    public function get_all()
    {
        return $this->query(self::SELECT . " ORDER BY r.created_at DESC, r.id DESC");
    }

    public function get_by_id($id)
    {
        return $this->query_unique(self::SELECT . " WHERE r.id = :id", ['id' => $id]);
    }

    public function get_by_course($course_id)
    {
        return $this->query(self::SELECT . " WHERE r.course_id = :course_id ORDER BY r.created_at DESC, r.id DESC", ['course_id' => $course_id]);
    }

    public function get_by_user_and_course($user_id, $course_id)
    {
        return $this->query_unique(
            "SELECT * FROM reviews WHERE user_id = :user_id AND course_id = :course_id",
            ['user_id' => $user_id, 'course_id' => $course_id]
        );
    }

    public function count_by_user($user_id)
    {
        return (int) $this->query_unique("SELECT COUNT(*) AS total FROM reviews WHERE user_id = :id", ['id' => $user_id])['total'];
    }
}