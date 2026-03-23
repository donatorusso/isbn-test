<?php
namespace App\DTO;

class ApiResponse
{
    public function __construct(
        public bool $success,
        public string $message,
        public mixed $data = null
    ){}

    public static function success(string $message='OK', $data): self
    {
        return new self(true, $message, $data);
    }

    public static function error(string $message='An error occurred', $data): self
    {
        return new self(false, $message, null);

    }

    public function isSuccess(): bool
    {
        return $this->success;
    }
}
