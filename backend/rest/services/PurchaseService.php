<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/PurchaseDao.php';
require_once __DIR__ . '/../dao/UserDao.php';
require_once __DIR__ . '/../dao/CourseDao.php';

class PurchaseService extends BaseService
{
    protected $entity_name = 'Purchase';
    private $user_dao;
    private $course_dao;

    //DAOs injectable (unit tests); by default real DAOs are used.
    public function __construct($dao = null, $user_dao = null, $course_dao = null)
    {
        parent::__construct($dao ?? new PurchaseDao());
        $this->user_dao = $user_dao ?? new UserDao();
        $this->course_dao = $course_dao ?? new CourseDao();
    }

    public function add($purchase)
    {
        return parent::add($this->prepare($purchase));
    }

    public function update($purchase, $id)
    {
        throw new ForbiddenException("Purchases cannot be updated. Refund and re-create instead.");
    }

    public function get_by_user($user_id)
    {
        return $this->dao->get_by_user($user_id);
    }

    public function has_purchased($user_id, $course_id)
    {
        return $this->dao->has_purchased($user_id, $course_id);
    }

    /** Admin dashboard: total money collected. */
    public function get_total_revenue()
    {
        return $this->dao->get_total_revenue();
    }

    //Server-controlled fields: amount (from course.price) and purchased_at (DB default).
    //Client-sent values for those keys are discarded — prevents price manipulation.
    private function prepare($purchase)
    {
        unset($purchase['purchased_at']);

        if (isset($purchase['course_id'])) {
            $course = $this->course_dao->get_by_id($purchase['course_id']);
            if ($course) {
                $purchase['amount'] = $course['price'];
            }
        }
        return $purchase;
    }

    protected function validate($purchase, $id = null)
    {
        Validator::required($purchase, ['user_id', 'course_id']);

        if (!$this->user_dao->get_by_id($purchase['user_id'])) {
            throw new ValidationException(
                "User with id {$purchase['user_id']} does not exist."
            );
        }

        if (!$this->course_dao->get_by_id($purchase['course_id'])) {
            throw new ValidationException(
                "Course with id {$purchase['course_id']} does not exist."
            );
        }

        if (!isset($purchase['amount'])) {
            throw new ValidationException("Could not determine purchase amount from course price.");
        }

        // Rule: one purchase per user per course
        if ($this->dao->has_purchased($purchase['user_id'], $purchase['course_id'])) {
            throw new ValidationException("This user has already purchased this course.");
        }
    }
}