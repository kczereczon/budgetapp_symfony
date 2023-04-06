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

    public function testGenerateDigest(): void
    {
        $ingClientMock = $this->createPartialMock(IngClient::class, []);
        $this->assertEquals('SHA-256=47DEQpj8HBSa+/TImW+5JCeuQeRkm5NMpJWZG3hSuFU=', $ingClientMock->generateDigest(''));
    }

    /**
     * @throws \Exception
     */
    public function testGetSignature(): void
    {
        $kernel = self::bootKernel();
        $ingClient = static::getContainer()->get(IngClient::class);
        $this->assertEquals([
            'Authorization' => 'Signature keyId="5ca1ab1e-c0ca-c01a-cafe-154deadbea75",algorithm="rsa-sha256",headers="(request-target) date digest",signature="UOxMoSIebpl7CCs9pquEe1FqkQp37QxoPnn+0iHYJVke6q3fq63SFJ9rT3nnkTHj7XX20Mx7LvY8N0nrq4BZKmG4bxtA6MDVaGKW+5Sq9jWY/MVPcTQsyWRaIrxp3z+ZjkNLT0Cu/zyyfegCAFfSlNBMs+TdPv9V5Z+ctkPIWD1PLsoi1//SpNLxFQN8UDIk53A7b8MPLXVG+GcYeDLsfOsoKO4CfTgwGrFRcpH6vpiHbVHf0LDCkGku6DFQWnUrZF+Gg+6xJ4eNkhAdlgJ9H0pNqI/672g9fvcIrAJkLZHZEm93MAgBc7xx+LyDfGkA1l9ENnkqRw6bdDlSspzbGw=="'
        ], $ingClient->getSignatureHeader('post', '/foo?param=value&pet=dog', 'Sun, 05 Jan 2014 21:31:40 GMT', 'SHA-256=X48E9qOokqqrvdts8nOJRJN3OWDUoyWxBf7kbu9DBPE='));
    }
}
