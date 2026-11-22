<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/CategoryDao.php';
require_once __DIR__ . '/../dao/CourseDao.php';

class CategoryService extends BaseService
{
    protected $entity_name = 'Category';
    private $course_dao;

    /** DAOs are injectable (unit tests); by default real DAOs are used. */
    public function __construct($dao = null, $course_dao = null)
    {
        parent::__construct($dao ?? new CategoryDao());
        $this->course_dao = $course_dao ?? new CourseDao();
    }

    public function add($category)
    {
        return parent::add($this->normalize($category));
    }

    public function update($category, $id)
    {
        return parent::update($this->normalize($category), $id);
    }

    /** Rule: a category that still contains courses cannot be deleted. */
    public function delete($id)
    {
        $this->get_by_id($id);
        $courses = $this->course_dao->count_by_category($id);
        if ($courses > 0) {
            throw new ValidationException(
                "This category still contains $courses course(s). Move or delete them first."
            );
        }
        $this->dao->delete($id);
    }

    protected function validate($category, $id = null)
    {
        Validator::required($category, ['name']);
        Validator::length($category['name'], 'name', 2, 100);

        // Rule: category names are unique
        $existing = $this->dao->get_by_name(trim($category['name']));
        if ($existing && $existing['id'] != $id) {
            throw new ValidationException(
                "A category named '{$category['name']}' already exists."
            );
        }
    }

    // Trim the name so the stored value matches what validate() compares.
    private function normalize($category)
    {
        if (isset($category['name'])) {
            $category['name'] = trim($category['name']);
        }
        return $category;
    }
}