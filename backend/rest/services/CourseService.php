<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/CourseDao.php';
require_once __DIR__ . '/../dao/CategoryDao.php';
require_once __DIR__ . '/../dao/TrainerDao.php';
require_once __DIR__ . '/../dao/PurchaseDao.php';

class CourseService extends BaseService
{
    protected $entity_name = 'Course';
    private $category_dao;
    private $trainer_dao;
    private $purchase_dao;

    public function __construct($dao = null, $category_dao = null, $trainer_dao = null, $purchase_dao = null)
    {
        parent::__construct($dao ?? new CourseDao());
        $this->category_dao = $category_dao ?? new CategoryDao();
        $this->trainer_dao = $trainer_dao ?? new TrainerDao();
        $this->purchase_dao = $purchase_dao ?? new PurchaseDao();
    }

    public function add($course)
    {
        return parent::add($this->normalize($course));
    }

    public function update($course, $id)
    {
        return parent::update($this->normalize($course), $id);
    }

    /**
     * Rule: a course that has been purchased cannot be deleted (financial history).
     * Reviews cascade automatically via the schema's ON DELETE CASCADE.
     */
    public function delete($id)
    {
        $this->get_by_id($id); // 404 if missing
        $purchases = $this->purchase_dao->count_by_course($id);
        if ($purchases > 0) {
            throw new ValidationException(
                "This course has $purchases purchase(s) and cannot be deleted."
            );
        }
        $this->dao->delete($id);
    }

    public function get_by_category($category_id)
    {
        return $this->dao->get_by_category($category_id);
    }

    public function get_by_trainer($trainer_id)
    {
        return $this->dao->get_by_trainer($trainer_id);
    }

    public function search($keyword)
    {
        return $this->dao->search_by_name($keyword);
    }

    protected function validate($course, $id = null)
    {
        Validator::required($course, ['category_id', 'trainer_id', 'name', 'price']);
        Validator::length($course['name'], 'name', 2, 150);
        Validator::decimal($course['price'], 'price', 0);   

        if (!$this->category_dao->get_by_id($course['category_id'])) {
            throw new ValidationException("Category with id {$course['category_id']} does not exist.");
        }
        if (!$this->trainer_dao->get_by_id($course['trainer_id'])) {
            throw new ValidationException("Trainer with id {$course['trainer_id']} does not exist.");
        }
    }

    private function normalize($course)
    {
        if (isset($course['name'])) {
            $course['name'] = trim($course['name']);
        }
        return $course;
    }
}