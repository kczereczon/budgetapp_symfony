<?php

namespace App\Tests\Providers;

use App\Providers\IngClient;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IngClientTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();

//        $this->assertSame('test', $kernel->getEnvironment());
         $ingClient = static::getContainer()->get(IngClient::class);
         $token = $ingClient->getGeneralAccessToken();
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }

    public function testGenerateDigest(): void {
        $ingClientMock = $this->createPartialMock(IngClient::class, []);
        $this->assertEquals('SHA-256=47DEQpj8HBSa+/TImW+5JCeuQeRkm5NMpJWZG3hSuFU=', $ingClientMock->generateDigest(''));
    }
}