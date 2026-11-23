<?php
use OpenApi\Attributes as OA;

class TrainerPaths
{
    #[OA\Get(
        path: '/trainers',
        tags: ['trainers'],
        summary: 'List all trainers',
        responses: [
            new OA\Response(response: 200, description: 'List of trainers',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Trainer')))
        ]
    )]
    public function getTrainers() {}

    #[OA\Get(
        path: '/trainers/specialty/{specialty}',
        tags: ['trainers'],
        summary: 'Find trainers by specialty (exact match, case-insensitive)',
        parameters: [
            new OA\Parameter(name: 'specialty', in: 'path', required: true,
                description: 'e.g. "Web Development" – URL-encoded spaces',
                example: 'Web Development',
                schema: new OA\Schema(type: 'string'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Matching trainers (empty array if none)',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Trainer')))
        ]
    )]
    public function getTrainersBySpecialty() {}

    #[OA\Get(
        path: '/trainers/{id}',
        tags: ['trainers'],
        summary: 'Get one trainer',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, example: 1,
                schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'The trainer',
                content: new OA\JsonContent(ref: '#/components/schemas/Trainer')),
            new OA\Response(response: 404, description: 'Trainer not found',
                content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ]
    )]
    public function getTrainersById() {}

    #[OA\Post(
        path: '/trainers',
        tags: ['trainers'],
        summary: 'Create a trainer',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/TrainerInput')
        ),
        responses: [
            new OA\Response(response: 201, description: 'Created trainer',
                content: new OA\JsonContent(ref: '#/components/schemas/Trainer')),
            new OA\Response(response: 400, description: 'Invalid data (missing name, name too short/long)',
                content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ]
    )]
    public function postTrainers() {}

    #[OA\Put(
        path: '/trainers/{id}',
        tags: ['trainers'],
        summary: 'Update a trainer',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, example: 1,
                schema: new OA\Schema(type: 'integer'))
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/TrainerInput')
        ),
        responses: [
            new OA\Response(response: 200, description: 'Updated trainer',
                content: new OA\JsonContent(ref: '#/components/schemas/Trainer')),
            new OA\Response(response: 400, description: 'Invalid data',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 404, description: 'Trainer not found',
                content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ]
    )]
    public function putTrainersById() {}

    #[OA\Delete(
        path: '/trainers/{id}',
        tags: ['trainers'],
        summary: 'Delete a trainer (only if no courses are assigned)',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, example: 1,
                schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Deleted',
                content: new OA\JsonContent(ref: '#/components/schemas/Message')),
            new OA\Response(response: 400, description: 'Trainer is assigned to courses and cannot be deleted',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')),
            new OA\Response(response: 404, description: 'Trainer not found',
                content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ]
    )]
    public function deleteTrainersById() {}
}