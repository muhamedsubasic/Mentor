<?php
require_once __DIR__ . '/BaseService.php';
require_once __DIR__ . '/../dao/TrainerDao.php';
require_once __DIR__ . '/../dao/CourseDao.php';

class TrainerService extends BaseService
{
    protected $entity_name = 'Trainer';
    private $course_dao;

    // DAOs injectable (unit tests); by default real DAOs are used.
    public function __construct($dao = null, $course_dao = null)
    {
        parent::__construct($dao ?? new TrainerDao());
        $this->course_dao = $course_dao ?? new CourseDao();
    }

    public function add($trainer)
    {
        return parent::add($this->normalize($trainer));
    }

    public function update($trainer, $id)
    {
        return parent::update($this->normalize($trainer), $id);
    }

    // Rule: a trainer who is still assigned to courses cannot be deleted.
    public function delete($id)
    {
        $this->get_by_id($id); // 404 if missing
        $courses = $this->course_dao->count_by_trainer($id);
        if ($courses > 0) {
            throw new ValidationException(
                "This trainer is assigned to $courses course(s). Reassign or delete them first."
            );
        }
        $this->dao->delete($id);
    }

    public function get_by_specialty($specialty)
    {
        return $this->dao->get_by_specialty($specialty);
    }

    protected function validate($trainer, $id = null)
    {
        Validator::required($trainer, ['first_name', 'last_name']);
        Validator::length($trainer['first_name'], 'first_name', 2, 50);
        Validator::length($trainer['last_name'],  'last_name',  2, 50);

        if (!empty($trainer['specialty'])) {
            Validator::length($trainer['specialty'], 'specialty', 2, 100);
        }
    }

    private function normalize($trainer)
    {
        foreach (['first_name', 'last_name', 'specialty'] as $field) {
            if (isset($trainer[$field])) {
                $trainer[$field] = trim((string) $trainer[$field]);
            }
        }
        return $this->empty_to_null($trainer, ['specialty']);
    }
}