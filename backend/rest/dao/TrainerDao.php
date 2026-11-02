<?php
require_once __DIR__ . '/BaseDao.php';

class TrainerDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct('trainers', ['first_name', 'last_name', 'specialty']);
    }

    public function get_all()
    {
        return $this->query("SELECT * FROM trainers ORDER BY last_name, first_name");
    }

    public function get_by_specialty($specialty)
    {
        return $this->query("SELECT * FROM trainers WHERE specialty = :specialty", ['specialty' => $specialty]);
    }
}