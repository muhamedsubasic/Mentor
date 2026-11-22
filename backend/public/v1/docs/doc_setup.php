<?php
use OpenApi\Attributes as OA;

/*
 * General API information + reusable schemas (referenced from the paths with ref: '#/components/schemas/...').
 * swagger-php 6 reads these PHP 8 attributes – the class itself is empty.
 * (Plain /* comments on purpose: swagger-php would turn a /** doc comment into a description.)
 */
#[OA\Info(
    title: 'MentorCourses API',
    description: 'REST API of MentorCourses – online course marketplace.',
    version: '1.0'
)]
#[OA\Server(url: API_BASE_URL, description: 'This server')]
#[OA\Schema(
    schema: 'Error',
    properties: [
        new OA\Property(property: 'error', type: 'string', example: 'Field \'name\' is required.')
    ]
)]
#[OA\Schema(
    schema: 'Message',
    properties: [new OA\Property(property: 'message', type: 'string', example: 'Course deleted.')]
)]
#[OA\Schema(
    schema: 'CategoryInput',
    required: ['name'],
    properties: [
        new OA\Property(property: 'name', type: 'string', example: 'Mobile Development')
    ]
)]
#[OA\Schema(
    schema: 'Category',
    allOf: [new OA\Schema(ref: '#/components/schemas/CategoryInput')],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'course_count', type: 'integer', example: 2)
    ]
)]
#[OA\Schema(
    schema: 'TrainerInput',
    required: ['first_name', 'last_name'],
    properties: [
        new OA\Property(property: 'first_name', type: 'string', example: 'Robert'),
        new OA\Property(property: 'last_name', type: 'string', example: 'Brown'),
        new OA\Property(
            property: 'specialty',
            type: 'string',
            example: 'Web Development',
            nullable: true,
            description: 'Optional – empty string is stored as NULL'
        )
    ]
)]
#[OA\Schema(
    schema: 'Trainer',
    allOf: [new OA\Schema(ref: '#/components/schemas/TrainerInput')],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time')
    ]
)]
#[OA\Schema(
    schema: 'CourseInput',
    required: ['category_id', 'trainer_id', 'name', 'price'],
    properties: [
        new OA\Property(property: 'category_id', type: 'integer', example: 1),
        new OA\Property(property: 'trainer_id', type: 'integer', example: 1),
        new OA\Property(property: 'name', type: 'string', example: 'Full-Stack Web Development'),
        new OA\Property(
            property: 'price',
            type: 'number',
            format: 'float',
            minimum: 0,
            example: 199.99,
            description: 'DECIMAL(10,2), must be >= 0'
        )
    ]
)]
#[OA\Schema(
    schema: 'Course',
    allOf: [new OA\Schema(ref: '#/components/schemas/CourseInput')],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'category_name', type: 'string', example: 'Programming'),
        new OA\Property(property: 'trainer_name', type: 'string', example: 'Robert Brown'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time')
    ]
)]
#[OA\Schema(
    schema: 'PurchaseInput',
    required: ['user_id', 'course_id'],
    description: 'Amount and purchased_at are set by the server – client values are ignored.',
    properties: [
        new OA\Property(property: 'user_id', type: 'integer', example: 2),
        new OA\Property(property: 'course_id', type: 'integer', example: 1)
    ]
)]
#[OA\Schema(
    schema: 'Purchase',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'user_id', type: 'integer', example: 2),
        new OA\Property(property: 'course_id', type: 'integer', example: 1),
        new OA\Property(property: 'amount', type: 'number', format: 'float', example: 199.99),
        new OA\Property(property: 'purchased_at', type: 'string', format: 'date-time'),
        new OA\Property(property: 'user_name', type: 'string', example: 'Lana Kovač'),
        new OA\Property(property: 'user_email', type: 'string', format: 'email', example: 'ema@mentorcourses.ba'),
        new OA\Property(property: 'course_name', type: 'string', example: 'Full-Stack Web Development')
    ]
)]
#[OA\Schema(
    schema: 'ReviewInput',
    required: ['user_id', 'course_id', 'rating'],
    description: 'User must have purchased the course before reviewing it.',
    properties: [
        new OA\Property(property: 'user_id', type: 'integer', example: 2),
        new OA\Property(property: 'course_id', type: 'integer', example: 1),
        new OA\Property(property: 'rating', type: 'integer', minimum: 1, maximum: 5, example: 5),
        new OA\Property(property: 'comment', type: 'string', example: 'Excellent course!', nullable: true)
    ]
)]
#[OA\Schema(
    schema: 'Review',
    allOf: [new OA\Schema(ref: '#/components/schemas/ReviewInput')],
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 1),
        new OA\Property(property: 'user_name', type: 'string', example: 'Lana Kovač'),
        new OA\Property(property: 'course_name', type: 'string', example: 'Full-Stack Web Development'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time')
    ]
)]
#[OA\Schema(
    schema: 'UserInput',
    required: ['first_name', 'last_name', 'email', 'role'],
    properties: [
        new OA\Property(property: 'first_name', type: 'string', example: 'Lana'),
        new OA\Property(property: 'last_name', type: 'string', example: 'Kovač'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'lana@mentorcourses.ba'),
        new OA\Property(
            property: 'password',
            type: 'string',
            format: 'password',
            example: 'Secret123',
            description: 'Required on create, optional on update (empty = keep existing)'
        ),
        new OA\Property(property: 'role', type: 'string', enum: ['admin', 'student', 'trainer'], example: 'student')
    ]
)]
#[OA\Schema(
    schema: 'User',
    properties: [
        new OA\Property(property: 'id', type: 'integer', example: 2),
        new OA\Property(property: 'first_name', type: 'string', example: 'Lana'),
        new OA\Property(property: 'last_name', type: 'string', example: 'Kovač'),
        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'ema@mentorcourses.ba'),
        new OA\Property(property: 'role', type: 'string', example: 'student'),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time')
    ]
)]
#[OA\Schema(
    schema: 'CurrencyConversion',
    properties: [
        new OA\Property(property: 'from', type: 'string', example: 'EUR'),
        new OA\Property(property: 'to', type: 'string', example: 'BAM'),
        new OA\Property(property: 'amount', type: 'number', format: 'float', example: 199.99),
        new OA\Property(property: 'rate', type: 'number', format: 'float', example: 1.9558),
        new OA\Property(property: 'result', type: 'number', format: 'float', example: 391.13),
        new OA\Property(
            property: 'updated_at',
            type: 'string',
            nullable: true,
            example: 'Mon, 25 Nov 2024 00:02:01 +0000',
            description: 'When the exchange rate was last updated upstream'
        )
    ]
)]
class OpenApiSpec {}