<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/ReviewDao.php';
require_once __DIR__ . '/../dao/CourseDao.php';
require_once __DIR__ . '/../dao/PurchaseDao.php';

class ReviewService extends BaseService
{
    protected $entity_name = 'Review';
    private $course_dao;
    private $purchase_dao;

    public function __construct($dao = null, $course_dao = null, $purchase_dao = null)
    {
        parent::__construct($dao ?? new ReviewDao());
        $this->course_dao = $course_dao ?? new CourseDao();
        $this->purchase_dao = $purchase_dao ?? new PurchaseDao();
    }

    public function get_by_course($course_id)
    {
        if (!$this->course_dao->get_by_id($course_id)) {
            throw new NotFoundException("Course not found.");
        }
        return $this->dao->get_by_course($course_id);
    }

    public function add($review)
    {
        Validator::required($review, ['user_id', 'course_id']);
        
        if (!$this->course_dao->get_by_id($review['course_id'])) {
            throw new ValidationException("The selected course does not exist.");
        }

        if (!$this->purchase_dao->has_purchased($review['user_id'], $review['course_id'])) {
            throw new ValidationException("You must purchase this course before leaving a review.");
        }

        if ($this->dao->get_by_user_and_course($review['user_id'], $review['course_id'])) {
            throw new ValidationException("You have already reviewed this course. Edit your existing review instead.");
        }
        
        return parent::add($this->empty_to_null($review, ['comment']));
    }

    public function update($review, $id)
    {
        $review = $this->empty_to_null($review, ['comment']);
        $changes = ['rating' => $review['rating'] ?? null, 'comment' => $review['comment'] ?? null];
        return parent::update($changes, $id);
    }

    protected function validate($review, $id = null)
    {
        Validator::required($review, ['rating']);
        Validator::integer($review['rating'], 'rating', 1, 5);
        if (!empty($review['comment'])) {
            Validator::length($review['comment'], 'comment', 0, 1000);
        }
    }
}