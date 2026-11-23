<?php
use OpenApi\Attributes as OA;

class PurchasePaths
{
    #[OA\Get(
        path: '/purchases',
        tags: ['purchases'],
        summary: 'List all purchases (newest first)',
        responses: [
            new OA\Response(response: 200, description: 'List of purchases',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Purchase')))
        ]
    )]
    public function getPurchases() {}

    #[OA\Get(
        path: '/purchases/revenue',
        tags: ['purchases'],
        summary: 'Total revenue from all purchases',
        responses: [
            new OA\Response(response: 200, description: 'Total revenue',
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: 'total', type: 'number', format: 'float', example: 577.49)]
                ))
        ]
    )]
    public function getPurchasesRevenue() {}

    #[OA\Get(
        path: '/purchases/user/{user_id}',
        tags: ['purchases'],
        summary: 'List purchases made by one user',
        parameters: [
            new OA\Parameter(name: 'user_id', in: 'path', required: true, example: 2,
                schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Purchases of the user (empty array if none)',
                content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: '#/components/schemas/Purchase')))
        ]
    )]
    public function getPurchasesByUser() {}

    #[OA\Get(
        path: '/purchases/{id}',
        tags: ['purchases'],
        summary: 'Get one purchase',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, example: 1,
                schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'The purchase',
                content: new OA\JsonContent(ref: '#/components/schemas/Purchase')),
            new OA\Response(response: 404, description: 'Purchase not found',
                content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ]
    )]
    public function getPurchasesById() {}

    #[OA\Post(
        path: '/purchases',
        tags: ['purchases'],
        summary: 'Create a purchase (amount is set server-side from courses.price)',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(ref: '#/components/schemas/PurchaseInput')
        ),
        responses: [
            new OA\Response(response: 201, description: 'Created purchase',
                content: new OA\JsonContent(ref: '#/components/schemas/Purchase')),
            new OA\Response(response: 400, description: 'Invalid data (unknown user_id or course_id, or user already purchased this course)',
                content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ]
    )]
    public function postPurchases() {}

    #[OA\Put(
        path: '/purchases/{id}',
        tags: ['purchases'],
        summary: 'Purchases are immutable – always returns 403',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, example: 1,
                schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 403, description: 'Purchases cannot be updated; refund and re-create instead',
                content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ]
    )]
    public function putPurchasesById() {}

    #[OA\Delete(
        path: '/purchases/{id}',
        tags: ['purchases'],
        summary: 'Delete (refund) a purchase',
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, example: 1,
                schema: new OA\Schema(type: 'integer'))
        ],
        responses: [
            new OA\Response(response: 200, description: 'Refunded',
                content: new OA\JsonContent(ref: '#/components/schemas/Message')),
            new OA\Response(response: 404, description: 'Purchase not found',
                content: new OA\JsonContent(ref: '#/components/schemas/Error'))
        ]
    )]
    public function deletePurchasesById() {}
}