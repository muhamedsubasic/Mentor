<?php
use OpenApi\Attributes as OA;

class CoursePaths
{
    #[OA\Get(
        path: '/courses',
        tags: ['courses'],
        summary: 'List all courses',
        responses: [
            new OA\Response(response: 200, description: 'List of courses',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Course')))
        ]
    )]
    public function getCourses() {}

    #[OA\Get(
        path: '/courses/search',
        tags: ['courses'],
        summary: 'Search courses by name (LIKE %q%)',
        parameters: [
            new OA\Parameter(name: 'q', in: 'query', required: false,
                description: 'Search keyword; empty or missing returns all courses',
                example: 'python',
                schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Matching courses',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Course')))
        ]
    )]
    public function getCoursesSearch() {}

    #[OA\Get(
        path: '/courses/category/{category_id}',
        tags: ['courses'],
        summary: 'List courses in one category',
        parameters: [
            new OA\Parameter(name: 'category_id', in: 'path', required: true, example: 1,
                schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Courses in the category (empty array if none)',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Course')))
        ]
    )]
    public function getCoursesByCategory() {}

    #[OA\Get(
        path: '/courses/trainer/{trainer_id}',
        tags: ['courses'],
        summary: 'List courses taught by one trainer',
        parameters: [
            new OA\Parameter(name: 'trainer_id', in: 'path', required: true, example: 1,
                schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Courses taught by the trainer (empty array if none)',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Course')))
        ]
    )]
    public function getCoursesByTrainer() {}

    #[OA\Get(
        path: '/courses/{id}/reviews',
        tags: ['courses', 'reviews'],
        summary: 'List reviews of one course',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, example: 1,
                schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Reviews of the course (empty array if it has none)',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Review'))),
            new OA\Response(response: 404, description: 'Course not found',
                content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ]
    )]
    public function getCoursesByIdReviews() {}

    #[OA\Get(
        path: '/courses/{id}',
        tags: ['courses'],
        summary: 'Get one course',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, example: 1,
                schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'The course',
                content: new OA\JsonContent(ref: '#/components/schemas/Course')),
            new OA\Response(response: 404, description: 'Course not found',
                content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ]
    )]
    public function getCoursesById() {}

    #[OA\Post(
        path: '/courses',
        tags: ['courses'],
        summary: 'Create a course',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/CourseInput')
        ),
        responses: [
            new OA\Response(response: 201, description: 'Created course',
                content: new OA\JsonContent(ref: '#/components/schemas/Course')),
            new OA\Response(response: 400, description: 'Invalid data (missing field, bad price, unknown category_id or trainer_id)',
                content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ]
    )]
    public function postCourses() {}

    #[OA\Put(
        path: '/courses/{id}',
        tags: ['courses'],
        summary: 'Update a course',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, example: 1,
                schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/CourseInput')
        ),
        responses: [
            new OA\Response(response: 200, description: 'Updated course',
                content: new OA\JsonContent(ref: '#/components/schemas/Course')),
            new OA\Response(response: 400, description: 'Invalid data',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 404, description: 'Course not found',
                content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ]
    )]
    public function putCoursesById() {}

    #[OA\Delete(
        path: '/courses/{id}',
        tags: ['courses'],
        summary: 'Delete a course (only if it has no purchases; reviews cascade)',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, example: 1,
                schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Deleted',
                content: new OA\JsonContent(ref: '#/components/schemas/Message')),
            new OA\Response(response: 400, description: 'Course has purchases and cannot be deleted',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 404, description: 'Course not found',
                content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ]
    )]
    public function deleteCoursesById() {}
}