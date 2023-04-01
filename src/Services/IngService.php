<?php

namespace App\Services;

use App\Providers\IngClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class IngService
{
    private IngClient $ingClient;

    public function __construct(IngClient $ingClient)
    {
        $this->ingClient = $ingClient;
    }
}