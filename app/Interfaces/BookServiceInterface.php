<?php

namespace App\Interfaces;

use App\DTO\ApiResponse;

interface BookServiceInterface
{
    public function searchByIsbn(string $isbn): ?ApiResponse;
}
