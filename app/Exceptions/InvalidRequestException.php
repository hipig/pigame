<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;

class InvalidRequestException extends Exception
{
    protected string $errorType;
    protected array $data;
    public function __construct(string $message = "", string $errorType = '', array $data = [], int $code = 400)
    {
        $this->errorType = $errorType;
        $this->data = $data;
        parent::__construct($message, $code);
    }

    public function render(Request $request)
    {
        $data = [
            'message' => $this->message,
            'error_type' => $this->errorType,
            'data' => $this->data
        ];

        if ($request->expectsJson()) {
            return response()->json($data, $this->code);
        }

        return null;
    }
}
