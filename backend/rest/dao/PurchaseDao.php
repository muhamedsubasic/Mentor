<?php
require_once __DIR__ . '/BaseDao.php';

class PurchaseDao extends BaseDao
{
    private const SELECT = "
        SELECT p.*, 
               CONCAT(u.first_name, ' ', u.last_name) AS user_name,
               u.email AS user_email,
               c.name AS course_name
        FROM purchases p
        JOIN users u ON u.id = p.user_id
        JOIN courses c ON c.id = p.course_id";

    public function __construct()
    {
        parent::__construct('purchases', ['user_id', 'course_id', 'amount', 'purchased_at']);
    }

    public function get_all()
    {
        return $this->query(self::SELECT . " ORDER BY p.purchased_at DESC");
    }

    public function get_by_id($id)
    {
        return $this->query_unique(self::SELECT . " WHERE p.id = :id", ['id' => $id]);
    }

    /** Get purchases made by a specific student */
    public function get_by_user($user_id)
    {
        return $this->query(self::SELECT . " WHERE p.user_id = :user_id ORDER BY p.purchased_at DESC", ['user_id' => $user_id]);
    }

    /** Check if a student already owns a specific course */
    public function has_purchased($user_id, $course_id)
    {
        $result = $this->query_unique(
            "SELECT id FROM purchases WHERE user_id = :user_id AND course_id = :course_id",
            ['user_id' => $user_id, 'course_id' => $course_id]
        );
        return $result !== null;
    }

    /** Get total earnings */
    public function get_total_revenue()
    {
        $result = $this->query_unique("SELECT SUM(amount) AS total FROM purchases");
        return (float) ($result['total'] ?? 0.00);
    }
}