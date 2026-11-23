<?php
require_once __DIR__ . '/../../utils/exceptions.php';
require_once __DIR__ . '/../../utils/Validator.php';
require_once __DIR__ . '/../../config/config.php';

/**
 * External service wrapper for ExchangeRate-API v6.
 *
 * No DAO, no BaseService: this service only translates between API and a
 * external HTTP endpoint. Input problems → ValidationException (400).
 * Upstream problems → ExternalServiceException (503), never a 500.
 */

class CurrencyService
{
    private const TIMEOUT_CONNECT = 3;  
    private const TIMEOUT_TOTAL   = 5;   

    public function convert($amount, $from, $to)
    {
        if ($amount === null || $from === null || $to === null) {
            throw new ValidationException("Query parameters 'amount', 'from' and 'to' are required.");
        }
        Validator::decimal($amount, 'amount', 0);

        $from = strtoupper(trim((string) $from));
        $to   = strtoupper(trim((string) $to));

        if (!preg_match('/^[A-Z]{3}$/', $from)) {
            throw new ValidationException("Field 'from' must be a 3-letter currency code (e.g. EUR).");
        }
        if (!preg_match('/^[A-Z]{3}$/', $to)) {
            throw new ValidationException("Field 'to' must be a 3-letter currency code (e.g. USD).");
        }
        if ($from === $to) {
            return [
                'from'       => $from,
                'to'         => $to,
                'amount'     => (float) $amount,
                'rate'       => 1.0,
                'result'     => (float) $amount,
                'updated_at' => null,
            ];
        }

        $url = rtrim(Config::EXCHANGE_API_BASE(), '/')
             . '/' . Config::EXCHANGE_API_KEY()
             . '/pair/' . $from . '/' . $to . '/' . urlencode($amount);

        $data = $this->call($url);

        
        if (($data['result'] ?? '') !== 'success') {
            $reason = $data['error-type'] ?? ($data['result'] ?? 'unknown error');
            throw new ExternalServiceException(
                "Currency service could not convert $from to $to: $reason."
            );
        }

        return [
            'from'       => $from,
            'to'         => $to,
            'amount'     => (float) $amount,
            'rate'       => (float) ($data['conversion_rate'] ?? 0),
            'result'     => (float) ($data['conversion_result'] ?? 0),
            'updated_at' => $data['time_last_update_utc'] ?? null,
        ];
    }

    private function call($url)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => self::TIMEOUT_CONNECT,
            CURLOPT_TIMEOUT        => self::TIMEOUT_TOTAL,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 2,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_HTTPHEADER     => ['Accept: application/json'],
        ]);

        $body     = curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($body === false) {
            throw new ExternalServiceException(
                "Could not reach the currency service. Please try again later."
            );
        }
        if ($httpCode >= 500) {
            throw new ExternalServiceException(
                "The currency service is temporarily unavailable (HTTP $httpCode)."
            );
        }
        if ($httpCode >= 400) {
            throw new ExternalServiceException(
                "The currency service rejected the request (HTTP $httpCode)."
            );
        }

        $data = json_decode($body, true);
        if (!is_array($data)) {
            throw new ExternalServiceException(
                "The currency service returned an unexpected response."
            );
        }

        return $data;
    }
}