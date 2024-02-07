<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class AppCustomException extends Exception
{
    private array $data;

    public function __construct(string $message, array $data)
    {
        $new_message = $message . ' : ' . json_encode($data, JSON_PRETTY_PRINT);
        parent::__construct($new_message);
        $this->data = $data;
    }

    public function getData(): array
    {
        return $this->data;
    }
}
