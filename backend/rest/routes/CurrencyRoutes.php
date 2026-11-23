<?php

/**
 * PRESENTATION LAYER – currency conversion (external API).
 * Errors (400 for bad input, 503 for upstream down) come from CurrencyService
 * and are turned into JSON by Flight::map('error') in index.php.
 */

Flight::route('GET /currency/convert', function () {
    $q = Flight::request()->query;

    Flight::json(
        Flight::currency_service()->convert(
            $q['amount'] ?? null,
            $q['from']   ?? null,
            $q['to']     ?? null
        )
    );
});