<?php
/**
 * User roles – must match the ENUM of the users.role column.
 */
class Roles
{
    const ADMIN = 'admin';
    const STUDENT = 'student';
    const TRAINER = 'trainer';

    public static function all()
    {
        return [self::ADMIN, self::STUDENT, self::TRAINER];
    }
}