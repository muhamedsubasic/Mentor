<?php
use OpenApi\Attributes as OA;

class ReviewPaths
{
    #[OA\Get(
        path: '/reviews',
        tags: ['reviews'],
        summary: 'List all reviews (newest first)',
        responses: [
            new OA\Response(
                response: 200,
                description: 'List of reviews',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Review'))
            )
        ]
    )]
    public function getReviews() {}

    #[OA\Get(
        path: '/reviews/course/{course_id}',
        tags: ['reviews', 'courses'],
        summary: 'List reviews of one course',
        parameters: [
            new OA\Parameter(
                name: 'course_id',
                in: 'path',
                required: true,
                example: 1,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Reviews of the course (empty array if the course has none)',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Review'))
            ),
            new OA\Response(
                response: 404,
                description: 'Course not found',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            )
        ]
    )]
    public function getReviewsByCourse() {}

    #[OA\Get(
        path: '/reviews/{id}',
        tags: ['reviews'],
        summary: 'Get one review',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                example: 1,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'The review',
                content: new OA\JsonContent(ref: '#/components/schemas/Review')
            ),
            new OA\Response(
                response: 404,
                description: 'Review not found',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            )
        ]
    )]
    public function getReviewsById() {}

    #[OA\Post(
        path: '/reviews',
        tags: ['reviews'],
        summary: 'Write a review (one per student per course; the student must have purchased the course)',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/ReviewInput')
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Created review',
                content: new OA\JsonContent(ref: '#/components/schemas/Review')
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid data (missing field, rating outside 1–5, unknown user or course, course not purchased, or already reviewed)',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            )
        ]
    )]
    public function postReviews() {}

    #[OA\Put(
        path: '/reviews/{id}',
        tags: ['reviews'],
        summary: 'Update rating and comment (user_id and course_id cannot be changed)',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                example: 1,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['rating'],
                properties: [
                    new OA\Property(property: 'rating', type: 'integer', minimum: 1, maximum: 5, example: 4),
                    new OA\Property(property: 'comment', type: 'string', example: 'Changed my mind.')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Updated review',
                content: new OA\JsonContent(ref: '#/components/schemas/Review')
            ),
            new OA\Response(
                response: 400,
                description: 'Invalid data (rating outside 1–5)',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 404,
                description: 'Review not found',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            )
        ]
    )]
    public function putReviewsById() {}

    #[OA\Delete(
        path: '/reviews/{id}',
        tags: ['reviews'],
        summary: 'Delete a review',
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                example: 1,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Deleted',
                content: new OA\JsonContent(ref: '#/components/schemas/Message')
            ),
            new OA\Response(
                response: 404,
                description: 'Review not found',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            )
        ]
    )]
    public function deleteReviewsById() {}
}