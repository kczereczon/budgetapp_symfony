<?php

namespace App\Tests\Services;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IngService extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();

//        $this->assertSame('test', $kernel->getEnvironment());
        $ingService = static::getContainer()->get(\App\Services\IngService::class);
        $data = $ingService->getGeneralAccessToken();
        $this->assertNotNull($data);
    }
}
