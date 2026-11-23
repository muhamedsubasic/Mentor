<?php
use OpenApi\Attributes as OA;

class CurrencyPaths
{
    #[OA\Get(
        path: '/currency/convert',
        tags: ['currency'],
        summary: 'Convert an amount between two currencies (external ExchangeRate API)',
        description: 'The API key is server-side; the client never sees it. Fails with 503 when the upstream is unreachable, times out, or returns invalid data.',
        parameters: [
            new OA\Parameter(
                name: 'amount',
                in: 'query',
                required: true,
                description: 'Amount to convert (>= 0)',
                example: 199.99,
                schema: new OA\Schema(type: 'number', format: 'float', minimum: 0)
            ),
            new OA\Parameter(
                name: 'from',
                in: 'query',
                required: true,
                description: '3-letter source currency code',
                example: 'EUR',
                schema: new OA\Schema(type: 'string', minLength: 3, maxLength: 3)
            ),
            new OA\Parameter(
                name: 'to',
                in: 'query',
                required: true,
                description: '3-letter target currency code',
                example: 'BAM',
                schema: new OA\Schema(type: 'string', minLength: 3, maxLength: 3)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Conversion result',
                content: new OA\JsonContent(ref: '#/components/schemas/CurrencyConversion')
            ),
            new OA\Response(
                response: 400,
                description: 'Missing or invalid parameters',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            ),
            new OA\Response(
                response: 503,
                description: 'External currency service unavailable, timed out, or returned invalid data',
                content: new OA\JsonContent(ref: '#/components/schemas/Error')
            )
        ]
    )]
    public function getCurrencyConvert() {}
}