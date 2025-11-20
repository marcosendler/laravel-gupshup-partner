<?php

namespace App\Services\GupshupPartner\Exceptions;

use Exception;

class GupshupPartnerException extends Exception
{
    protected $statusCode;
    protected $responseData;

    public function __construct(string $message = "", int $statusCode = 0, array $responseData = [], \Throwable $previous = null)
    {
        parent::__construct($message, $statusCode, $previous);

        $this->statusCode = $statusCode;
        $this->responseData = $responseData;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getResponseData(): array
    {
        return $this->responseData;
    }

    public function toArray(): array
    {
        return [
            'error' => true,
            'message' => $this->getMessage(),
            'status_code' => $this->statusCode,
            'response_data' => $this->responseData,
        ];
    }
}
